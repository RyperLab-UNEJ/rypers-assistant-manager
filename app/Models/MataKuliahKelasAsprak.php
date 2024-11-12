<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliahKelasAsprak extends Model
{
    protected $guarded = ['id'];

    public function mataKuliahKelas()
    {
        return $this->belongsTo(MataKuliahKelas::class);
    }

    public function asprak()
    {
        return $this->belongsTo(User::class, 'asprak_id', 'id');
    }
}
