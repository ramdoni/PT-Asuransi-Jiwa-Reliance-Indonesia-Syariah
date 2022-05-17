<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;
use App\Models\BankBookSettle;
use App\Models\BankBookTransaction;
use App\Models\BankBookTransactionItem;
use App\Models\Expenses;
use App\Models\ErrorSuspense;
use App\Models\BankAccount;
use App\Models\Journal;

class PayableInsert extends Component
{
    public $types=[],$transaction_ids=[],$bank_book_id=[],$kwitansi = [],$error_settle;
    public $payment_ids=[],$amounts=[],$total_payment=0,$total_voucher=0,$message_error = '',$payment_rows=[];
    protected $listeners = ['set_bank_book'];
    public function render()
    {
        return view('livewire.bank-book.payable-insert');
    }

    public function updated($propertyName)
    {
        $property = explode('.',$propertyName);
        if(isset($property[0]) and $property[0]=='types'){
            $this->payment_ids[$property[1]]=null;
            $this->transaction_ids[$property[1]]=null;
            $this->amounts[$property[1]]=0;
            $this->payment_rows[$property[1]]=null;
        }
        $this->reset(['error_settle','total_payment']);
        
        foreach($this->types as $k =>$type){
            if($type!="Error Suspense Account"){
                $premi = Expenses::find($this->transaction_ids[$k]);
                if($premi){
                    $this->payment_rows[$k] = $premi;
                    $amount = $premi->outstanding_balance ? $premi->outstanding_balance : $premi->payment_amount;
                    if($premi->payment_amount=="") $amount = $premi->nominal;
                    $this->amounts[$k] = $amount;

                    if($this->amounts[$k] > $amount) $this->error_settle = $premi->reference_no ." Nominal has exceeded the limit!";
                }
            }
        
            $this->total_payment += $this->amounts[$k]?$this->amounts[$k]:0;
        }
        if($this->total_payment > $this->total_voucher)  $this->error_settle = "Nominal has exceeded the limit!";
        $this->emit('select-type',$this->transaction_ids);
    }

    public function  set_bank_book($id)
    {
        $this->bank_book_id = BankBook::whereIn('id',$id)->get();
        $this->total_voucher = BankBook::whereIn('id',$id)->sum('amount');
    }

    public function add_payment()
    {
        if($this->total_payment > $this->total_voucher){
            $this->error_settle = "Nominal has exceeded the limit!";
        }else{
            $this->payment_ids[] = null;
            $this->types[] = null;
            $this->transaction_ids[] = null;
            $this->amounts[] = 0;$this->payment_rows[] = null;
        }

        $this->emit('select-type');
    }

    public function delete_payment($k)
    {
        unset($this->payment_ids[$k],$this->types[$k],$this->transaction_ids[$k],$this->amounts[$k],$this->payment_rows[$k]);
        $this->updated('amounts');
        $this->emit('select-type');
    }

    public function onhold()
    {
        
    }

