<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;

class SinkronDn extends Command
{
    public $selected_id,$data;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:dn {pengajuan_id}';

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

        $this->selected_id = $this->argument('pengajuan_id');
        
        $this->data = Pengajuan::find($this->selected_id);
        if($this->data){
            $key=0;

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

            $this->data->nilai_manfaat = $nilai_manfaat;
            $this->data->dana_tabbaru = $dana_tabbaru;
            $this->data->dana_ujrah = $dana_ujrah;
            $this->data->kontribusi = $kontribusi;
            $this->data->extra_kontribusi = $ektra_kontribusi;
            $this->data->extra_mortalita = $extra_mortalita;

            if($this->data->polis->potong_langsung){
                $this->data->potong_langsung_persen = $this->data->polis->potong_langsung;
                $this->data->potong_langsung = $kontribusi*($this->data->polis->potong_langsung/100);
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

            /**
             * Biaya Polis dan Materai
             * jika pengajuan baru pertama kali ada biaya polis dan materia 100.000
             * */
            // if($running_number_first==0 || $running_number_first==""){
            //     $this->data->biaya_polis_materai = $this->data->polis->biaya_polis_materai;
            //     $this->data->biaya_sertifikat = $this->data->polis->biaya_sertifikat;
            // }

            $total = $kontribusi+
                        $ektra_kontribusi+
                        $extra_mortalita+
                        $this->data->biaya_sertifikat+
                        $this->data->biaya_polis_materai+
                        $this->data->pph-($this->data->ppn+$this->data->potong_langsung+$this->data->brokerage_ujrah);
                        
            $this->data->net_kontribusi = $total;
            $this->data->save();
        }
    }
}
