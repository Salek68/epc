<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPartsFast extends Command
{
    protected $signature = 'parts:import-fast';
    protected $description = 'Fast import of CHERY MVM parts';

    public function handle(): int
    {
        $file = storage_path(
            'app/import/TOTALPARTS(1405.03.09).xlsx'
        );

        if (!is_file($file)) {
            $this->error("Excel file not found: {$file}");
            return self::FAILURE;
        }

        $this->info('Reading Excel file...');

        $rows = Excel::toArray(new class implements
            ToArray, WithHeadingRow, WithChunkReading
        {
            public function array(array $array): void {}
            public function chunkSize(): int { return 1000; }
        }, $file);

        $data = $rows[0] ?? [];

        if (count($data) === 0) {
            $this->error('No data rows were read from Excel.');
            return self::FAILURE;
        }

        $this->info('Rows found: ' . count($data));

        $required = [
            'technicalcode',
            'partnameper',
            'logisticscode',
            'price',
            'pricetax',
            'carmodel',
            'replace',
        ];

        $first = $data[0];

        foreach ($required as $column) {
            if (!array_key_exists($column, $first)) {
                $this->error(
                    "Missing column: {$column}. Found: " .
                    implode(', ', array_keys($first))
                );
                return self::FAILURE;
            }
        }

        $now = now()->toDateTimeString();
        $saved = 0;

        foreach (array_chunk($data, 500) as $chunk) {
            $insert = [];

            foreach ($chunk as $row) {
                $code = trim((string)($row['technicalcode'] ?? ''));
                $name = trim((string)($row['partnameper'] ?? ''));

                if ($code === '' || $name === '') {
                    continue;
                }

                $insert[] = [
                    'technical_code' => $code,
                    'logistics_code' => $row['logisticscode'] ?? null,
                    'name_fa' => $name,
                    'price' => is_numeric($row['price'] ?? null)
                        ? $row['price'] : null,
                    'price_tax' => is_numeric($row['pricetax'] ?? null)
                        ? $row['pricetax'] : null,
                    'car_model_raw' => $row['carmodel'] ?? null,
                    'replace_raw' => $row['replace'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($insert) {
                DB::table('parts')->insert($insert);
                $saved += count($insert);
            }

            $this->line("Saved: {$saved}");
        }

        $this->info('Import finished.');
        $this->info(
            'Total parts in database: ' .
            DB::table('parts')->count()
        );

        return self::SUCCESS;
    }
}