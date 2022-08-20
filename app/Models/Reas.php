<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kepesertaan;
use App\Models\Reasuradur;

class Reas extends Model
{
    use HasFactory;

    protected $table = 'reas';

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'reas_id','id');
    }

    public function reasuradur()
    {
        return $this->hasOne(Reasuradur::class,'id','reasuradur_id');
    }

    public function rate_uw()
    {
        return $this->hasOne(ReasuradurRate::class,'id','reasuradur_rate_id');
    }
}
