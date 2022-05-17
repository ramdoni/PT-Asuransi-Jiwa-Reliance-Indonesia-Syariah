<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coa;

class CoaGroup extends Model
{
    use HasFactory;

    public function coa()
    {
        return $this->hasMany(Coa::class,'coa_group_id','id');
    }
}
