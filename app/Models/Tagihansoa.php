<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reasuradur;
use App\Models\TagihansoaPengajuan;

class Tagihansoa extends Model
{
    use HasFactory;

    protected $table = 'tagihan_soa';

    protected $guarded = [];

    public function reasuradur()
    {
        return $this->belongsTo(Reasuradur::class,'reasuradur_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(TagihansoaPengajuan::class,'tagihan_soa_id','id');
    }
}
