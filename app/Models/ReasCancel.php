<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\Kepesertaan;

class ReasCancel extends Model
{
    use HasFactory;

    protected $table = 'reas_cancel';

    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'reas_cancel_id','id');
    }

    
}
