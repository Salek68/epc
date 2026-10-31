<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PartsImport;

class ImportPartsCommand extends Command
{
   protected $signature = 'parts:import';

    protected $description = 'Import CHERY MVM parts from Excel';

    public function handle(): int
    {
          $file = storage_path(
        'app/import/TOTALPARTS(1405.03.09).xlsx'
    );

    if (! file_exists($file)) {
        $this->error('Excel file not found: ' . $file);
        return self::FAILURE;
    }

    $import = new \App\Imports\PartsImport();

    $this->info('Importing parts...');

    try {
        \Maatwebsite\Excel\Facades\Excel::import($import, $file);

        $this->info('Rows read: ' . $import->read);
        $this->info('Rows saved: ' . $import->saved);
        $this->info('Rows skipped: ' . $import->skipped);

        $this->info(
            'Total parts in database: ' .
            \Illuminate\Support\Facades\DB::table('parts')->count()
        );

        return self::SUCCESS;
    } catch (\Throwable $e) {
        $this->error('Import failed: ' . $e->getMessage());
        return self::FAILURE;
    }
    }
}
