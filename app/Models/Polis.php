<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;
use App\Models\Provinsi;
use App\Models\Kepersertaan;

class Polis extends Model
{
    use HasFactory;

    protected $table = 'polis';

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function pesertas()
    {
        return $this->hasMany(Kepesertaan::class,'polis_id','id');
    }
}
