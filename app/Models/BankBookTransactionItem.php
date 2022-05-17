<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BankBook;

class BankBookTransactionItem extends Model
{
    use HasFactory;

    protected $table = 'bank_book_transaction_item';

    public function bank_books()
    {
        return $this->hasOne(BankBook::class,'bank_book_transaction_id','bank_book_transaction_id');
    }
}
