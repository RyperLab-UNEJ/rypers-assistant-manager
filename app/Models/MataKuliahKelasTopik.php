<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MataKuliahKelasTopik extends Model
{

    protected $guarded = ['id'];

    public function mataKuliahKelas()
    {
        return $this->belongsTo(MataKuliahKelas::class);
    }

    public function mataKuliahKelasTopiks()
    {
        return $this->hasMany(MataKuliahKelasTopikDetail::class);
    }
}
