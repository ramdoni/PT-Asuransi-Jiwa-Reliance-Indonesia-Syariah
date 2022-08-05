<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Polis;
use App\Models\Kepesertaan;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    public function polis()
    {
        return $this->belongsTo(Polis::class,'polis_id','id');
    }

    public function account_manager()
    {
        return $this->belongsTo(User::class,'account_manager_id','id');
    }

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'pengajuan_id','id');
    }
}
