<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kuliner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'kuliner_id';

    protected $fillable = [
        'gambar_kuliner',
        'name_kuliner',
        'deskripsi',
        'harga_reguler',
        'harga_jumbo',
        'operasional',
        'lokasi',
        'latitude',
        'longitude'
    ];
}
