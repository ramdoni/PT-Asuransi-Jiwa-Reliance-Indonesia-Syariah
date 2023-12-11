<?php

namespace App\Http\Livewire\MemoRefund;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Polis;
use App\Models\Refund;
use App\Models\ReasRefund;
use App\Models\Kepesertaan;

class Insert extends Component
{
    use WithFileUploads;
    public $polis,$polis_id,$file,$peserta=[],$is_insert=false,$kepesertaan_id,$tanggal_efektif,$tanggal_pengajuan,
            $perihal_internal_memo,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo,$no_peserta_awal,$no_peserta_akhir;
    public function render()
    {
        return view('livewire.memo-refund.insert');
    }

    public function mount()
    {
        $this->tanggal_pengajuan = date('Y-m-d');
        $this->tanggal_efektif = date('Y-m-d');
        $this->polis = Polis::where('status_approval',1)->get();
    }

    public function updated($propertyName)
    {
        if($propertyName=='polis_id'){
            $this->peserta = [];
        }

        foreach($this->peserta as $k => $i){
            $peserta = Kepesertaan::find($i['id']);
            if($peserta){
                $this->peserta[$k]['refund_sisa_masa_asuransi'] = hitung_masa_bulan($i['refund_tanggal_efektif'],$peserta->tanggal_akhir,3);
                $this->peserta[$k]['refund_kontribusi'] = ($this->peserta[$k]['refund_sisa_masa_asuransi'] / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $i['total_kontribusi_dibayar']);
            }
        }
    }

    public function add_peserta()
    {
        $index = count($this->peserta);
        $peserta = Kepesertaan::find($this->kepesertaan_id)->toArray();
        if($peserta){
            $polis = Polis::find($this->polis_id);
            foreach($peserta as $field => $val){
                $this->peserta[$index][$field] = $val;
            }
           
            $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta['kontribusi'] + $peserta['extra_kontribusi'] + $peserta['extra_mortalita'] ;
            // $this->peserta[$index]['reas'] = isset($peserta->reas->no_pengajuan) ? $peserta->reas->no_pengajuan : '-';
            // $this->peserta[$index]['reasuradur'] = isset($peserta->reas->reasuradur->name) ? $peserta->reas->reasuradur->name : '-';
            $this->peserta[$index]['refund_tanggal_efektif'] = date('Y-m-d');
            $this->peserta[$index]['refund_sisa_masa_asuransi'] = hitung_masa_bulan(date('Y-m-d'),$peserta['tanggal_akhir'],3);
            $this->peserta[$index]['refund_kontribusi'] = ($this->peserta[$index]['refund_sisa_masa_asuransi'] / $peserta['masa_bulan']) * (($polis->refund / 100) * $this->peserta[$index]['total_kontribusi_dibayar']);

            $ids = [];
            foreach($this->peserta as $item){
                $ids[] = $item['id'];
            }
            $this->emit('on-change-peserta',implode("-",$ids));
        }else{
            $this->emit('message-error','Data Kepesertaan tidak ditemukan');
        }
    }

