<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Endorsement extends Model
{
    use HasFactory;

    protected $table = 'endorsement';

    protected $guarded = [];  

    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class,'requester_id','id');
    }

    public function pesertas()
    {
        return $this->hasMany(EndorsementPeserta::class,'endorsement_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'endorsement_id','id');
    }

    public function jenis_perubahan()
    {
        return $this->belongsTo(JenisPerubahan::class,'jenis_perubahan_id','id');
    }
}
