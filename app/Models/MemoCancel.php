<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\Kepesertaan;
use App\Models\User;

class MemoCancel extends Model
{
    use HasFactory;

    protected $table = 'memo_cancel';

    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class,'requester_id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'memo_cancel_id','id');
    }
}