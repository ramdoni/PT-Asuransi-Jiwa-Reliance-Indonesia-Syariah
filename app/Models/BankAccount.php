<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    protected $dates = ['deleted_at'];
    use HasFactory;
    
    public function coa()
    {
        return $this->hasOne('\App\Models\Coa','id','coa_id');
    }
}
