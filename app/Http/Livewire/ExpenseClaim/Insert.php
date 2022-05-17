<?php

namespace App\Http\Livewire\ExpenseClaim;

use Livewire\Component;
use App\Models\Journal;
use App\Models\Expenses;
use App\Models\Policy;
use App\Models\Income;
use App\Models\ExpensePeserta;

class Insert extends Component
{
    public $data,$no_voucher,$no_polis,$nilai_klaim,$premium_receivable,$is_submit=false;
    public $reference_no,$to_bank_account_id,$from_bank_account_id,$payment_date,$bank_charges,$description,$type=1;
    public $add_pesertas=[],$no_peserta=[],$nama_peserta=[];
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    public function render()
    {
        return view('livewire.expense-claim.insert');
    }
    
    public function mount()
    {
        $this->no_voucher = generate_no_voucher_expense();
        $this->add_pesertas[] = 0;
        $this->no_peserta[] = "";
        $this->nama_peserta[] = "";
    }

    public function emitAddBank($id)
    {
        $this->to_bank_account_id = $id;
        $this->emit('init-form');
    }

    public function updated($propertyName)
    {
        if($propertyName=='no_polis'){
            $this->data = Policy::find($this->no_polis);
            $premium = Income::select('income.*')->where(['income.reference_type'=>'Premium Receivable','income.transaction_table'=>'konven_underwriting'])
                                            ->join('konven_underwriting','konven_underwriting.id','=','income.transaction_id')
                                            ->where('konven_underwriting.no_polis',$this->data->no_polis);
            $total_premium_receive = clone $premium;
            if($total_premium_receive->where('income.status',2)->sum('income.payment_amount') > 0) $this->is_submit = true;
            else $this->is_submit = false;

            $this->premium_receivable = $premium->get();
        }
        $this->emit('init-form');
    }
    public function delete_peserta($key)
    {
        unset($this->add_pesertas[$key],$this->no_peserta[$key],$this->nama_peserta[$key]);
    }
    public function add_peserta()
    {
        $this->add_pesertas[] = count($this->add_pesertas);
        $this->no_peserta[] = '';
        $this->nama_peserta[] = '';
    }
    public function save($type)
    {
        $this->bank_charges = replace_idr($this->bank_charges);
        $this->nilai_klaim = replace_idr($this->nilai_klaim);
        $this->validate(
            [
                'no_polis' => 'required',
                'nilai_klaim' => 'required',
                'payment_date' => 'required',
                'from_bank_account_id' => 'required'
            ]);
        $data = new Expenses();
        $data->policy_id = $this->data->id;
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->rekening_bank_id = $this->to_bank_account_id;
        $data->reference_type = 'Claim';
        $data->reference_no = $this->reference_no;
        $data->recipient = $this->data->no_polis.' - '. $this->data->pemegang_polis;
        $data->no_voucher = $this->no_voucher;
        $data->payment_amount = $this->nilai_klaim;
        $data->payment_date = $this->payment_date;
        $data->bank_charges = $this->bank_charges;
        $data->status = $type=='Draft' ? 4 : 2;
        $data->user_id = \Auth::user()->id;
        $data->description = $this->description;
        $data->type = $this->type;
        $data->save();

        if($this->add_pesertas){
            foreach($this->add_pesertas as $k=>$v){
                if(!empty($this->no_peserta[$k]) and !empty($this->nama_peserta[$k])){
                    $peserta = new ExpensePeserta();
                    $peserta->expense_id = $data->id;
                    $peserta->no_peserta = $this->no_peserta[$k];
                    $peserta->nama_peserta = $this->nama_peserta[$k];
                    $peserta->type = 1; // Claim Payable
                    $peserta->policy_id = $this->data->id;
                    $peserta->save();
                }
            }
        }

        if($type=='Submit'){
            // insert coa
            $coa_bank_charges = 347;
            // Bank
            $coa_bank_account = BankAccount::find($this->from_bank_account_id);
            $no_voucher = generate_no_voucher($coa_bank_account->coa_id,$this->data->id);
            if($coa_bank_account->coa_id){
                $journal = new Journal();
                $journal->coa_id = $coa_bank_account->coa_id;
                $journal->no_voucher = $no_voucher;
                $journal->date_journal = date('Y-m-d');
                $journal->kredit = $this->bank_charges + $this->payment_amount;
                $journal->debit = 0;
                $journal->saldo = $this->bank_charges + $this->payment_amount;
                $journal->description = $this->description ? $this->description : 'Pembayaran Klaim '.$reas->broker_re.' ('.$reas->keterangan.')';
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($reas->uw->no_kwitansi_debit_note)?$reas->uw->no_kwitansi_debit_note:'';
                $journal->save();
            } 
            // Bank Charges
            if(!empty($this->bank_charges)){
                $journal = new Journal();
                $journal->coa_id = $coa_bank_charges;
                $journal->no_voucher = $no_voucher;
                $journal->date_journal = date('Y-m-d');
                $journal->debit = replace_idr($this->bank_charges);
                $journal->kredit = 0;
                $journal->saldo = replace_idr($this->bank_charges);
                $journal->description = $this->description ? $this->description : 'Pembayaran Klaim '.$reas->broker_re.' ('.$reas->keterangan.')';
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($reas->uw->no_kwitansi_debit_note)?$reas->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
            // Reinsurance Premium Payable
            $journal = new Journal();
            $journal->coa_id = $coa_reinsurance_premium_payable;
            $journal->no_voucher = $no_voucher;
            $journal->date_journal = date('Y-m-d');
            $journal->debit = $this->payment_amount;
            $journal->kredit = 0;
            $journal->saldo = $this->payment_amount;
            $journal->description = $this->description ? $this->description : 'Pembayaran Premi Reas '.$reas->broker_re.' ('.$reas->keterangan.')';
            $journal->transaction_id = $this->data->id;
            $journal->transaction_table = 'expenses';
            $journal->transaction_number = isset($reas->uw->no_kwitansi_debit_note)?$reas->uw->no_kwitansi_debit_note:'';
            $journal->save();

            // set balance
            $bank_balance = BankAccount::find($data->from_bank_account_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance - $this->nilai_klaim;
                $bank_balance->save();

                $balance = new BankAccountBalance();
                $balance->debit = $this->nilai_klaim;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 6; // Claim Payable
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }

            // $coa_claim_payable = 0;
            // switch($this->data->uw->line_bussines){
            //     case "JANGKAWARSA":
            //         $coa_claim_payable = 155; //Claim Payable Jangkawarsa
            //     break;
            //     case "EKAWARSA":
            //         $coa_claim_payable = 156; //Claim Payable Ekawarsa
            //     break;
            //     case "DWIGUNA":
            //         $coa_claim_payable = 157; //Claim Payable  Dwiguna
            //     break;
            //     case "DWIGUNA KOMBINASI":
            //         $coa_claim_payable = 158; //Claim Payable Dwiguna Kombinasi
            //     break;
            //     case "KECELAKAAN":
            //         $coa_claim_payable = 159; //Claim Payable Kecelakaan Diri
            //     break;
            //     case "TRADISIONAL":
            //         $coa_claim_payable = 154; //Claim Payable Kecelakaan Diri
            //     break;
            //     default: 
            //         $coa_claim_payable = 160; //CLaim Payable Other Tradisional
            //     break;
            // }        
            // $no_voucher = generate_no_voucher($coa_claim_payable,$data->id);
            // // Premium Receivable
            // $journal = new Journal();
            // $journal->coa_id = $coa_claim_payable;
            // $journal->no_voucher = $no_voucher;
            // $journal->date_journal = date('Y-m-d');
            // $journal->kredit = $this->payment_amount;
            // $journal->debit = 0;
            // $journal->saldo = $this->payment_amount;
            // $journal->description = $this->description;
            // $journal->transaction_id = $this->data->id;
            // $journal->transaction_table = 'expenses';
            // $journal->transaction_number = isset($data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
            // $journal->save();

            // $journal = new Journal();
            // $journal->coa_id = 206;//Other Payable
            // $journal->no_voucher = $no_voucher;
            // $journal->date_journal = date('Y-m-d');
            // $journal->kredit = $this->payment_amount - $this->data->nominal;
            // $journal->debit = 0;
            // $journal->saldo = $this->payment_amount - $this->data->nominal;
            // $journal->description = $this->description;
            // $journal->transaction_id = $this->data->id;
            // $journal->transaction_table = 'expenses';
            // $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
            // $journal->save();
            
            // // Bank Charges
            // if(!empty($this->bank_charges)){
            //     $journal = new Journal();
            //     $journal->coa_id = 347; // Bank Charges
            //     $journal->no_voucher = $no_voucher;
            //     $journal->date_journal = date('Y-m-d');
            //     $journal->kredit = replace_idr($this->bank_charges);
            //     $journal->debit = 0;
            //     $journal->saldo = replace_idr($this->bank_charges);
            //     $journal->description = $this->description;
            //     $journal->transaction_id = $this->data->id;
            //     $journal->transaction_table = 'expenses';
            //     $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
            //     $journal->save();
            // }
        }

        session()->flash('message-success',__('Claim data has been successfully saved'));
        \LogActivity::add("Expense Claim {$type} {$data->id}");
        return redirect()->route('expense.claim');
    }
}
