<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;
use App\Models\Finance\Income;
use App\Models\Finance\Polis;
use App\Models\Finance\Journal;
use App\Models\Finance\SyariahUnderwriting;

class SinkronDnToJournal extends Command
{   
    public $data;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:dn-to-journal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        // $num=1;
        // foreach(Pengajuan::where('status',3)->get() as $item){
        //     if(isset($item->polis->fee_base_brokerage) and $item->polis->fee_base_brokerage >0){
                
        //         $income = Income::where(['transaction_table'=>'syariah_underwriting','transaction_id'=>$item->id])->first();
                
        //         if(!$income) continue;

        //         // if($item->polis_id !=76) continue;

        //         $journal_old = Journal::where('transaction_id',$income->id)->where('coa_id',13)->first();
        //         if(!$journal_old){
        //             $journal_old = Journal::where('transaction_id',$income->id)->first();
                    
        //             if(!$journal_old) continue;

        //             echo "No Journal : {$journal_old->no_voucher}\n";
        //             echo "{$num}. No Pengajuan : {$item->no_pengajuan}\n";
        //             echo "-------------------------------------------------\n\n";
                    
        //             $journal = new Journal;
        //             $journal->no_voucher = $journal_old->no_voucher;
        //             $journal->transaction_number = $journal_old->transaction_number;
        //             $journal->transaction_id = $journal_old->transaction_id;
        //             $journal->transaction_table = 'syariah_underwriting'; 
        //             $journal->coa_id = 13;
        //             $journal->date_journal = $journal_old->date_journal;
        //             $journal->debit = $item->brokerage_ujrah;
        //             $journal->kredit = 0;
        //             $journal->saldo = $item->brokerage_ujrah;
        //             $journal->is_auto = 2;
        //             $journal->description = $item->polis->nama;
        //             $journal->save();
                    
        //             $this->error("Journal ID : {$journal->id}");
                    
        //             $num++;
        //         }
        //     }
        // }

        // return;

