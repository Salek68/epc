<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BuildPartRelations extends Command
{
    protected $signature = 'parts:build-relations';
    protected $description = 'Build vehicle and replacement relations';

    public function handle(): int
    {
        $this->info('Building vehicle models...');

        $parts = DB::table('parts')
            ->select('id', 'car_model_raw', 'replace_raw')
            ->orderBy('id')
            ->get();

        $models = [];
        $now = now()->toDateTimeString();

        foreach ($parts as $part) {
            $names = preg_split(
                '/[,،;؛\r\n]+/u',
                (string) $part->car_model_raw
            );

            foreach ($names as $name) {
                $name = trim(preg_replace('/\s+/u', ' ', $name));

                if ($name === '') {
                    continue;
                }

                $key = mb_strtolower($name);

                if (!isset($models[$key])) {
                    $models[$key] = [
                        'name' => $name,
                        'normalized_name' => $key,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk(array_values($models), 500) as $chunk) {
            DB::table('vehicle_models')->upsert(
                $chunk,
                ['normalized_name'],
                ['name', 'updated_at']
            );
        }

        $this->info('Vehicle models: ' . count($models));

        $modelIds = DB::table('vehicle_models')
            ->pluck('id', 'normalized_name');

        $this->info('Building part-to-vehicle links...');

        $links = [];

        foreach ($parts as $part) {
            $names = preg_split(
                '/[,،;؛\r\n]+/u',
                (string) $part->car_model_raw
            );

            foreach ($names as $name) {
                $key = mb_strtolower(
                    trim(preg_replace('/\s+/u', ' ', $name))
                );

                if ($key !== '' && isset($modelIds[$key])) {
                    $links[] = [
                        'part_id' => $part->id,
                        'vehicle_model_id' => $modelIds[$key],
                    ];
                }
            }
        }

        foreach (array_chunk($links, 1000) as $chunk) {
            DB::table('part_vehicle_model')->insertOrIgnore($chunk);
        }

        $this->info('Vehicle links processed: ' . count($links));
        $this->info('Building replacement codes...');

        $replacementRows = [];

        foreach ($parts as $part) {
            $codes = preg_split(
                '/[,،;؛\r\n]+/u',
                (string) $part->replace_raw
            );

            foreach ($codes as $code) {
                $code = trim($code);

                if ($code === '') {
                    continue;
                }

                $replacementRows[] = [
                    'part_id' => $part->id,
                    'replacement_code' => $code,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($replacementRows) >= 1000) {
                    DB::table('part_replacements')
                        ->insertOrIgnore($replacementRows);

                    $replacementRows = [];
                }
            }
        }

        if ($replacementRows) {
            DB::table('part_replacements')
                ->insertOrIgnore($replacementRows);
        }

        $this->info(
            'Part-vehicle links: ' .
            DB::table('part_vehicle_model')->count()
        );

        $this->info(
            'Replacement records: ' .
            DB::table('part_replacements')->count()
        );

        $this->info('Relation building completed.');

        return self::SUCCESS;
    }
}