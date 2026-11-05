<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class SyncKmcParts extends Command
{
    protected $signature = 'parts:sync-kmc {--file=storage\app\import\kmc.XLSX : مسیر فایل اکسل}';
    protected $description = 'Import KMC Excel, rebuild vehicle/replacement relations and categorize parts';

    /** دسته‌بندی‌ها بر اساس قوانین فعلی پروژه */
    private array $rules = [
        'موتوری' => [
            'تسمه تایم' => ['تسمه تایم', 'زنجیر تایم', 'سفت کن تایم', 'هرزگرد تایم'],
            'سرسیلندر و سوپاپ' => ['سرسیلندر', 'سوپاپ', 'میل سوپاپ', 'میل بادامک', 'اسبک', 'تایپیت', 'گاید سوپاپ'],
            'بلوک و قطعات داخلی موتور' => ['سیلندر', 'پیستون', 'رینگ موتور', 'شاتون', 'میل لنگ', 'یاتاقان', 'فلایویل'],
            'واشر و آب‌بندی موتور' => ['واشر سرسیلندر', 'واشر درب سوپاپ', 'کاسه نمد', 'واشر کارتل', 'واشر موتور'],
            'روغن‌کاری موتور' => ['اویل پمپ', 'پمپ روغن', 'فیلتر روغن', 'کارتل روغن', 'خنک کن روغن'],
            'متعلقات موتور' => ['دسته موتور', 'پایه موتور', 'قاب موتور', 'درپوش موتور'],
        ],
        'بدنه و تزئینات' => [
            'سپر و جلوپنجره' => ['سپر', 'جلوپنجره', 'دیفیوزر'],
            'درب و گلگیر' => ['گلگیر', 'درب خودرو', 'درب صندوق', 'کاپوت', 'لولای درب', 'قفل درب'],
            'چراغ و روشنایی' => ['چراغ جلو', 'چراغ عقب', 'چراغ مه شکن', 'چراغ راهنما', 'چراغ روز'],
            'آینه و شیشه' => ['آینه بغل', 'آینه جانبی', 'شیشه جلو', 'شیشه عقب', 'شیشه درب'],
            'تزئینات بدنه' => ['دستگیره درب', 'زه درب', 'نوار دور درب', 'آرم', 'لوگو', 'قاب آینه'],
        ],
        'جلوبندی و فرمان' => [
            'طبق و سیبک' => ['طبق', 'سیبک', 'بوش طبق'],
            'میل موج‌گیر' => ['میل موجگیر', 'میل موج گیر', 'لاستیک موجگیر', 'بوش موجگیر'],
            'فرمان' => ['جعبه فرمان', 'سیبک فرمان', 'قرقری فرمان', 'پمپ هیدرولیک', 'غربیلک فرمان'],
            'تعلیق جلو' => ['کمک فنر جلو', 'توپی سر کمک', 'فنر جلو'],
        ],
        'عقب‌بندی و تعلیق عقب' => [
            'تعلیق عقب' => ['کمک فنر عقب', 'فنر عقب', 'طبق عقب', 'بازوی عقب', 'بوش عقب', 'میل پیچشی'],
        ],
        'زیربندی و شاسی' => [
            'رام و شاسی' => ['رام جلو', 'رام عقب', 'شاسی', 'سینی زیر موتور', 'محافظ زیر موتور'],
            'توپی و بلبرینگ چرخ' => ['توپی چرخ', 'بلبرینگ چرخ', 'سگدست'],
        ],
        'برقی و الکترونیکی' => [
            'سنسور و کنترل الکترونیکی' => ['سنسور', 'ECU', 'کامپیوتر موتور', 'واحد کنترل', 'ماژول'],
            'استارت و شارژ' => ['استارت', 'دینام', 'آلترناتور'],
            'سیم‌کشی و اتصالات' => ['دسته سیم', 'سیم کشی', 'سوکت', 'رله', 'فیوز'],
            'جرقه و احتراق' => ['کوئل', 'شمع موتور', 'وایر شمع'],
            'باتری و برق' => ['باتری', 'کابل باتری', 'سنسور باتری'],
        ],
        'گیربکس و انتقال قدرت' => [
            'گیربکس' => ['گیربکس', 'دنده', 'چرخ دنده', 'شیر برقی گیربکس', 'ساعت گیربکس', 'والو بادی'],
            'کلاچ' => ['کلاچ', 'دیسک و صفحه', 'بلبرینگ کلاچ', 'دوشاخه کلاچ'],
            'پلوس و دیفرانسیل' => ['پلوس', 'سرپلوس', 'گردگیر پلوس', 'دیفرانسیل', 'گاردان'],
        ],
        'ترمز' => [
            'لنت و دیسک' => ['لنت ترمز', 'دیسک ترمز', 'کاسه ترمز'],
            'سیستم هیدرولیک ترمز' => ['پمپ ترمز', 'بوستر ترمز', 'سیلندر ترمز', 'شلنگ ترمز'],
            'ABS و کنترل ترمز' => ['ABS', 'ای بی اس', 'سنسور چرخ', 'پمپ ABS'],
        ],
        'خنک‌کاری' => [
            'رادیاتور و فن' => ['رادیاتور', 'فن رادیاتور', 'پروانه فن'],
            'پمپ و ترموستات' => ['واتر پمپ', 'پمپ آب', 'ترموستات'],
            'شلنگ و مخزن' => ['شلنگ آب', 'مخزن آب', 'منبع انبساط'],
        ],
        'سوخت‌رسانی و هوا' => [
            'سوخت‌رسانی' => ['پمپ بنزین', 'انژکتور', 'ریل سوخت', 'فیلتر بنزین', 'باک بنزین'],
            'ورودی هوا' => ['دریچه گاز', 'هواکش', 'فیلتر هوا', 'منیفولد هوا', 'توربو', 'اینترکولر'],
        ],
        'تهویه مطبوع و بخاری' => [
            'کولر' => ['کمپرسور کولر', 'کندانسور', 'اواپراتور', 'گاز کولر'],
            'بخاری و تهویه' => ['رادیاتور بخاری', 'فن بخاری', 'مقاومت فن', 'دریچه کولر'],
        ],
        'اگزوز و آلایندگی' => [
            'اگزوز' => ['اگزوز', 'منبع اگزوز', 'لوله اگزوز', 'کاتالیزور'],
            'کنترل آلایندگی' => ['سنسور اکسیژن', 'EGR', 'کنیستر'],
        ],
        'داخلی کابین' => [
            'داشبورد و کنسول' => ['داشبورد', 'کنسول', 'قاب داشبورد', 'جالیوانی'],
            'صندلی و کمربند' => ['صندلی', 'کمربند ایمنی', 'قفل کمربند', 'ریل صندلی'],
            'کلید و دستگیره داخلی' => ['کلید شیشه', 'کلید بالابر', 'دستگیره داخلی'],
        ],
        'چرخ و لاستیک' => [
            'چرخ' => ['رینگ چرخ', 'قالپاق', 'مهره چرخ', 'پیچ چرخ'],
            'لاستیک و فشار باد' => ['لاستیک', 'والو لاستیک', 'سنسور فشار باد'],
        ],
        'مصرفی و سرویس دوره‌ای' => [
            'فیلترها' => ['فیلتر روغن', 'فیلتر هوا', 'فیلتر کابین', 'فیلتر بنزین'],
            'روغن و مایعات' => ['روغن موتور', 'روغن گیربکس', 'ضدیخ', 'مایع ترمز'],
        ],
    ];

    public function handle(): int
    {
        if (!class_exists(ZipArchive::class) || !function_exists('simplexml_load_string')) {
            $this->error('افزونه‌های PHP با نام‌های zip و simplexml باید فعال باشند.');
            return self::FAILURE;
        }

        $fileOption = (string) $this->option('file');
        $file = str_starts_with($fileOption, '/') || preg_match('/^[A-Za-z]:\\\\/', $fileOption)
            ? $fileOption
            : base_path($fileOption);

        if (!is_file($file)) {
            $this->error("فایل اکسل پیدا نشد: {$file}");
            return self::FAILURE;
        }

        $this->info('در حال خواندن فایل اکسل...');
        try {
            $rows = $this->readXlsx($file);
        } catch (\Throwable $e) {
            $this->error('خواندن اکسل ناموفق بود: ' . $e->getMessage());
            return self::FAILURE;
        }

        if (!$rows) {
            $this->error('هیچ ردیف اطلاعاتی از اکسل خوانده نشد.');
            return self::FAILURE;
        }

        $required = ['نام_کالا', 'شماره_فني', 'مدل_به_کاررفته'];
        foreach ($required as $column) {
            if (!array_key_exists($column, $rows[0])) {
                $this->error('ستون موردنیاز پیدا نشد: ' . $column . ' | ستون‌های فایل: ' . implode(', ', array_keys($rows[0])));
                return self::FAILURE;
            }
        }

        // ردیف‌های دارای کد فنی یکسان ادغام می‌شوند تا خودروهای یک قطعه از بین نروند.
        $grouped = [];
        foreach ($rows as $row) {
            $code = trim((string) ($row['شماره_فني'] ?? ''));
            $name = trim((string) ($row['نام_کالا'] ?? ''));
            if ($code === '' || $name === '') {
                continue;
            }
            if (!isset($grouped[$code])) {
                $grouped[$code] = ['name' => $name, 'models' => []];
            }
            foreach ($this->splitList((string) ($row['مدل_به_کاررفته'] ?? '')) as $model) {
                $grouped[$code]['models'][$this->normalize($model)] = $model;
            }
        }

        if (!$grouped) {
            $this->error('هیچ ردیف دارای کد فنی و نام قطعه معتبر نبود.');
            return self::FAILURE;
        }

        $now = now()->toDateTimeString();
        $inserted = 0;
        $updated = 0;

        $this->info('شروع ورود/به‌روزرسانی قطعات...');
        DB::beginTransaction();
        try {
            foreach (array_chunk($grouped, 250, true) as $chunk) {
                foreach ($chunk as $code => $item) {
                    $existing = DB::table('parts')->where('technical_code', $code)->orderBy('id')->first();
                    $mergedModels = $item['models'];
                    if ($existing) {
                        // سازگاری خودروهای قبلی حفظ می‌شود و مدل‌های فایل جدید به آن اضافه می‌شوند.
                        foreach ($this->splitList((string) $existing->car_model_raw) as $oldModel) {
                            $mergedModels[$this->normalize($oldModel)] = $oldModel;
                        }
                    }
                    $rawModels = implode(';', array_values($mergedModels));

                    if ($existing) {
                        // قیمت، کد لجستیک، جایگزین‌ها، عکس، لینک و دسته‌بندی دستی حفظ می‌شوند.
                        DB::table('parts')->where('id', $existing->id)->update([
                            'name_fa' => $item['name'],
                            'car_model_raw' => $rawModels,
                            'updated_at' => $now,
                        ]);
                        $updated++;
                    } else {
                        DB::table('parts')->insert([
                            'technical_code' => $code,
                            'logistics_code' => null,
                            'name_fa' => $item['name'],
                            'price' => null,
                            'price_tax' => null,
                            'car_model_raw' => $rawModels,
                            'replace_raw' => null,
                            'category' => null,
                            'subcategory' => null,
                            'category_manual' => 0,
                            'img_src' => '',
                            'p_link' => '',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $inserted++;
                    }
                }
            }

            $this->info('ورود قطعات تمام شد؛ ساخت فهرست خودروها...');
            $allModels = [];
            DB::table('parts')->select('car_model_raw')->whereNotNull('car_model_raw')->orderBy('id')->chunk(1000, function ($parts) use (&$allModels) {
                foreach ($parts as $part) {
                    foreach ($this->splitList((string) $part->car_model_raw) as $name) {
                        $key = $this->normalize($name);
                        if ($key !== '' && !isset($allModels[$key])) {
                            $allModels[$key] = $name;
                        }
                    }
                }
            });

            $modelIds = [];
            foreach ($allModels as $key => $name) {
                $vehicle = DB::table('vehicle_models')->where('normalized_name', $key)->orderBy('id')->first();
                if (!$vehicle) {
                    // اگر رکورد قدیمی نام یکسان ولی normalized_name قدیمی داشته باشد، همان را اصلاح می‌کنیم.
                    $vehicle = DB::table('vehicle_models')->where('name', $name)->orderBy('id')->first();
                }
                if (!$vehicle) {
                    // نام unique است؛ در صورت وجود اختلاف حروف بزرگ/کوچک، collation دیتابیس هم رعایت می‌شود.
                    $vehicleId = DB::table('vehicle_models')->insertGetId([
                        'name' => $name,
                        'normalized_name' => $key,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    $vehicleId = $vehicle->id;
                    if ($vehicle->normalized_name !== $key) {
                        DB::table('vehicle_models')->where('id', $vehicleId)->update([
                            'normalized_name' => $key,
                            'updated_at' => $now,
                        ]);
                    }
                }
                $modelIds[$key] = $vehicleId;
            }

            $this->info('تکمیل ارتباط قطعات و خودروها (بدون حذف ارتباط‌های قبلی)...');
            $vehicleLinks = 0;
            DB::table('parts')->select('id', 'car_model_raw')->orderBy('id')->chunkById(500, function ($parts) use (&$vehicleLinks, $modelIds) {
                $links = [];
                foreach ($parts as $part) {
                    foreach ($this->splitList((string) $part->car_model_raw) as $name) {
                        $key = $this->normalize($name);
                        if ($key !== '' && isset($modelIds[$key])) {
                            $links[] = ['part_id' => $part->id, 'vehicle_model_id' => $modelIds[$key]];
                        }
                    }
                }
                foreach (array_chunk($links, 1000) as $linkChunk) {
                    DB::table('part_vehicle_model')->insertOrIgnore($linkChunk);
                    $vehicleLinks += count($linkChunk);
                }
            });

            $this->info('تکمیل ارتباط کدهای جایگزین (بدون حذف اطلاعات قبلی)...');
            $replacementCount = 0;
            DB::table('parts')->select('id', 'replace_raw')->whereNotNull('replace_raw')->orderBy('id')->chunkById(500, function ($parts) use (&$replacementCount, $now) {
                $replacementRows = [];
                foreach ($parts as $part) {
                    foreach ($this->splitList((string) $part->replace_raw) as $code) {
                        $replacementRows[] = [
                            'part_id' => $part->id,
                            'replacement_code' => $code,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
                foreach (array_chunk($replacementRows, 1000) as $replacementChunk) {
                    DB::table('part_replacements')->insertOrIgnore($replacementChunk);
                    $replacementCount += count($replacementChunk);
                }
            });

            $this->info('دسته‌بندی قطعاتی که دسته‌بندی دستی ندارند...');
            $classified = 0;
            DB::table('parts')->select('id', 'name_fa')->where('category_manual', false)->orderBy('id')->chunkById(500, function ($parts) use (&$classified) {
                foreach ($parts as $part) {
                    [$category, $subcategory] = $this->classify((string) $part->name_fa);
                    DB::table('parts')->where('id', $part->id)->update([
                        'category' => $category,
                        'subcategory' => $subcategory,
                    ]);
                    $classified++;
                }
            });

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('عملیات به دلیل خطا لغو شد و تغییرات دیتابیس rollback شدند: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('عملیات با موفقیت انجام شد.');
        $this->line("ردیف‌های یکتای اکسل: " . count($grouped));
        $this->line("قطعات جدید: {$inserted}");
        $this->line("قطعات به‌روزرسانی‌شده: {$updated}");
        $this->line('خودروهای شناسایی‌شده: ' . count($allModels));
        $this->line("ارتباط قطعه-خودرو: {$vehicleLinks}");
        $this->line("کدهای جایگزین بازسازی‌شده: {$replacementCount}");
        $this->line("قطعات دسته‌بندی‌شده/به‌روزشده: {$classified}");

        $summary = DB::table('parts')->select('category', DB::raw('COUNT(*) AS total'))->groupBy('category')->orderByDesc('total')->get();
        $this->newLine();
        $this->info('خلاصه دسته‌بندی کل قطعات:');
        foreach ($summary as $item) {
            $this->line(($item->category ?: 'بدون دسته') . ': ' . $item->total);
        }

        return self::SUCCESS;
    }

    private function normalize(string $value): string
    {
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);
        return mb_strtolower($value, 'UTF-8');
    }

    private function splitList(string $value): array
    {
        $items = preg_split('/[,،;؛\r\n]+/u', $value) ?: [];
        $result = [];
        $seen = [];
        foreach ($items as $item) {
            $item = trim(preg_replace('/\s+/u', ' ', $item) ?? $item);
            if ($item === '') {
                continue;
            }
            $key = $this->normalize($item);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $result[] = $item;
            }
        }
        return $result;
    }

    private function classify(string $name): array
    {
        $name = mb_strtolower(trim($name), 'UTF-8');
        foreach ($this->rules as $category => $subcategories) {
            foreach ($subcategories as $subcategory => $keywords) {
                foreach ($keywords as $keyword) {
                    if (mb_strpos($name, mb_strtolower($keyword, 'UTF-8'), 0, 'UTF-8') !== false) {
                        return [$category, $subcategory];
                    }
                }
            }
        }
        return ['متفرقه / نیازمند بررسی', 'دسته‌بندی نشده'];
    }

    /**
     * خواندن مستقیم XLSX بدون وابستگی به maatwebsite/excel یا PhpSpreadsheet.
     * این فایل اکسل دارای sheet1.xml و sharedStrings.xml است.
     */
    private function readXlsx(string $file): array
    {
        $zip = new ZipArchive();
        if ($zip->open($file) !== true) {
            throw new \RuntimeException('فایل XLSX باز نشد.');
        }

        try {
            $sharedStrings = [];
            $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
            if ($sharedXml !== false) {
                $shared = simplexml_load_string($sharedXml);
                if ($shared === false) {
                    throw new \RuntimeException('فایل sharedStrings.xml معتبر نیست.');
                }
                foreach ($shared->xpath('//*[local-name()="si"]') ?: [] as $si) {
                    $text = '';
                    foreach ($si->xpath('.//*[local-name()="t"]') ?: [] as $t) {
                        $text .= (string) $t;
                    }
                    $sharedStrings[] = $text;
                }
            }

            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
            if ($sheetXml === false) {
                throw new \RuntimeException('worksheet اول (sheet1.xml) در فایل پیدا نشد.');
            }
            $sheet = simplexml_load_string($sheetXml);
            if ($sheet === false) {
                throw new \RuntimeException('محتوای worksheet معتبر نیست.');
            }

            $matrix = [];
            foreach ($sheet->xpath('//*[local-name()="sheetData"]/*[local-name()="row"]') ?: [] as $rowNode) {
                $row = [];
                foreach ($rowNode->xpath('./*[local-name()="c"]') ?: [] as $cell) {
                    $reference = (string) $cell['r'];
                    if (!preg_match('/^([A-Z]+)/', $reference, $matches)) {
                        continue;
                    }
                    $column = $this->columnNumber($matches[1]);
                    $type = (string) $cell['t'];
                    $value = '';

                    if ($type === 'inlineStr') {
                        foreach ($cell->xpath('.//*[local-name()="t"]') ?: [] as $t) {
                            $value .= (string) $t;
                        }
                    } else {
                        $v = $cell->xpath('./*[local-name()="v"]');
                        $raw = isset($v[0]) ? (string) $v[0] : '';
                        if ($type === 's' && $raw !== '') {
                            $value = $sharedStrings[(int) $raw] ?? '';
                        } else {
                            $value = $raw;
                        }
                    }
                    $row[$column] = $value;
                }
                if ($row) {
                    ksort($row);
                    $matrix[] = $row;
                }
            }

            if (!$matrix) {
                return [];
            }

            $headerRow = array_shift($matrix);
            $headers = [];
            foreach ($headerRow as $column => $heading) {
                $headers[$column] = trim((string) $heading);
            }

            $result = [];
            foreach ($matrix as $row) {
                $item = [];
                foreach ($headers as $column => $heading) {
                    if ($heading !== '') {
                        $item[$heading] = $row[$column] ?? '';
                    }
                }
                if (array_filter($item, fn ($v) => trim((string) $v) !== '') !== []) {
                    $result[] = $item;
                }
            }
            return $result;
        } finally {
            $zip->close();
        }
    }

    private function columnNumber(string $letters): int
    {
        $number = 0;
        foreach (str_split($letters) as $letter) {
            $number = ($number * 26) + (ord($letter) - 64);
        }
        return $number;
    }
}
