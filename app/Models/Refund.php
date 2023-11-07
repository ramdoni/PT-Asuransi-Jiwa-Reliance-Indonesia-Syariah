<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\User;

class Refund extends Model
{
    use HasFactory;

    protected $table = 'refund';

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }

    public function user_created()
    {
        return $this->hasOne(User::class,'id','user_created_id');
    }

    public function user_head_teknik()
    {
        return $this->hasOne(User::class,'id','user_head_teknik_id');
    }

    public function user_head_syariah()
    {
        return $this->hasOne(User::class,'id','user_head_syariah_id');
    }
    
    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'memo_refund_id','id');
    }
}
