<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BankBook;

class BankBookPairing extends Model
{
    use HasFactory;

    protected $table = 'bank_book_pairing';

    public function bank_book()
    {
        return $this->belongsTo(BankBook::class,'bank_book_id','id');
    }
}
