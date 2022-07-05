<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;

class Kepesertaan extends Model
{
    use HasFactory;

    protected $table = 'kepesertaan';

    public function parent()
    {
        return $this->belongsTo(Kepesertaan::class,'parent_id','id');
    }

    public function polis()
    {
        return $this->hasOne(Polis::class,'id','polis_id');
    }
}