    public function upload()
    {
        ini_set('memory_limit', '-1');
        
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200', // 50MB maksimal
        ]);

        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $xlsx = $reader->load($path);
        $sheetData = $xlsx->getActiveSheet()->toArray(null, true, true, true);

        $insert = [];
        $check_double = [];
        $this->error_upload = '';
        $index=0;
        $this->peserta = [];
        foreach($sheetData as $key => $item){
            if($key<=0) continue;
            $no_peserta = $item['C'];

            $peserta = Kepesertaan::where('no_peserta',$no_peserta)->first();
            if($peserta){
                foreach($peserta as $field => $val){
                    $this->peserta[$index][$field] = $val;
                }

                $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita ;
                $this->peserta[$index]['reas'] = isset($peserta->reas->no_pengajuan) ? $peserta->reas->no_pengajuan : '-';
                $this->peserta[$index]['reasuradur'] = isset($peserta->reas->reasuradur->name) ? $peserta->reas->reasuradur->name : '-';
                $this->peserta[$index]['refund_tanggal_efektif'] = date('Y-m-d');
                $this->peserta[$index]['refund_sisa_masa_asuransi'] = hitung_masa_bulan(date('Y-m-d'),$peserta->tanggal_akhir,3);
                $this->peserta[$index]['refund_kontribusi'] = ($this->peserta[$index]['refund_sisa_masa_asuransi'] / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $this->peserta[$index]['total_kontribusi_dibayar']);
                
                $index++;
            }
        }

        $ids = [];
        foreach($this->peserta as $item){
            $ids[] = $item['id'];
        }
        
        $this->emit('on-change-peserta',implode("-",$ids));

        $this->emit('modal','hide');
    }

    public function submit()
    {
        $this->validate([
            'polis_id' => 'required',
            'tanggal_efektif' => 'required|before:tomorrow',
            'tanggal_pengajuan' => 'required||before:tomorrow',
            "peserta"    => "required|array",
            "peserta.*"  => "required",
        ]);
        try {
            \DB::transaction(function () {
                $polis = Polis::find($this->polis_id);
                $data = new Refund;
                $data->tanggal_pengajuan = $this->tanggal_pengajuan;
                $data->polis_id = $this->polis_id;
                $data->tanggal_efektif = $this->tanggal_efektif;
                $data->tujuan_pembayaran = $this->tujuan_pembayaran;
                $data->nama_bank = $this->nama_bank;
                $data->no_peserta_awal = $this->no_peserta_awal;
                $data->no_peserta_akhir = $this->no_peserta_akhir;
                $data->no_rekening = $this->no_rekening;
                $data->tgl_jatuh_tempo = $this->tgl_jatuh_tempo;
                $data->user_created_id = \Auth::user()->id;
                $data->status = 0;
                $running_number = get_setting('running_number_refund')+1;
                $running_number_cn = get_setting('running_number_refund')+1;

                $data->nomor = str_pad($running_number,4, '0', STR_PAD_LEFT) ."/UW-RFND-CN-R/".numberToRomawi(date('m')).'/'.date('Y');
                $data->nomor_cn = $polis->no_polis . '/'. str_pad($running_number_cn,4, '0', STR_PAD_LEFT) ."/AJRIUS-CN-R/".numberToRomawi(date('m')).'/'.date('Y');
                $data->save();

                update_setting('running_number_refund',$running_number);

                // 036/UW-M-CNCL/AJRIUS/X/2023
                $total = 0;$total_kontribusi=0;$total_manfaat_asuransi = 0;$total_kontribusi_gross=0;$total_kontribusi_tambahan=0;
                $total_potongan_langsung = 0;$total_ujroh_brokerage=0;$total_ppn=0;$total_pph=0;
                foreach($this->peserta as $k => $item){
                    $peserta = Kepesertaan::find($item['id']);
                    if($peserta){
                        $peserta->memo_refund_id = $data->id;
                        $peserta->refund_tanggal_efektif = $item['refund_tanggal_efektif'];
                        $peserta->refund_sisa_masa_asuransi = hitung_masa_bulan($item['refund_tanggal_efektif'],$peserta->tanggal_akhir,3);
                        $peserta->total_kontribusi_dibayar = $item['total_kontribusi_dibayar'];
                        $peserta->save();
                        $total++;
                        /**
                         *
                            Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross
                            t            = sisa masa asuransi (dalam bulan)
                            n            = masa asuransi (dalam bulan)
                            %            = persentase pengembalian asuransi (sesuai yang tercantum di Polis)
                         */
                         $peserta->refund_kontribusi = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $peserta->total_kontribusi_dibayar);
                        
                        if($peserta->net_kontribusi_reas>0 and $peserta->reas_id>0) {

                            /**
                                1.Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross reas atau
                                2.Nilai Pengembalian Kontribusi = t/n x dana tabarru’reas
                                3.Nilai Pengembalian Kontribusi = t/n x % x dana tabarru’reas
                             */
                            if(isset($peserta->reas->rate_uw->type_pengembalian_kontribusi)){
                                $refund_reas_persen = isset($peserta->reas->rate_uw->persentase_refund) ? $peserta->reas->rate_uw->persentase_refund : 0; 
                                $type_pengembalian = $peserta->reas->rate_uw->type_pengembalian_kontribusi;
                                // Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross reas atau
                                if($type_pengembalian==1){
                                    $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $peserta->net_kontribusi_reas);
                                }
                                
                                if($peserta->reas->rate_uw->tabbaru)
                                    $dana_tabbaru_reas = ($peserta->reas->rate_uw->tabbaru /100)*$peserta->net_kontribusi_reas;
                                else
                                    $dana_tabbaru_reas = $peserta->net_kontribusi_reas;
                                
                                // Nilai Pengembalian Kontribusi = t/n x dana tabarru’reas
                                if($type_pengembalian==2){
                                    $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * $data_tabbaru_reas;
                                }
                                //Nilai Pengembalian Kontribusi = t/n x % x dana tabarru’reas
                                if($type_pengembalian==3){
                                    $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $data_tabbaru_reas);
                                }
                            }
                        }

                        $peserta->save();

                        $total_kontribusi_gross += $peserta->kontribusi;
                        // $total_potongan_langsung += $peserta->jumlah_potongan_langsung;
                        $total_kontribusi_tambahan += $peserta->extra_kontribusi;
                        $total_kontribusi += $peserta->refund_kontribusi;
                        $total_manfaat_asuransi += $peserta->basic;
                    }
                }

                if($polis->potong_langsung){
                    $total_potongan_langsung = $total_kontribusi_gross*($polis->potong_langsung/100);
                }

                if($polis->fee_base_brokerage){
                    $polis->fee_base_brokerage = str_replace(",",".",$polis->fee_base_brokerage);
                    $data->brokerage_ujrah_persen = $polis->fee_base_brokerage;
                    $data->brokerage_ujrah = @$total_kontribusi*($polis->fee_base_brokerage/100);
                }

                /**
                 * Hitung PPH
                 */
                if($polis->pph){
                    $data->pph =  $polis->pph;

                    if($polis->ket_diskon=='Potong Langsung + Brokerage Ujroh')
                        $data->pph_amount = $data->brokerage_ujrah*($polis->pph/100);
                    else
                        $data->pph_amount = $data->potong_langsung*($polis->pph/100);
                }

                /**
                 * Hitung PPN
                 */
                if($polis->ppn){
                    $data->ppn =  $polis->ppn;
                    if($data->potong_langsung)
                        $data->ppn_amount = (($polis->ppn/100) * $data->potong_langsung);
                    else
                        $data->ppn_amount = $kontribusi*($polis->ppn/100);
                }

                $data->total_kontribusi_gross = $total_kontribusi_gross;
                $data->total_potongan_langsung = $total_potongan_langsung;
                $data->total_kontribusi_tambahan = $total_kontribusi_tambahan;
                $data->total_manfaat_asuransi = $total_manfaat_asuransi;
                $data->total_kontribusi = $total_kontribusi;
                $data->total_peserta = $total;  
                $data->save();

                $reasuradur = Kepesertaan::select('kepesertaan.*')->where('kepesertaan.memo_refund_id',$data->id)
                                ->join('reas','reas.id','=','kepesertaan.reas_id')
                                ->join('reasuradur','reasuradur.id','=','reas.reasuradur_id')
                                ->where(function($table){
                                    $table->where('reasuradur.name','<>','OR')
                                            ->orWhere('reasuradur.name','<>','');
                                })
                                ->groupBy('reasuradur.id')
                                ->get();
                
                foreach($reasuradur as $item){
                    $reas_refund = new ReasRefund();
                    $reas_refund->memo_refund_id = $data->id;
                    $reas_refund->status = 0;
                    $reas_refund->polis_id = $data->polis_id;
                    $reas_refund->tanggal_pengajuan = $data->tanggal_pengajuan;
                    $reas_refund->reas_id = $item->reas_id;
                    $reas_refund->save();
                    
                    $reas_refund->nomor = str_pad($reas_refund->id,6, '0', STR_PAD_LEFT) ."/RFND-C/AJRI/".numberToRomawi(date('m')).'/'.date('Y');
                    
                    Kepesertaan::where(['memo_refund_id'=>$data->id,'reas_id'=>$item->reas_id])->update(['reas_refund_id'=>$reas_refund->id]);
                    
                    $reas_refund->total_peserta = Kepesertaan::where(['memo_refund_id'=>$data->id,'reas_id'=>$item->reas_id])->get()->count();
                    $reas_refund->total_manfaat_asuransi = Kepesertaan::where(['memo_refund_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('nilai_manfaat_asuransi_reas');
                    // $reas_refund->total_kontribusi = Kepesertaan::where(['memo_refund_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('net_kontribusi_reas');
                    $reas_refund->total_kontribusi = Kepesertaan::where(['memo_refund_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('refund_kontribusi_reas');
                    $reas_refund->save();   
                }

                session()->flash('message-success',__('Memo Cancel berhasil disubmit'));

                return redirect()->route('memo-refund.index');
            });
        }
        catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }

    public function delete_peserta($k)
    {
        unset($this->peserta[$k]);
    }

    public function downloadTemplate()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Entigi System")
                                    ->setLastModifiedBy("Entigi System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Peserta")
                                    ->setKeywords("office 2007 openxml php");
        
        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet
                ->setCellValue('A1', 'NO')
                ->setCellValue('B1', 'NAMA')
                ->setCellValue('C1', 'NOMOR PESERTA');

        $activeSheet->getColumnDimension('A')->setWidth(5);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
            
        // Rename worksheet
        // $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="template-upload-no-peserta.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        //header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },'template-upload-no-peserta.xlsx');
    }
}
