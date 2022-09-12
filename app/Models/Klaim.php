<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\Kepesertaan;

class Klaim extends Model
{
    use HasFactory;

    protected $table = 'klaim';

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }

    public function kepesertaan()
    {
        return $this->hasOne(Kepesertaan::class,'id','kepesertaan_id');
    }
}
