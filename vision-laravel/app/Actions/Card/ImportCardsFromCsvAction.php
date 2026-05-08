<?php

namespace App\Actions\Card;

use App\Models\Card;
use App\Models\Package;

class ImportCardsFromCsvAction
{
    /**
     * CSV rows: `code` column required; optional second column `serial_number`.
     *
     * @return array{created:int, errors:list<array{line:int, message:string}>}
     */
    public function execute(Package $package, string $csvBody): array
    {
        $created = 0;
        $errors = [];
        $lines = preg_split('/\r\n|\r|\n/', trim($csvBody)) ?: [];

        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1;
            if (trim($line) === '') {
                continue;
            }

            $row = str_getcsv($line);
            $code = isset($row[0]) ? trim((string) $row[0]) : '';

            if ($code === '') {
                $errors[] = ['line' => $lineNumber, 'message' => 'Missing card code.'];

                continue;
            }

            if (Card::query()->where('code', $code)->exists()) {
                $errors[] = ['line' => $lineNumber, 'message' => "Duplicate code {$code}."];

                continue;
            }

            try {
                Card::query()->create([
                    'seller_id' => $package->seller_id,
                    'network_id' => $package->network_id,
                    'package_id' => $package->id,
                    'code' => $code,
                    'serial_number' => isset($row[1]) ? trim((string) $row[1]) ?: null : null,
                    'price' => $package->price,
                    'status' => 'active',
                    'meta' => ['import_batch' => 'csv'],
                ]);
                $created++;
            } catch (\Throwable $exception) {
                $errors[] = ['line' => $lineNumber, 'message' => $exception->getMessage()];
            }
        }

        return ['created' => $created, 'errors' => $errors];
    }
}
