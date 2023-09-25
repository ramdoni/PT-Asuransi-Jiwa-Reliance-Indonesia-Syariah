<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;

class MemoUjroh extends Model
{
    use HasFactory;

    protected $table = 'memo_ujroh';

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }
}
