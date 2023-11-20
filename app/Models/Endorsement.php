<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    use HasFactory;

    protected $table = 'endorsement';

    protected $guarded = [];  

    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'endorsement_id','id');
    }
}
