<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class ImportBrakePads extends Command
{
    protected $signature = 'brake-pads:import
        {file? : XLSX file path; defaults to storage/app/import/lent.xlsx}
        {--assume-g-is-6 : Treat letter G in source price strings as digit 6; otherwise such prices are left null}';

    protected $description = 'Import brake pad data, vehicle fitments and other compatibilities from the brake-pad XLSX workbook';

    public function handle(): int
    {
        $file = $this->argument('file') ?: storage_path('app/import/lent.xlsx');
        if (!is_file($file)) {
            $this->error("Excel file not found: {$file}");
            return self::FAILURE;
        }
        if (!class_exists(ZipArchive::class) || !function_exists('simplexml_load_string')) {
            $this->error('PHP extensions zip and simplexml must be enabled.');
            return self::FAILURE;
        }

        try {
            $sheets = $this->readWorkbook($file);
        } catch (\Throwable $e) {
            $this->error('Could not read XLSX: '.$e->getMessage());
            return self::FAILURE;
        }

        $main = $sheets['لنت های خودروهای چینی'] ?? null;
        $byVehicle = $sheets['مشترکات به تفکیک خودرو'] ?? null;
        if (!$main || count($main) < 2) {
            $this->error('Sheet "لنت های خودروهای چینی" is missing or empty.');
            return self::FAILURE;
        }

        $mainRows = $this->rowsToAssociative($main);
        $fitments = [];
        if ($byVehicle && count($byVehicle) > 1) {
            foreach ($this->rowsToAssociative($byVehicle) as $row) {
                $code = trim((string)($row['کد فنی لنت'] ?? ''));
                $vehicle = trim((string)($row['نام خودرو چینی'] ?? ''));
                if ($code !== '' && $vehicle !== '') {
                    $fitments[$code][] = $vehicle;
                }
            }
        }

        $this->info('Rows in main sheet: '.count($mainRows));
        $created = $updated = $vehicleLinks = $compatibilityCount = 0;
        $unparsedPrices = [];
        $unknownPositions = [];
        $now = now();

        DB::transaction(function () use (
            $mainRows, $fitments, $now, &$created, &$updated,
            &$vehicleLinks, &$compatibilityCount, &$unparsedPrices, &$unknownPositions
        ) {
            foreach ($mainRows as $row) {
                $code = trim((string)($row['کد فنی'] ?? ''));
                $name = trim((string)($row['نام کالا / نوع لنت'] ?? ''));
                if ($code === '' || $name === '') {
                    continue;
                }

                $position = $this->detectPosition($name);
                if ($position === null) {
                    $unknownPositions[] = $code.' | '.$name;
                }
                $rawPrice = trim((string)($row['قیمت'] ?? ''));
                $price = $this->parsePrice($rawPrice, (bool)$this->option('assume-g-is-6'));
                if ($rawPrice !== '' && $price === null) {
                    $unparsedPrices[] = $code.' | '.$rawPrice;
                }
                $quantity = $this->parseInteger($row['تعداد در کارتن'] ?? null);
                $rawOther = trim((string)($row['سایر مشترکات درج شده در توضیحات'] ?? ''));
                if ($rawOther === '') {
                    // Some Excel exports retain a zero-width joiner in this heading; find by prefix.
                    foreach ($row as $header => $value) {
                        if (str_contains($header, 'سایر مشترکات')) {
                            $rawOther = trim((string)$value);
                            break;
                        }
                    }
                }

                $existing = DB::table('brake_bank_pads')->where('technical_code', $code)->first();
                $data = [
                    'technical_code' => $code,
                    'name' => $name,
                    'position' => $position,
                    'pad_type' => $this->detectPadType($name),
                    'other_compatibility_raw' => $rawOther !== '' ? $rawOther : null,
                    'carton_quantity' => $quantity,
                    'source_row' => $this->parseInteger($row['ردیف فایل مبدا'] ?? null),
                    'updated_at' => $now,
                ];
                // Do not erase an already entered price when the source value is malformed/ambiguous.
                if ($price !== null || !$existing) {
                    $data['price'] = $price;
                }

                if ($existing) {
                    DB::table('brake_bank_pads')->where('id', $existing->id)->update($data);
                    $padId = $existing->id;
                    $updated++;
                } else {
                    $data['created_at'] = $now;
                    $padId = DB::table('brake_bank_pads')->insertGetId($data);
                    $created++;
                }

                // The vehicle-specific sheet is preferred; main-sheet fitments are a fallback.
                $vehicleNames = $fitments[$code] ?? $this->splitList((string)($row['خودروهای چینی مشترک'] ?? ''));
                foreach (array_unique($vehicleNames) as $vehicleName) {
                    $vehicleName = trim($vehicleName);
                    if ($vehicleName === '') continue;
                    $vehicleId = $this->findOrCreateVehicle($vehicleName, $now);
                    $inserted = DB::table('brake_bank_pad_vehicle')->insertOrIgnore([
                        'brake_pad_id' => $padId,
                        'brake_pad_vehicle_id' => $vehicleId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $vehicleLinks += $inserted;
                }

                // Replace this pad's compatibility list from this workbook; images are never touched.
                DB::table('brake_bank_pad_compatibilities')->where('brake_pad_id', $padId)->delete();
                foreach ($this->splitOtherCompatibilities($rawOther) as $compatName) {
                    DB::table('brake_bank_pad_compatibilities')->insertOrIgnore([
                        'brake_pad_id' => $padId,
                        'vehicle_name' => mb_substr($compatName, 0, 191),
                        'notes' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $compatibilityCount++;
                }
            }
        }, 3);

        $this->newLine();
        $this->info("New brake pads: {$created}");
        $this->info("Updated brake pads: {$updated}");
        $this->info("New vehicle links: {$vehicleLinks}");
        $this->info("Compatibility entries processed: {$compatibilityCount}");
        $this->info('Total brake pads in database: '.DB::table('brake_bank_pads')->count());

        if ($unparsedPrices) {
            $this->warn('Prices needing review (left unchanged/null):');
            foreach ($unparsedPrices as $item) $this->line(' - '.$item);
            if (!$this->option('assume-g-is-6')) {
                $this->comment('If G in these source cells is definitely the digit 6, rerun with --assume-g-is-6.');
            }
        }
        if ($unknownPositions) {
            $this->warn('Position not explicit in source (left null):');
            foreach ($unknownPositions as $item) $this->line(' - '.$item);
        }

        $this->info('Import completed.');
        return self::SUCCESS;
    }

    private function readWorkbook(string $path): array
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) throw new RuntimeException('Invalid XLSX/ZIP file.');

        $shared = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $xml = simplexml_load_string($sharedXml);
            foreach ($xml->xpath('//*[local-name()="si"]') ?: [] as $si) {
                $texts = $si->xpath('.//*[local-name()="t"]') ?: [];
                $value = '';
                foreach ($texts as $t) $value .= (string)$t;
                $shared[] = $value;
            }
        }

        $workbookXml = $zip->getFromName('xl/workbook.xml');
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if ($workbookXml === false || $relsXml === false) throw new RuntimeException('Workbook metadata not found.');
        $workbook = simplexml_load_string($workbookXml);
        $rels = simplexml_load_string($relsXml);
        $targets = [];
        foreach ($rels->xpath('//*[local-name()="Relationship"]') ?: [] as $rel) {
            $targets[(string)$rel['Id']] = (string)$rel['Target'];
        }

        $result = [];
        foreach ($workbook->xpath('//*[local-name()="sheet"]') ?: [] as $sheet) {
            $name = (string)$sheet['name'];
            $rid = '';
            foreach ($sheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships') as $key => $value) {
                if ($key === 'id') $rid = (string)$value;
            }
            if ($rid === '' || !isset($targets[$rid])) continue;
            $target = ltrim($targets[$rid], '/');
            $sheetPath = str_starts_with($target, 'xl/') ? $target : 'xl/'.$target;
            $sheetXml = $zip->getFromName($sheetPath);
            if ($sheetXml === false) continue;
            $result[$name] = $this->parseWorksheet($sheetXml, $shared);
        }
        $zip->close();
        return $result;
    }

    private function parseWorksheet(string $xmlString, array $shared): array
    {
        $xml = simplexml_load_string($xmlString);
        $rows = [];
        foreach ($xml->xpath('//*[local-name()="sheetData"]/*[local-name()="row"]') ?: [] as $row) {
            $cells = [];
            foreach ($row->xpath('./*[local-name()="c"]') ?: [] as $cell) {
                $ref = (string)$cell['r'];
                preg_match('/^[A-Z]+/', $ref, $m);
                $col = $this->columnNumber($m[0] ?? 'A');
                $type = (string)$cell['t'];
                $value = '';
                if ($type === 'inlineStr') {
                    foreach ($cell->xpath('.//*[local-name()="t"]') ?: [] as $t) $value .= (string)$t;
                } else {
                    $v = $cell->xpath('./*[local-name()="v"]');
                    $raw = isset($v[0]) ? (string)$v[0] : '';
                    $value = $type === 's' ? ($shared[(int)$raw] ?? '') : $raw;
                }
                $cells[$col] = $value;
            }
            if ($cells) {
                $max = max(array_keys($cells));
                $line = [];
                for ($i = 1; $i <= $max; $i++) $line[] = $cells[$i] ?? '';
                $rows[] = $line;
            }
        }
        return $rows;
    }

    private function columnNumber(string $letters): int
    {
        $number = 0;
        foreach (str_split($letters) as $letter) $number = $number * 26 + (ord($letter) - 64);
        return $number;
    }

    private function rowsToAssociative(array $rows): array
    {
        $headers = array_map(fn($v) => trim((string)$v), array_shift($rows) ?? []);
        $out = [];
        foreach ($rows as $row) {
            $item = [];
            foreach ($headers as $i => $header) if ($header !== '') $item[$header] = $row[$i] ?? '';
            if (array_filter($item, fn($v) => trim((string)$v) !== '')) $out[] = $item;
        }
        return $out;
    }

    private function normalize(string $value): string
    {
        $value = str_replace(['ي', 'ك', 'ۀ', 'ة', "\u{200c}", "\u{200f}", "\u{200e}"], ['ی', 'ک', 'ه', 'ه', ' ', '', ''], $value);
        $value = mb_strtolower($value, 'UTF-8');
        return trim(preg_replace('/\s+/u', ' ', $value));
    }

    private function findOrCreateVehicle(string $name, $now): int
    {
        $normalized = $this->normalize($name);
        $vehicle = DB::table('brake_bank_vehicles')->where('normalized_name', $normalized)->first();
        if ($vehicle) return (int) $vehicle->id;

        return (int) DB::table('brake_bank_vehicles')->insertGetId([
            'name' => $name,
            'normalized_name' => $normalized,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function splitList(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/[,،;؛\r\n]+/u', $value) ?: [])));
    }

    private function splitOtherCompatibilities(string $value): array
    {
        if (trim($value) === '') return [];
        $items = preg_split('/[,،;؛\r\n]+|(?<=\s)[-–—]\s*/u', $value) ?: [];
        return array_values(array_unique(array_filter(array_map('trim', $items))));
    }

    private function detectPosition(string $name): ?string
    {
        $hasFront = preg_match('/جلو/u', $name) === 1;
        $hasRear = preg_match('/عقب/u', $name) === 1;
        if ($hasFront && !$hasRear) return 'front';
        if ($hasRear && !$hasFront) return 'rear';
        return null;
    }

    private function detectPadType(string $name): ?string
    {
        if (str_contains($name, 'کفشکی')) return 'کفشکی';
        return null;
    }

    private function parsePrice(string $value, bool $assumeGIs6): ?float
    {
        $value = trim(str_replace([',', '٬', ' '], '', $value));
        if ($value === '') return null;
        if ($assumeGIs6) $value = str_replace(['G', 'g'], '6', $value);
        $value = strtr($value, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9','٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9']);
        return is_numeric($value) ? (float)$value : null;
    }

    private function parseInteger($value): ?int
    {
        $value = trim((string)$value);
        if ($value === '') return null;
        $value = strtr($value, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
        return is_numeric($value) ? (int)$value : null;
    }
}
