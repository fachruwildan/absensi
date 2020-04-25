<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = "point";
    protected $fillable = [
        "kategori_point_id",
        "peraturan",
        "interval_waktu",
        "point",
        "sanksi",
    ];

    function kasus() {
        return $this->hasMany('App\Models\Kasus');
    }
    function kategoriPoint() {
        return $this->belongsTo('App\Models\KategoriPoint');
    }
}

