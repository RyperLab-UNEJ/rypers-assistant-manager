<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliahKelasMahasiswa extends Model
{
    protected $guarded = ['id'];

    public function mataKuliahKelas()
    {
        return $this->belongsTo(MataKuliahKelas::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }
}
