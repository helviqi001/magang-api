<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wisata extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'latitude',
        'longitude'
    ];
}
