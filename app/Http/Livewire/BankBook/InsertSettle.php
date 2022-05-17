<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;
use App\Models\BankBookSettle;
use App\Models\BankBookTransaction;
use App\Models\BankBookTransactionItem;
use App\Models\Income;
use App\Models\ErrorSuspense;
use App\Models\BankAccount;
use App\Models\Journal;

class InsertSettle extends Component
{
    public $types=[],$transaction_ids=[],$bank_book_id=[],$kwitansi = [],$error_settle;
    public $payment_ids=[],$amounts=[],$total_payment=0,$total_voucher=0,$message_error = '',$payment_rows=[];
    protected $listeners = ['set_bank_book'];
    public function render()
    {
        return view('livewire.bank-book.insert-settle');
    }

    public function updated($propertyName)
    {
        $this->reset(['error_settle','total_payment']);

        foreach($this->types as $k =>$type){
            if($type=="Premium Receivable" || $type=="Reinsurance Commision" || $type=="Recovery Claim" || $type=="Recovery Refund"){
                $premi = Income::find($this->transaction_ids[$k]);
                if($premi){
                    $this->payment_rows[$k] = $premi;
                    $amount = $premi->outstanding_balance ? $premi->outstanding_balance : $premi->nominal;
                    if($this->amounts[$k]==0 and $type=="Premium Receivable")
                        $this->amounts[$k] = $amount;
                    else
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

        $no_voucher = generate_no_voucer_journal("AR");

        foreach($this->types as $k => $item){
            $transaction_item = new BankBookTransactionItem();
            $transaction_item->bank_book_transaction_id = $transaction->id;
            $transaction_item->amount = $this->amounts[$k];
            $transaction_item->type = $item;
            $transaction_item->transaction_id = $this->transaction_ids[$k];
            $transaction_item->description = $this->transaction_ids[$k];

            if($item=='Premium Receivable' || $item=='Reinsurance Commision' || $item=='Recovery Claim' || $item=='Recovery Refund'){
               $income = Income::find($this->transaction_ids[$k]);
               if($income){
                    $transaction_item->dn = $income->reference_no;
                    $transaction_item->description = $income->description;
            
                    $income_nominal = $income->outstanding_balance ? $income->outstanding_balance : $income->nominal;
                    $income->outstanding_balance = $income_nominal - $this->amounts[$k];
                    $income->payment_amount = $income->payment_amount ? ($this->amounts[$k] + $income->payment_amount) : $this->amounts[$k];
                    $income->status = ($income->outstanding_balance != 0 ? 3 : 2);
                    $income->bank_book_transaction_id = $transaction->id;
                    $income->settle_date = date('Y-m-d');
                    $income->save();

                    $line_bussines = $line_bussines = isset($income->uw->line_bussines) ? $income->uw->line_bussines : '';
                    $coa_id = 0;
                    if($item=='Premium Receivable'){
                        $coa_id = 63;
                        switch($line_bussines){
                            case "JANGKAWARSA":
                                $coa_id = 58; //Premium Receivable Jangkawarsa
                            break;
                            case "EKAWARSA":
                                $coa_id = 59; //Premium Receivable Ekawarsa
                            break;
                            case "DWIGUNA":
                                $coa_id = 60; //Premium Receivable Dwiguna
                            break;
                            case "DWIGUNA KOMBINASI":
                                $coa_id = 61; //Premium Receivable Dwiguna Kombinasi
                            break;
                            case "KECELAKAAN":
                                $coa_id = 62; //Premium Receivable Kecelakaan Diri
                            break;
                            default: 
                                $coa_id = 63; //Premium Receivable Other Tradisional
                            break;
                        }
                    }

                    if($item=='Reinsurance Commision'){
                        $coa_id = 0;
                        switch($line_bussines){
                            case "JANGKAWARSA":
                                $coa_id = 244; //Reinsurance Commission Fee Jangkawarsa
                            break;
                            case "EKAWARSA":
                                $coa_id = 245; //Reinsurance Commission Fee Ekawarsa
                            break;
                            case "DWIGUNA":
                                $coa_id = 246; //Reinsurance Commission Fee Dwiguna
                            break;
                            case "DWIGUNA KOMBINASI":
                                $coa_id = 247; //Reinsurance Commission Fee Dwiguna Kombinasi
                            break;
                            case "KECELAKAAN":
                                $coa_id = 248; //Reinsurance Commission Fee Kecelakaan Diri
                            break;
                            default: 
                                $coa_id = 249; //Reinsurance Commission Fee Other Tradisional
                            break;
                        }        
                    }

                    if($item=='Recovery Claim'){
                        $coa_id = 0;
                        switch($line_bussines){
                            case "JANGKAWARSA":
                                $coa_id = 250; //Reinsurance Commission Fee Jangkawarsa
                            break;
                            case "EKAWARSA":
                                $coa_id = 251; //Reinsurance Commission Fee Ekawarsa
                            break;
                            case "DWIGUNA":
                                $coa_id = 252; //Reinsurance Commission Fee Dwiguna
                            break;
                            case "DWIGUNA KOMBINASI":
                                $coa_id = 253; //Reinsurance Commission Fee Dwiguna Kombinasi
                            break;
                            case "KECELAKAAN":
                                $coa_id = 254; //Reinsurance Commission Fee Kecelakaan Diri
                            break;
                            default: 
                                $coa_id = 255; //Reinsurance Commission Fee Other Tradisional
                            break;
                        }        
                    }

                    if($item=='Recovery Refund'){
                        $coa_id = 0;
                        switch($line_bussines){
                            case "JANGKAWARSA":
                                $coa_id = 263; //Reinsurance Commission Fee Jangkawarsa
                            break;
                            case "EKAWARSA":
                                $coa_id = 264; //Reinsurance Commission Fee Ekawarsa
                            break;
                            case "DWIGUNA":
                                $coa_id = 265; //Reinsurance Commission Fee Dwiguna
                            break;
                            case "DWIGUNA KOMBINASI":
                                $coa_id = 266; //Reinsurance Commission Fee Dwiguna Kombinasi
                            break;
                            case "KECELAKAAN":
                                $coa_id = 267; //Reinsurance Commission Fee Kecelakaan Diri
                            break;
                            default: 
                                $coa_id = 268; //Reinsurance Commission Fee Other Tradisional
                            break;
                        }        
                    }

                    $journal = new Journal();
                    $journal->kredit = $this->amounts[$k];
                    $journal->debit = 0;
                    $journal->no_voucher = $no_voucher;
                    $journal->coa_id = $coa_id;
                    $journal->date_journal = $bank_book->payment_date;
                    $journal->description = $income->description;
                    $journal->transaction_id = $income->id;
                    $journal->transaction_table = 'income';
                    $journal->transaction_number = $income->reference_no;
                    $journal->save();
               }
            }

            if($item=='Error Suspense Account'){
                $error = new ErrorSuspense();
                $error->bank_book_transaction_id = $transaction->id;
                $error->amount = $this->amounts[$k];
                $error->note = $this->transaction_ids[$k];
                $error->save();

                # insert journal
                $journal = new Journal();
                $journal->coa_id = 349; // Error Suspen Account;
                $journal->no_voucher = $no_voucher;
                $journal->date_journal = date('Y-m-d');
                $journal->kredit = $this->amounts[$k];
                $journal->debit = 0;
                $journal->saldo = 0;
                $journal->description = $this->transaction_ids[$k];
                $journal->transaction_id = $error->id;
                $journal->transaction_table = 'error_suspense';
                $journal->save();
            }

            if($item=='Premium Deposit'){
                foreach($this->bank_book_id as $bank_book_id){
                    $data = new Income();
                    $data->reference_type = 'Titipan Premi';
                    $data->nominal = $this->amounts[$k];
                    $data->outstanding_balance = $this->amounts[$k];
                    $data->description = $this->transaction_ids[$k];
                    $data->user_id = \Auth::user()->id;
                    $data->bank_book_transaction_id = $transaction->id;
                    $data->bank_book_id = $bank_book_id->id;
                    $data->save();
                    
                    $transaction_item->transaction_id = $data->id;
                    
                    # insert journal
                    $journal = new Journal();
                    $journal->coa_id = get_coa(406000); // premium suspend;
                    $journal->no_voucher = $no_voucher;
                    $journal->date_journal = date('Y-m-d');
                    $journal->kredit = $this->amounts[$k];
                    $journal->debit = 0;
                    $journal->saldo = 0;
                    $journal->description = $this->transaction_ids[$k];
                    $journal->transaction_id = $data->id;
                    $journal->transaction_table = 'income';
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
            $journal->debit = $bank_book->amount;
            $journal->kredit = 0;
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
