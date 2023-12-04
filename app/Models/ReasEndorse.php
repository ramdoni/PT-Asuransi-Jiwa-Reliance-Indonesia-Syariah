<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\Reas;
use App\Models\Kepesertaan;
use App\Models\Endorsement;

class ReasEndorse extends Model
{
    use HasFactory;

    protected $table = 'reas_endorse';
    
    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function endorsement()
    {
        return $this->belongsTo(Endorsement::class,'endorsement_id','id');
    }

    public function reas()
    {
        return $this->belongsTo(Reas::class,'reas_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'reas_endorse_id','id');
    }
}
