<?php

namespace App\Imports\Admin;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportAsistenPraktikum implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row)
        {
            $asisten = User::firstOrCreate(
                [
                    'name' => $row['nama'],
                    'email' => $row['nim'] . '@am.test',
                ],
                [
                    'password' => bcrypt('password'),
                ]
            );

            $asisten->assignRole('asprak');
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
