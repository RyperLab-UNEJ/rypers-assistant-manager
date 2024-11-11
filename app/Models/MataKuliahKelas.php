<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliahKelas extends Model
{
    protected $guarded = ['id'];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
