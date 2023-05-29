<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;

class RateBroker extends Model
{
    use HasFactory;

    protected $table = 'rate_broker';

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }
}