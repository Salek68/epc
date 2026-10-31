<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Part;
use App\Models\VehicleModel;
use App\Models\PartReplacement;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PartsImport implements ToCollection
{
 public int $read = 0;
    public int $saved = 0;
    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $values = array_values($row->toArray());

            // سطر عنوان اکسل را رد می‌کنیم.
            if (
                isset($values[0]) &&
                trim((string) $values[0]) === 'TechnicalCode'
            ) {
                continue;
            }

            $this->read++;

            $technicalCode = trim((string) ($values[0] ?? ''));
            $logisticsCode = trim((string) ($values[1] ?? ''));
            $name = trim((string) ($values[2] ?? ''));
            $price = $values[3] ?? null;
            $priceTax = $values[4] ?? null;
            $carModels = trim((string) ($values[5] ?? ''));
            $replaceRaw = trim((string) ($values[6] ?? ''));

            if ($technicalCode === '' || $name === '') {
                $this->skipped++;
                continue;
            }

            DB::transaction(function () use (
                $technicalCode,
                $logisticsCode,
                $name,
                $price,
                $priceTax,
                $carModels,
                $replaceRaw
            ) {
                $part = Part::create([
                    'technical_code' => $technicalCode,
                    'logistics_code' => $logisticsCode ?: null,
                    'name_fa' => $name,
                    'price' => is_numeric($price) ? $price : null,
                    'price_tax' => is_numeric($priceTax) ? $priceTax : null,
                    'car_model_raw' => $carModels ?: null,
                    'replace_raw' => $replaceRaw ?: null,
                ]);

                foreach (explode(',', $carModels) as $modelName) {
                    $modelName = preg_replace(
                        '/\s+/u',
                        ' ',
                        trim($modelName)
                    );

                    if ($modelName === '') {
                        continue;
                    }

                    $model = VehicleModel::firstOrCreate(
                        ['normalized_name' => mb_strtolower($modelName)],
                        ['name' => $modelName]
                    );

                    $part->vehicleModels()
                        ->syncWithoutDetaching([$model->id]);
                }

                foreach (explode(',', $replaceRaw) as $code) {
                    $code = trim($code);

                    if ($code !== '') {
                        PartReplacement::create([
                            'part_id' => $part->id,
                            'replacement_code' => $code,
                        ]);
                    }
                }
            });

            $this->saved++;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }

}
