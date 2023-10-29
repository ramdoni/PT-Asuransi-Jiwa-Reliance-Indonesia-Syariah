<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\User;

class MemoUjroh extends Model
{
    use HasFactory;

    protected $table = 'memo_ujroh';

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }

    public function user_teknik()
    {
        return $this->hasOne(User::class,'id','user_teknik_id');
    }
}