        // foreach(Pengajuan::where('status',3)->get() as $k => $data){
        foreach(Pengajuan::where('id',10823)->get() as $k => $data){

            //$income = Income::where(['transaction_table'=>'syariah_underwriting','transaction_id' => $data->id])->first();
            //if(!$income) continue;
            //$this->warn("Income ID {$income->id}");

            // $journal = Journal::where(['transaction_id' => $income->id,'transaction_table'=>'konven_underwriting'])->first();
            // if($journal){
            //     $this->warn("Journal ID {$journal->id}");
            //    $journal->delete();
            // }
            // $income->delete();
            $this->warn("--------------------------------");

            $this->error("{$k}. No Pengajuan : {$data->no_pengajuan}");
            echo "DN  : {$data->dn_number}\n";
            $this->data = $data;

            $nilai_manfaat = $data->nilai_manfaat;
            $dana_tabbaru = $data->dana_tabbaru;
            $dana_ujrah = $data->dana_ujrah;
            $kontribusi = $data->kontribusi;
            $extra_kontribusi = $data->extra_kontribusi;
            $extra_mortalita = $data->extra_mortalita;
            
            if($kontribusi <=0) continue;

            // $this->data->nilai_manfaat = $nilai_manfaat;
            // $this->data->dana_tabbaru = $dana_tabbaru;
            // $this->data->dana_ujrah = $dana_ujrah;
            // $this->data->kontribusi = $kontribusi;
            // $this->data->extra_kontribusi = $extra_kontribusi;
            // $this->data->extra_mortalita = $extra_mortalita;

            $total = $kontribusi+
                        $extra_kontribusi+
                        $extra_mortalita+
                        $this->data->biaya_sertifikat+
                        $this->data->biaya_polis_materai+
                        $this->data->pph-($this->data->ppn+$this->data->potong_langsung+$this->data->brokerage_ujrah);

            $this->data->net_kontribusi = $total;
            // $this->data->save();

            // // find polis
            $polis = Polis::where('no_polis',$this->data->polis->no_polis)->first();
            // if(!$polis){
            //     $polis = new Polis();
            //     $polis->no_polis = $this->data->polis->no_polis;
            //     $polis->pemegang_polis = $this->data->polis->nama;
            //     $polis->alamat = $this->data->polis->alamat;
            //     $polis->save();
            // }

            // insert finance
            $income = new Income();
            // $income->user_id = \Auth::user()->id;
            $income->reference_no = $this->data->dn_number;
            $income->reference_date = date('Y-m-d');
            $income->nominal = $total;
            $income->client = $this->data->polis->no_polis .'/'. $this->data->polis->nama;
            $income->reference_type = 'Premium Receivable';
            $income->transaction_table = 'syariah_underwriting';
            $income->transaction_id = $this->data->id;
            $income->type = 2; // Syariah
            $income->policy_id = $polis->id;
            if($this->data->tanggal_jatuh_tempo) $income->due_date = $this->data->tanggal_jatuh_tempo;
            $income->tabarru = $dana_tabbaru;
            $income->ujrah = $dana_ujrah;
            $income->nilai_manfaat_asuransi = $this->data->nilai_manfaat;
            $income->tabarru = $this->data->dana_tabbaru;
            $income->ujrah = $this->data->dana_ujrah;
            $income->kontribusi = $this->data->kontribusi;
            $income->extra_kontribusi = $this->data->extra_kontribusi;
            $income->extra_mortality = $this->data->extra_mortalita;
            $income->save();
            
            /**
             * 4 = Kontribusi Tab Baru (kredit)
             * 3 = Kontribusi Ujrah (kredit)
             * 2 = Tagihan Kontribusi (debit)
             * 1 = Tagihan Kontribusi Ujrah (debit)
             * 5 = Management Fee - Ujrah (debit)
             * 6 = Pendapatan Administrasi Polis Ujrah (kredit)
             * 7 = Utang Pajak PPH 23 (kredit)
             * 13  = Beban Komisi (debit)
             */

            //  kontribusi tabarru = dana tabarru + extra kontribusi + extra mortalita
            //  tagihan kontribusi = dana tabarru + extra kontribusi + extra mortalita
            $no_voucher = "";
            
            foreach([13,7,5,6,4,3,2,1] as $k => $coa_id){
                if($no_voucher=="") $no_voucher = generate_no_voucher($coa_id);

                $new  = new Journal();
                $new->transaction_number = $this->data->dn_number;
                $new->transaction_id = $income->id;
                $new->transaction_table = 'konven_underwriting'; 
                $new->coa_id = $coa_id;
                $new->no_voucher = $no_voucher;
                if($this->data->head_syariah_submit) 
                    $new->date_journal = date('Y-m-d',strtotime($this->data->head_syariah_submit));
                else
                    $new->date_journal = date('Y-m-d',strtotime($this->data->updated_at));

                if($coa_id==4){
                    $temp = $dana_tabbaru;
                    if($this->data->extra_kontribusi) $temp += $this->data->extra_kontribusi;
                    if($this->data->extra_mortalita) $temp += $this->data->extra_mortalita;
                    $new->kredit = $temp;
                } 
                
                if($coa_id==3) {
                    $plus = $dana_ujrah;

                    /**
                     * Jika ada potong langsung maka masuk ke dana ujrah kemudian dipotong di debit coa potong langsung
                     * pengurangan brokerage ujrah
                     * penambahan biaya sertifikat
                     * penambahan biaya polis
                     * penambahan pph
                     * pengurang ppn
                     * penambahan extra kontribusi
                     * penambahan exta mortalita
                     */
                    // if($this->data->potong_langsung) $plus -= $this->data->potong_langsung;
                    // if($this->data->brokerage_ujrah) $plus -= $this->data->brokerage_ujrah;
                    // if($this->data->biaya_sertifikat) $plus += $this->data->biaya_sertifikat;
                    // if($this->data->biaya_polis_materai) $plus += $this->data->biaya_polis_materai;
                    // if($this->data->pph) $plus += $this->data->pph;
                    // if($this->data->ppn) $plus -= $this->data->ppn;
                    // if($this->data->extra_kontribusi) $plus += $this->data->extra_kontribusi;
                    // if($this->data->extra_mortalita) $plus += $this->data->extra_mortalita;

                    $new->kredit = $plus;
                }
                if($coa_id==2) {
                    $temp = $dana_tabbaru;
                    if($this->data->extra_kontribusi) $temp += $this->data->extra_kontribusi;
                    if($this->data->extra_mortalita) $temp += $this->data->extra_mortalita;

                    $new->debit = $temp;
                }
                if($coa_id==1) {
                    $temp = $dana_ujrah;
                    
                    if($this->data->pph) $temp += $this->data->pph;
                    if($this->data->potong_langsung) $temp -= $this->data->potong_langsung;
                    if($this->data->brokerage_ujrah) $temp -= $this->data->brokerage_ujrah;
                    if($this->data->biaya_sertifikat) $temp -= $this->data->biaya_sertifikat;
                    if($this->data->biaya_polis_materai) $temp -= $this->data->biaya_polis_materai;

                    $new->debit = $temp;
                }
                if($coa_id==5){
                    if($this->data->potong_langsung=="") continue;
                    $new->debit = $this->data->potong_langsung;
                }
                if($coa_id==6){
                    if($this->data->biaya_sertifikat=="" and $this->data->biaya_polis_materai=="") continue;
                    $new->kredit = $this->data->biaya_sertifikat + $this->data->biaya_polis_materai;
                }
                if($coa_id==13){
                    if($this->data->brokerage_ujrah=="") continue;
                    $new->debit = $this->data->brokerage_ujrah;
                }

                if($coa_id==7){
                    if($this->data->pph)
                        $new->kredit = $this->data->pph;
                    else 
                        continue; 
                }

                $new->description = $this->data->polis->nama;
                $new->saldo = ($new->debit!=0 ? $new->debit : ($new->kredit!=0?$new->kredit : 0));
                $new->is_manual = 2;
                $new->save();
            }
        }
        return 0;
    }
}
