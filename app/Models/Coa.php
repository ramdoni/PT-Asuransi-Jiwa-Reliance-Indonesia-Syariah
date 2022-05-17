<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Journal;

class Coa extends Model
{
    use HasFactory;
    protected $guarded = [];
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function group()
    {
        return $this->hasOne('\App\Models\CoaGroup','id','coa_group_id');
    }

    public function type()
    {
        return $this->hasOne('\App\Models\CoaType','id','coa_type_id');
    }

    public function journal()
    {
        return $this->hasMany(Journal::class,'coa_id','id');
    }
}
