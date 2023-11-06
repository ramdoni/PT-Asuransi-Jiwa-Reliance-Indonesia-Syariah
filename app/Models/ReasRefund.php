<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\Refund;
use App\Models\Reas;
use App\Models\Kepesertaan;

class ReasRefund extends Model
{
    use HasFactory;

    protected $table = 'reas_refund';

    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function memo_refund()
    {
        return $this->belongsTo(Refund::class,'memo_refund_id','id');
    }

    public function reas()
    {
        return $this->belongsTo(Reas::class,'reas_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'reas_refund_id','id');
    }
}
