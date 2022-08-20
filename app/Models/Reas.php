<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kepesertaan;

class Reas extends Model
{
    use HasFactory;

    protected $table = 'reas';

    public function kepesertaan()
    {
        return $this->hasMany(Kepesertaan::class,'reas_id','id');
    }
}
