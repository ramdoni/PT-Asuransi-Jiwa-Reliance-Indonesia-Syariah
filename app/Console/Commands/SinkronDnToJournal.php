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
        return;
        // foreach(Journal::get() as $new){
        //     echo "Journal : {$new->transaction_number}\n";
        //     $new->saldo = replace_idr($new->debit!=0 ? $new->debit : ($new->kredit!=0?$new->kredit : 0));
        //     $new->save();
        // }

        // return;

        foreach(Pengajuan::where('status',3)->get() as $k => $data){
            echo "{$k}. No Pengajuan : {$data->no_pengajuan}\n";
            echo "DN  : {$data->dn_number}\n";
            $this->data = $data;
            $select = Kepesertaan::select(\DB::raw("SUM(basic) as total_nilai_manfaat"),
                                        \DB::raw("SUM(dana_tabarru) as total_dana_tabbaru"),
                                        \DB::raw("SUM(dana_ujrah) as total_dana_ujrah"),
                                        \DB::raw("SUM(kontribusi) as total_kontribusi"),
                                        \DB::raw("SUM(extra_kontribusi) as total_extra_kontribusi"),
                                        \DB::raw("SUM(extra_mortalita) as total_extra_mortalita")
                                        )->where(['pengajuan_id'=>$this->data->id,'status_akseptasi'=>1])->first();

            $nilai_manfaat = $select->total_nilai_manfaat;
            $dana_tabbaru = $select->total_dana_tabbaru;
            $dana_ujrah = $select->total_dana_ujrah;
            $kontribusi = $select->total_kontribusi;
            $ektra_kontribusi = $select->total_extract_kontribusi;
            $extra_mortalita = $select->total_extra_mortalita;
            
            if($kontribusi <=0) continue;

            $this->data->nilai_manfaat = $nilai_manfaat;
            $this->data->dana_tabbaru = $dana_tabbaru;
            $this->data->dana_ujrah = $dana_ujrah;
            $this->data->kontribusi = $kontribusi;
            $this->data->extra_kontribusi = $ektra_kontribusi;
            $this->data->extra_mortalita = $extra_mortalita;

            if($this->data->polis->potong_langsung){
                $this->data->polis->potong_langsung = str_replace(",",".",$this->data->polis->potong_langsung);
                $this->data->potong_langsung_persen = $this->data->polis->potong_langsung;
                $this->data->potong_langsung = @$kontribusi*($this->data->polis->potong_langsung/100);
            }
            
            if($this->data->polis->fee_base_brokerage){
                $this->data->polis->fee_base_brokerage = str_replace(",",".",$this->data->polis->fee_base_brokerage);
                $this->data->brokerage_ujrah_persen = $this->data->polis->fee_base_brokerage;
                $this->data->brokerage_ujrah = @$kontribusi*($this->data->polis->fee_base_brokerage/100);
            }

            /**
             * Hitung PPH
             */
            if($this->data->polis->pph){
                $this->data->pph_persen =  $this->data->polis->pph;

                if($this->data->polis->ket_diskon=='Potong Langsung + Brokerage Ujroh')
                    $this->data->pph = $this->data->brokerage_ujrah*($this->data->polis->pph/100);
                else
                    $this->data->pph = $this->data->potong_langsung*($this->data->polis->pph/100);
            }

            /**
             * Hitung PPN
             */
            if($this->data->polis->ppn){
                $this->data->ppn_persen =  $this->data->polis->ppn;
                if($this->data->potong_langsung)
                    $this->data->ppn = (($this->data->polis->ppn/100) * $this->data->potong_langsung);
                else
                    $this->data->ppn = $kontribusi*($this->data->polis->ppn/100);
            }

            $total = $kontribusi+
                        $ektra_kontribusi+
                        $extra_mortalita+
                        $this->data->biaya_sertifikat+
                        $this->data->biaya_polis_materai+
                        $this->data->pph-($this->data->ppn+$this->data->potong_langsung+$this->data->brokerage_ujrah);
                        
            $this->data->net_kontribusi = $total;

            $select_tertunda =  Kepesertaan::select(\DB::raw("SUM(basic) as total_nilai_manfaat"),
                                            \DB::raw("SUM(dana_tabarru) as total_dana_tabbaru"),
                                            \DB::raw("SUM(dana_ujrah) as total_dana_ujrah"),
                                            \DB::raw("SUM(kontribusi) as total_kontribusi"),
                                            \DB::raw("SUM(extra_kontribusi) as total_extra_kontribusi"),
                                            \DB::raw("SUM(extra_mortalita) as total_extra_mortalita")
                                            )->where(['pengajuan_id'=>$this->data->id,'status_akseptasi'=>2])->first();

            $manfaat_Kepesertaan_tertunda = $select_tertunda->total_nilai_manfaat;
            $kontribusi_kepesertaan_tertunda =  $select_tertunda->total_kontribusi;

            // find polis
            $polis = Polis::where('no_polis',$this->data->polis->no_polis)->first();
            if(!$polis){
                $polis = new Polis();
                $polis->no_polis = $this->data->polis->no_polis;
                $polis->pemegang_polis = $this->data->polis->nama;
                $polis->alamat = $this->data->polis->alamat;
                $polis->save();
            }

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

                if($coa_id==4) $new->kredit = $dana_tabbaru;
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
                    if($this->data->potong_langsung) $plus -= $this->data->potong_langsung;
                    if($this->data->brokerage_ujrah) $plus -= $this->data->brokerage_ujrah;
                    if($this->data->biaya_sertifikat) $plus += $this->data->biaya_sertifikat;
                    if($this->data->biaya_polis_materai) $plus += $this->data->biaya_polis_materai;
                    if($this->data->polis->pph) $plus += $this->data->polis->pph;
                    if($this->data->polis->ppn) $plus -= $this->data->polis->ppn;
                    if($this->data->extra_kontribusi) $plus += $this->data->extra_kontribusi;
                    if($this->data->extra_mortalita) $plus += $this->data->extra_mortalita;

                    $new->kredit = $plus;
                }
                if($coa_id==2) $new->debit = $dana_tabbaru;
                if($coa_id==1) $new->debit = $dana_ujrah;
                if($coa_id==5){
                    if($this->data->potong_langsung=="") continue;
                    $new->debit = $this->data->potong_langsung;
                }
                if($coa_id==6){
                    if($this->data->biaya_sertifikat=="" and $this->data->biaya_polis_materai=="") continue;
                    $new->debit = $this->data->biaya_sertifikat + $this->data->biaya_polis_materai;
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
                $new->saldo = replace_idr($new->debit!=0 ? $new->debit : ($new->kredit!=0?$new->kredit : 0));
                $new->is_manual = 2;
                $new->save();
            }
        }
        return 0;
    }
}
