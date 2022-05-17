<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use App\Models\KonvenUnderwriting;
use App\Models\KonvenUnderwritingCoa;
use App\Models\Income;
use App\Models\Expenses;
use App\Models\Journal;
use App\Models\Policy;
use App\Models\DistributionChannel;

class UnderwritingSync extends Component
{
    public $total_sync,$is_sync,$total_finish=0,$data='Synchronize, please wait...!',$total_success=0,$total_failed=0;
    protected $listeners = ['is_sync'=>'uw_sync'];
    public function render()
    {
        return view('livewire.konven.underwriting-sync');
    }
    public function mount()
    {
        $this->total_sync = KonvenUnderwriting::where('status',1)->count();
    }
    public function cancel_sync(){
        $this->is_sync=false;
    }
    public function uw_sync()
    {
        if($this->is_sync==false) return false;

        $is_teknis = \Auth::user()->user_access_id ==5?true:false;

        $this->emit('is_sync');
        foreach(KonvenUnderwriting::where('status',1)->get() as $key => $item){
            $note_invalid = '';
            if($item->no_polis=="") $note_invalid = "No Polis harus diisi";
            if($item->line_bussines=="") $note_invalid = "Line Bussiness harus diisi";
            if($item->premi_netto=="") $note_invalid = "Premi Netto harus diisi";
            if($item->no_kwitansi_debit_note=="") $note_invalid = "No Kwitansi / Debit Note harus diisi";
            
            if($note_invalid){
                $item->note_invalid = $note_invalid;
                $item->status = 3;
                $item->save();
                $this->total_failed++;
                continue;
            }

            $item->status=2;
            $item->save();  

            // find distribution channel
            $channel = DistributionChannel::where(['name'=>$item->channel_name,'type'=>$item->channel_type])->first();
            if(!$channel){
                $channel = new DistributionChannel();
                $channel->name = $item->channel_name;
                $channel->type = $item->channel_type;
                $channel->save();
            }

            $policy = Policy::where('no_polis',$item->no_polis)->first(); 
            if(!$policy){
                $policy = new Policy();
                $policy->no_polis = $item->no_polis;
                $policy->pemegang_polis = $item->pemegang_polis;
                $policy->alamat = $item->alamat;
                $policy->cabang = $item->cabang;
                $policy->produk = $item->produk;
                $policy->save();
            }
            
            //$this->data = $item->no_kwitansi_debit_note.'<br />'.$item->no_polis.' / '.$item->pemegang_polis;
            if($item->line_bussines=='DWIGUNA'){
                $coa_premi_netto = 60;
                $commision_paid = 91;
                $discount_coa = 60; 
                $gross_premium = 75;
            }elseif($item->line_bussines=='JANGKAWARSA'){
                $coa_premi_netto = 58;
                $commision_paid = 89;
                $discount_coa = 65;
                $gross_premium = 73;
            }elseif($item->line_bussines=='EKAWARSA'){
                $coa_premi_netto = 59;
                $commision_paid = 90;
                $discount_coa = 66;
                $gross_premium = 74;
            }elseif($item->line_bussines=='KECELAKAAN'){
                $coa_premi_netto = 62;
                $commision_paid = 93;
                $discount_coa = 69;
                $gross_premium = 77;
            }else{
                $coa_premi_netto = 63; //Premium Receivable Other Tradisional
                $commision_paid = 94; //Commision Paid Other Tradisional
                $discount_coa = 70;
                $gross_premium = 78;
            }
            $ordering = 1;
            // Insert Transaksi
            if(!empty($item->premi_netto)){
                $new = new KonvenUnderwritingCoa();
                $new->coa_id = $coa_premi_netto;
                $new->konven_underwriting_id = $item->id;
                $new->debit = $item->premi_netto;
                $new->kredit = 0;
                $new->ordering = $ordering;
                $new->description = $item->pemegang_polis;
                $new->save();
                $ordering++;
                // insert income premium receivable
                $income = new Income();
                $income->user_id = \Auth::user()->id;
                $income->no_voucher = generate_no_voucher_income();
                $income->reference_no = $item->no_kwitansi_debit_note;
                $income->reference_date = date('Y-m-d',strtotime($item->tanggal_produksi));
                $income->nominal = $item->premi_netto;
                $income->client = $item->pemegang_polis;
                $income->reference_type = 'Premium Receivable';
                $income->transaction_table = 'konven_underwriting';
                $income->transaction_id = $item->id;
                $income->due_date = $item->tgl_jatuh_tempo;
                $income->type = 1;
                $income->policy_id = $policy->id;
                if(!$is_teknis) 
                    $income->status = 2; // otomatis paid ketika Administrator yang melakukan upload
                else
                    $income->status = 1; 
                $income->save();
            }
            if(!empty($item->ppn) and !empty($item->jumlah_discount)){
                // Expense -  Commision Payable
                $expense = new Expenses();
                $expense->user_id = \Auth::user()->id;
                $expense->no_voucher = generate_no_voucher_expense();
                $expense->reference_no = $item->no_kwitansi_debit_note;
                $expense->reference_date = $item->tanggal_produksi;
                $expense->nominal = $item->jumlah_pph + $item->jumlah_ppn;
                $expense->recipient = $item->no_polis .' / '. $item->pemegang_polis;
                $expense->reference_type = 'Handling Fee';
                $expense->transaction_id = $item->id;
                $expense->transaction_table = 'konven_underwriting';
                $expense->type = 1;
                $expense->policy_id = $policy->id;
                
                if(!$is_teknis) 
                    $expense->status = 2; // otomatis paid ketika Administrator yang melakukan upload
                else
                    $expense->status = 1;

                $expense->save();
                $ordering++;
            }elseif(!empty($item->jumlah_discount)){
                $new = new KonvenUnderwritingCoa();
                $new->coa_id = $discount_coa; // Discount Jangkawarsa
                $new->konven_underwriting_id = $item->id;
                $new->debit = $item->jumlah_discount;
                $new->kredit = 0;
                $new->ordering = $ordering;
                $new->description = $item->pemegang_polis;
                $new->save();
                $ordering++;
            }
            if(!empty($item->premi_gross) or !empty($item->extra_premi)){
                $new = new KonvenUnderwritingCoa();
                $new->coa_id = $gross_premium; // 	Gross Premium Jangkawarsa
                $new->konven_underwriting_id = $item->id;
                $new->debit = 0;
                $new->kredit = $item->premi_gross + $item->extra_premi;
                $new->ordering = $ordering;
                $new->description = $item->pemegang_polis;
                $new->save();
                $ordering++;
            }
            if(!empty($item->jumlah_pph)){
                $new = new KonvenUnderwritingCoa();
                $new->coa_id = 83; // PPH 23 Payable
                $new->konven_underwriting_id = $item->id;
                $new->debit = 0;
                $new->kredit = $item->jumlah_pph;
                $new->ordering = $ordering;
                $new->description = $item->pemegang_polis;
                $new->save();
                $ordering++;
            }
            if(!empty($item->extsertifikat)){
                $new = new KonvenUnderwritingCoa();
                $new->coa_id = 88; // Policy Administration Income
                $new->konven_underwriting_id = $item->id;
                $new->debit = 0;
                $new->kredit = $item->extsertifikat;
                $new->ordering = $ordering;
                $new->description = $item->pemegang_polis;
                $new->save();
                $ordering++;
            }

            /**
             * jika teknis yang upload
             * maka otomatis ter journal 
             * */             
            if($is_teknis){
                foreach($item->coaDesc as $k => $coa){
                    $new  = new Journal();
                    $new->transaction_number = $item->no_kwitansi_debit_note;
                    $new->transaction_id = $item->id;
                    $new->transaction_table = 'konven_underwriting'; 
                    $new->coa_id = $coa->coa_id;
                    $new->no_voucher = generate_no_voucher($coa->coa_id,$item->id);
                    $new->date_journal = $item->tanggal_produksi;
                    $new->debit = $coa->debit;
                    $new->kredit = $coa->kredit;
                    $new->description = $coa->description;
                    $new->saldo = replace_idr($coa->debit!=0 ? $coa->debit : ($coa->kredit!=0?$coa->kredit : 0));
                    $new->save();
                }
            }
            
            $this->total_success++;
            $this->total_finish++;
        }
        if(KonvenUnderwriting::where('status',1)->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong> !');   
            return redirect()->route('konven.underwriting');
        }
    }
}
