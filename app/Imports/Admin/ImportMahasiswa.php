<?php

namespace App\Imports\Admin;

use App\Models\MataKuliahKelasMahasiswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportMahasiswa implements ToCollection, WithHeadingRow
{
    public $kelasId;

    public function __construct($kelasId)
    {
        $this->kelasId = $kelasId;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row)
        {
            $mahasiswa = User::firstOrCreate([
                'name' => $row['nama'],
                'email' => $row['nim'] . "@am.test",
            ],
            [
                'password' => bcrypt('password'),
            ]);

            $mahasiswa->assignRole('mahasiswa');

            MataKuliahKelasMahasiswa::firstOrCreate([
                'mata_kuliah_kelas_id' => $this->kelasId,
                'mahasiswa_id' => $mahasiswa->id,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
