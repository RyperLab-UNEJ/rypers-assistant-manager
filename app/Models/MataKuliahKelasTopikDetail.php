<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MataKuliahKelasTopikDetail extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $guarded = ['id'];

    public function mataKuliahKelasTopik()
    {
        return $this->belongsTo(MataKuliahKelasTopik::class);
    }
}