    public function save()
    {
        if($this->total_payment != $this->total_voucher){ 
            $this->message_error = '';
            return false;
        }

        $transaction  = new BankBookTransaction();
        $transaction->amount  = $this->total_voucher;
        $transaction->save();

        foreach($this->bank_book_id as $bank_book){
            $bank_book->date_pairing = date('Y-m-d');
            $bank_book->bank_book_transaction_id = $transaction->id;
            $bank_book->status = 1;
            $bank_book->save();
        }

        $no_voucher = generate_no_voucer_journal("AP");

        foreach($this->types as $k => $item){
            $transaction_item = new BankBookTransactionItem();
            $transaction_item->bank_book_transaction_id = $transaction->id;
            $transaction_item->amount = $this->amounts[$k];
            $transaction_item->type = $item;
            $transaction_item->transaction_id = $this->transaction_ids[$k];

            if($item=='Error Suspense Account'){
                $error = new ErrorSuspense();
                $error->bank_book_transaction_id = $transaction->id;
                $error->amount = $this->amounts[$k];
                $error->note = $this->transaction_ids[$k];
                $error->save();

                $transaction_item->description = $this->transaction_ids[$k];
                
                # insert journal
                $journal = new Journal();
                $journal->coa_id = 349; // Error Suspen Account;
                $journal->no_voucher = $no_voucher;
                $journal->date_journal = date('Y-m-d');
                $journal->debit = $this->amounts[$k];
                $journal->kredit = 0;
                $journal->description = $this->transaction_ids[$k];
                $journal->transaction_id = $error->id;
                $journal->transaction_table = 'error_suspense';
                $journal->save();
            }else{
                $expense = Expenses::find($this->transaction_ids[$k]);
                if($expense){
                     $transaction_item->dn = $expense->reference_no;
                     $transaction_item->description = $expense->description;
                     
                     $expense->status = 2;
                     $expense->bank_book_transaction_id = $transaction->id;
                     $expense->settle_date = date('Y-m-d');
                     $expense->save();
 
                     $line_bussines = $line_bussines = isset($expense->uw->line_bussines) ? $expense->uw->line_bussines : '';
                     $coa_id = 0;
                     if($item=='Claim Payable'){
                         $coa_id = 0;
                         switch($line_bussines){
                             case "JANGKAWARSA":
                                 $coa_id = 155;
                             break;
                             case "EKAWARSA":
                                 $coa_id = 156;
                             break;
                             case "DWIGUNA":
                                 $coa_id = 157;
                             break;
                             case "DWIGUNA KOMBINASI":
                                 $coa_id = 158;
                             break;
                             case "KECELAKAAN":
                                 $coa_id = 159;
                             break;
                             case "TRADISIONAL":
                                 $coa_id = 154;
                             break;
                             default: 
                                 $coa_id = 160; 
                             break;
                         }
                     }
 
                     if($item=='Reinsurance'){
                         $coa_id = 0;
                         switch($line_bussines){
                             case "JANGKAWARSA":
                                 $coa_id = 168;
                             break;
                             case "EKAWARSA":
                                 $coa_id = 169;
                             break;
                             case "DWIGUNA":
                                 $coa_id = 170;
                             break;
                             case "DWIGUNA KOMBINASI":
                                 $coa_id = 171;
                             break;
                             case "KECELAKAAN":
                                 $coa_id = 172;
                             break;
                             default: 
                                 $coa_id = 173;
                             break;
                         }
                     }
 
                     if($item=='Commision'){
                         $coa_id = 0;
                         switch($line_bussines){
                             case "JANGKAWARSA":
                                 $coa_id = 175;
                             break;
                             case "EKAWARSA":
                                 $coa_id = 176;
                             break;
                             case "DWIGUNA":
                                 $coa_id = 177;
                             break;
                             case "DWIGUNA KOMBINASI":
                                 $coa_id = 178;
                             break;
                             case "KECELAKAAN":
                                 $coa_id = 179;
                             break;
                             default: 
                                 $coa_id = 180;
                             break;
                         }
                     }

                    if($item=='Cancelation' || $item=='Refund'){
                        $coa_id = 0;
                        switch($line_bussines){
                            case "JANGKAWARSA":
                                $coa_id = 263;
                            break;
                            case "EKAWARSA":
                                $coa_id = 264;
                            break;
                            case "DWIGUNA":
                                $coa_id = 265;
                            break;
                            case "DWIGUNA KOMBINASI":
                                $coa_id = 266;
                            break;
                            case "KECELAKAAN":
                                $coa_id = 267;
                            break;
                            default: 
                                $coa_id = 268;
                            break;
                        }
                    }
                     
                     if($item=='Others') $coa_id = 206;
 
                     $journal = new Journal();
                     $journal->debit = $this->amounts[$k];
                     $journal->kredit = 0;
                     $journal->no_voucher = $no_voucher;
                     $journal->coa_id = $coa_id;
                     $journal->date_journal = $bank_book->payment_date;
                     $journal->description = $expense->description;
                     $journal->transaction_id = $expense->id;
                     $journal->transaction_table = 'expenses';
                     $journal->transaction_number = $expense->reference_no;
                     $journal->save();
                }
            }

            $transaction_item->save();
        }

        foreach($this->bank_book_id as $bank_book){
            $journal = new Journal();
            $journal->coa_id = (isset($bank_book->from_bank->coa_id) ? $bank_book->from_bank->coa_id :24); // Cash in bank
            $journal->no_voucher = $no_voucher;
            $journal->date_journal = date('Y-m-d');
            $journal->kredit = $bank_book->amount;
            $journal->debit = 0;
            $journal->description = $bank_book->note;
            $journal->transaction_id = $bank_book->id;
            $journal->transaction_table = 'bank_book';
            $journal->save();
        }

        $this->emit('modal','hide');
        session()->flash('message-success',__('Settle successfully'));

        return redirect()->route('bank-book.teknik');
    }
}
