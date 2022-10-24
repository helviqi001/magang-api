<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $primaryKey = 'wisata_id';

    protected $fillable = [
        'gambar_wisata',
        'name_wisata',
        'deskripsi',
        'harga_dewasa',
        'harga_anak',
        'fasilitas',
        'operasional',
        'lokasi',
    ];
}
