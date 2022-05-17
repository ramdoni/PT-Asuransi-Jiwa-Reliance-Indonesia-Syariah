<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use App\Models\KonvenMemo;
use App\Models\KonvenUnderwriting;
use App\Models\Policy;
use App\Models\BankAccount;
use App\Models\Income;
use App\Models\IncomeEndorsement;
use App\Models\Expenses;
use App\Models\IncomeCancel;
use App\Models\Journal;

class MemoPosSync extends Component
{
    public $total_sync,$is_sync_memo,$total_finish=0,$data='Synchronize, please wait...!',$total_success=0,$total_failed=0;
    protected $listeners = ['is_sync_memo'=>'memo_sync'];
    public function render()
    {
        return view('livewire.konven.memo-pos-sync');
    }
    public function mount()
    {
        $this->total_sync = KonvenMemo::where('status_sync',0)->count();
    }
    public function cancel_sync(){
        $this->is_sync_memo=false;
    }
    public function memo_sync()
    {
        if($this->is_sync_memo==false) return false;
        $this->emit('is_sync_memo');
        $is_teknis = \Auth::uset()->user_access_id ==5?true:false;
        foreach(KonvenMemo::where(['status_sync'=>0,'is_temp'=>0])->get() as $key => $item){
            $this->data = $item->no_kwitansi_finance .'/'. $item->no_kwitansi_finance2."<br />";
            $note_failed = '';
            // find data UW
            $kwitansi = ($item->no_kwitansi_finance ? $item->no_kwitansi_finance : $item->no_kwitansi_finance2);
            $uw = KonvenUnderwriting::where('no_kwitansi_debit_note',$kwitansi)->first();   
            if($uw){
                if($uw->status==1) $note_failed = "Data Konven Underwriting dengan no kwitansi {$kwitansi} belum di sync";  // jika data UW belum di sinkron
                $item->status_sync=1; //sync
                $item->konven_underwriting_id = $uw->id;
            }else
                $note_failed = "Data Konven Underwriting dengan no kwitansi {$kwitansi} tidak ditemukan";
            
            if($kwitansi=="") $note_failed = "No kwitansi {$kwitansi} harus diisi";
            if($note_failed){
                $this->total_failed++;
                $item->status_sync=2;//Invalid
                $item->note_invalid = $note_failed;
                $item->save();
                continue;
            }
            
            $this->total_success++;
            $item->save();
            // cek no polis
            $polis = Policy::where('no_polis',$item->no_polis)->first();
            if(!$polis){
                $polis = new Policy();
                $polis->no_polis = $item->no_polis;
                $polis->pemegang_polis = $item->pemegang_polis;
                $polis->alamat = $item->alamat;
                $polis->cabang = $item->cabang;
                $polis->produk = $item->produk;
                $polis->type = 1; // konven
                $polis->save();
            }

            $this->total_finish++;
            if(!$uw) continue; // Skip jika tidak ditemukan data UW
            $bank = BankAccount::where('no_rekening',replace_idr($item->no_rekening))->first();
            if(!$bank){
                $bank = new BankAccount();
                $bank->bank = $item->bank;
                $bank->no_rekening = replace_idr($item->no_rekening);
                $bank->owner = $item->tujuan_pembayaran;
                $bank->save();
            }
            if($item->jenis_po =='END'){ // Endorsment
                $this->data = '<strong>Endorsment '.$item->ket_perubahan2.'</strong> : '.format_idr($item->refund);
                // cek income Status Unpaid
                $income = Income::where(['transaction_table'=>'konven_underwriting','transaction_id'=>$uw->id,'status'=>1])->first();
                if($income){
                    $endors = new IncomeEndorsement();
                    $endors->income_id = $income->id;
                    $endors->nominal =  abs($item->refund);
                    $endors->transaction_table = 'konven_memo_pos';
                    $endors->transaction_id = $item->id;
                    $endors->type = $item->ket_perubahan2 =="DN" ? 2 : 1;
                    $endors->save();
                }else{
                    if($item->ket_perubahan2 =='DN'){
                        $income = new Income();
                        $income->user_id = \Auth::user()->id;
                        $income->no_voucher = generate_no_voucher_income();
                        $income->reference_no = $item->no_dn_cn;
                        $income->reference_date = $item->tgl_produksi;
                        $income->nominal = abs($item->refund);
                        $income->client = $item->no_polis.' / '.$item->pemegang_polis;
                        $income->reference_type = 'Endorsement '.$item->ket_perubahan2;
                        $income->transaction_id = $item->id;
                        $income->transaction_table = 'konven_memo_pos';
                        $income->description = $item->ket_perubahan1;
                        $income->rekening_bank_id = $bank->id;
                        $income->type = 1;
                        $income->policy_id  = $polis->id;
                        if(!$is_teknis) $income->status = 2; // otomatis paid ketika yang upload adalah administrator
                        $income->save();
                    }else{
                        $expense = new Expenses();
                        $expense->user_id = \Auth::user()->id;
                        $expense->no_voucher = generate_no_voucher_income();
                        $expense->reference_no = $item->no_dn_cn;
                        $expense->reference_date = $item->tgl_produksi;
                        $expense->nominal = abs($item->refund);
                        $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
                        $expense->reference_type = 'Endorsement '.$item->ket_perubahan2;
                        $expense->transaction_id = $item->id;
                        $expense->transaction_table = 'konven_memo_pos';
                        $expense->description = $item->ket_perubahan1;
                        $expense->rekening_bank_id = $bank->id;
                        $expense->type = 1;
                        $expense->policy_id  = $polis->id;
                        if(!$is_teknis) $expense->status = 2; // otomatis paid ketika yang upload adalah administrator
                        $expense->save();
                    }
                }
            }
            if(strtoupper($item->jenis_po) =='RFND' || strtoupper($item->jenis_po)=='REFUND'){ // Refund
                $this->data = '<strong>Refund </strong> : '.format_idr($item->refund);
                $expense = new Expenses();
                $expense->user_id = \Auth::user()->id;
                $expense->no_voucher = generate_no_voucher_income();
                $expense->reference_no = $item->no_dn_cn;
                $expense->reference_date = $item->tgl_produksi;
                $expense->nominal = abs($item->refund);
                $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
                $expense->reference_type = 'Refund';
                $expense->transaction_id = $item->id;
                $expense->transaction_table = 'konven_memo_pos';
                $expense->description = $item->ket_perubahan1;
                $expense->rekening_bank_id = $bank->id;
                $expense->type = 1;
                $expense->policy_id  = $polis->id;
                if(!$is_teknis) $expense->status = 2; // otomatis paid ketika yang upload adalah administrator
                $expense->save();

                if($uw){
                    $coa_id = 268; // Refund Premium Other Tradisional
                    $coa_id_payable = 167; // Refund Premium Payable Other Tradisional
                    switch($uw->line_bussines){
                        case 'DWIGUNA':
                            $coa_id = 265; // Refund Premium Dwiguna
                            $coa_id_payable = 164; // Refund Premium Payable Dwiguna
                            break;
                        case 'JANGKAWARSA':
                            $coa_id = 263; // Refund Premium Jangkawarsa
                            $coa_id_payable = 162; // Refund Premium Payable Jangkawarsa
                            break;
                        case 'EKAWARSA':
                            $coa_id = 264; // Refund Premium Ekawarsa
                            $coa_id_payable = 163; // Refund Premium Payable Ekawarsa
                            break;
                    }

                    /**
                     * jika yang upload teknis maka langsung tercreate journal otomatis
                     */
                    if($is_teknis){
                        // Refund Premium
                        $new  = new Journal();
                        $new->transaction_number = $item->no_dn_cn;
                        $new->transaction_id = $item->id;
                        $new->transaction_table = 'konven_memo_pos'; 
                        $new->coa_id = $coa_id;
                        $new->no_voucher = generate_no_voucher($coa_id,$expense->id);
                        $new->date_journal = date('Y-m-d');
                        $new->debit = $item->refund;
                        $new->description = "Refund {$item->pemegang_polis}";
                        $new->save();

                        // Refund Premium Payable
                        $new  = new Journal();
                        $new->transaction_number = $item->no_dn_cn;
                        $new->transaction_id = $item->id;
                        $new->transaction_table = 'konven_memo_pos'; 
                        $new->coa_id = $coa_id_payable;
                        $new->no_voucher = generate_no_voucher($coa_id,$expense->id);
                        $new->date_journal = date('Y-m-d');
                        $new->kredit = $item->refund;
                        $new->description = "Refund {$item->pemegang_polis}";
                        $new->save();
                    }
                }
            }
            if($item->jenis_po =='CNCL'){ // Cancel
                $this->data = '<strong>Cancelation </strong> : '.format_idr($item->refund);
                // Find Income Premium Receivable
                if($uw){
                    $in = Income::where('transaction_table','konven_underwriting')->where('transaction_id',$uw->id)->first();
                    
                    if($in and $in->status==1){  
                        //jika statusnya belum paid maka embed cancelation ke form income premium receivable 
                        //dan mengurangi nominal dari premi yang diterima
                        $cancel = new IncomeCancel();
                        $cancel->income_id = $in->id;
                        $cancel->nominal = $item->refund;
                        $cancel->transaction_id = $item->id;
                        $cancel->transaction_table= "konven_memo_pos";
                        $cancel->save();
                    }else{
                        $expense = new Expenses();
                        $expense->user_id = \Auth::user()->id;
                        $expense->no_voucher = generate_no_voucher_income();
                        $expense->reference_no = $item->no_dn_cn;
                        $expense->reference_date = $item->tgl_produksi;
                        $expense->nominal = abs($item->refund);
                        $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
                        $expense->reference_type = 'Cancelation';
                        $expense->transaction_id = $item->id;
                        $expense->transaction_table = 'konven_memo_pos';
                        $expense->description = $item->ket_perubahan1;
                        $expense->rekening_bank_id = $bank->id;
                        $expense->type = 1;
                        $expense->policy_id  = $polis->id;
                        if(!$is_teknis) $expense->status = 2; // otomatis paid ketika yang upload adalah administrator
                        $expense->save();
                    }
                }
            }
            $this->data .=$item->no_dn_cn.'<br />'.$item->no_polis.' / '.$item->pemegang_polis;
        }
        if(KonvenMemo::where('status_sync',0)->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong>');   
            return redirect()->route('konven.underwriting');
        }
    }
}
