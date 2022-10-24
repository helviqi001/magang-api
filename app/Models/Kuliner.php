<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuliner extends Model
{
    use HasFactory;

    protected $primaryKey = 'kuliner_id';

    protected $fillable = [
        'gambar_kuliner',
        'name_kuliner',
        'deskripsi',
        'harga_reguler',
        'harga_jumbo',
        'operasional',
        'lokasi',
    ];
}
