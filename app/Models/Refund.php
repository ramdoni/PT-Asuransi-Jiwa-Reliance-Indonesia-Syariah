<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;

class Refund extends Model
{
    use HasFactory;

    protected $table = 'refund';

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }
    
    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'memo_cancel_id','id');
    }
}
