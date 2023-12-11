<?php

namespace App\Http\Livewire\MemoCancel;

use Livewire\Component;
use App\Models\Polis;
use App\Models\MemoCancel;
use App\Models\MemoCancelPeserta;
use App\Models\Kepesertaan;
use App\Models\ReasCancel;
use Livewire\WithFileUploads;

class Insert extends Component
{
    use WithFileUploads;
    public $polis,$polis_id,$file,$peserta=[],$is_insert=false,$kepesertaan_id,$tanggal_efektif,$tanggal_pengajuan,
            $perihal_internal_memo,$memo_cancel,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo;
    
    public function render()
    {
        return view('livewire.memo-cancel.insert');
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
    }

    public function add_peserta()
    {
        $index = count($this->peserta);
        $peserta = Kepesertaan::find($this->kepesertaan_id)->toArray();
        $polis = Polis::find($this->polis_id);
        if($peserta){
            foreach($peserta as $field => $val){
                $this->peserta[$index][$field] = $val;
            }
            
            $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta['kontribusi'] + $peserta['extra_kontribusi'] + $peserta['extra_mortalita'];
            $this->peserta[$index]['cancel_kontribusi_netto'] = round($this->peserta[$index]['total_kontribusi_dibayar'] * ($polis->refund / 100));

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
                $this->peserta[$index]['id'] = $peserta->id;
                $this->peserta[$index]['status_polis'] = $peserta->status_polis;
                $this->peserta[$index]['no_peserta'] = $peserta->no_peserta;
                $this->peserta[$index]['nama'] = $peserta->nama;
                $this->peserta[$index]['tanggal_mulai'] = $peserta->tanggal_mulai;
                $this->peserta[$index]['tanggal_akhir'] = $peserta->tanggal_akhir;
                $this->peserta[$index]['basic'] = $peserta->basic;
                $this->peserta[$index]['masa_bulan'] = $peserta->masa_bulan;
                $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita ;
                $this->peserta[$index]['reas'] = isset($peserta->reas->no_pengajuan) ? $peserta->reas->no_pengajuan : '-';
                $this->peserta[$index]['reasuradur'] = isset($peserta->reas->reasuradur->name) ? $peserta->reas->reasuradur->name : '-';
                // $this->peserta[$index]['cancel_kontribusi_netto'] = $this->peserta[$index]['total_kontribusi_dibayar'] * ($polis->refund / 100);
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
            // 'peserta.*'=>'required',
            "peserta"    => "required|array",
            "peserta.*"  => "required",
            'tanggal_efektif' => 'required',
            'tanggal_pengajuan' => 'required',
            'tujuan_pembayaran' => 'required',
            'nama_bank' => 'required',
            'no_rekening' => 'required',
            'tgl_jatuh_tempo' => 'required'
        ]);
        // try {
            \DB::transaction(function () {
                $polis = Polis::find($this->polis_id);
                $data = new MemoCancel;
                $data->tanggal_pengajuan = $this->tanggal_pengajuan;
                $data->polis_id = $this->polis_id;
                $data->tanggal_efektif = $this->tanggal_efektif;
                $data->tujuan_pembayaran = $this->tujuan_pembayaran;
                $data->nama_bank = $this->nama_bank;
                $data->no_rekening = $this->no_rekening;
                $data->tgl_jatuh_tempo = $this->tgl_jatuh_tempo;
                $data->requester_id = \Auth::user()->id;
                $data->save();

                $data->nomor_cn = $polis->no_polis . '/'. str_pad($data->id,6, '0', STR_PAD_LEFT) ."/UWS-M-CNCL/AJRIUS/".numberToRomawi(date('m')).'/'.date('Y');
                // 036/UW-M-CNCL/AJRIUS/X/2023
                $data->nomor = str_pad($data->id,6, '0', STR_PAD_LEFT) ."/UWS-M-CNCL/AJRIUS/".numberToRomawi(date('m')).'/'.date('Y');
                $data->save();
                
                $total = 0;$total_kontribusi=0;$total_manfaat_asuransi = 0;$total_kontribusi_gross=0;$total_kontribusi_tambahan=0;
                $total_potongan_langsung = 0;$total_ujroh_brokerage=0;$total_ppn=0;$total_pph=0;
                foreach($this->peserta as $k => $item){
                    $peserta = Kepesertaan::find($item['id']);
                    if($peserta){
                        $peserta->memo_cancel_id = $data->id;
                        $peserta->total_kontribusi_dibayar = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;
                        $peserta->save();
                        $total++;

                        $total_kontribusi_gross += $peserta->kontribusi;
                        $total_kontribusi_tambahan += $peserta->extra_kontribusi;
                        $total_manfaat_asuransi += $peserta->basic;

                        if($polis->potong_langsung){
                            $peserta->jumlah_potongan_langsung = $total_kontribusi_gross*($polis->potong_langsung/100);
                            $item['jumlah_potongan_langsung'] = $item['kontribusi']*($polis->potong_langsung/100);
                            $total_potongan_langsung += $peserta->jumlah_potongan_langsung;
                        }
                        
                        if($polis->fee_base_brokerage){
                            $polis->fee_base_brokerage = str_replace(",",".",$polis->fee_base_brokerage);
                            
                            $peserta->brokerage_ujrah_persen = $polis->fee_base_brokerage;
                            $peserta->brokerage_ujrah = @$peserta->kontribusi*($polis->fee_base_brokerage/100);
        
                            $item['brokerage_ujrah_persen'] = $polis->fee_base_brokerage;
                            $item['brokerage_ujrah'] = @$item['kontribusi']*($polis->fee_base_brokerage/100);

                            $total_ujroh_brokerage += $item['brokerage_ujrah'];
                        }
                
                        if($polis->pph){
                            $peserta->pph_amount = $polis->pph;
                            $item['pph'] =  $polis->pph;
                
                            if($polis->ket_diskon=='Potong Langsung + Brokerage Ujroh'){
                                $peserta->pph_amount = $peserta->brokerage_ujrah*($polis->pph/100);
                                $item['pph_amount'] = $item['brokerage_ujrah']*($polis->pph/100);
                            }else{
                                $peserta->pph_amount = $peserta->jumlah_potongan_langsung*($polis->pph/100);
                                $item['pph_amount'] = $item['jumlah_potongan_langsung']*($polis->pph/100);
                            }
                            $total_pph += $item['pph_amount'];
                        }
                
                        if($polis->ppn){
                            $peserta->ppn = $polis->ppn; 
                            $item['ppn'] =  $polis->ppn;
        
                            if(isset($peserta->jumlah_potongan_langsung))
                                $peserta->ppn = (($polis->ppn/100) * $peserta->jumlah_potongan_langsung);
                            else
                                $peserta->ppn = $peserta->kontribusi*($polis->ppn/100);
        
                            if(isset($item['jumlah_potongan_langsung']))
                                $item['ppn'] = (($polis->ppn/100) * $item['jumlah_potongan_langsung']);
                            else
                                $item['ppn'] = $item['kontribusi']*($polis->ppn/100);
                            
                            $total_ppn += $peserta->ppn;
                        }
                        
                        $peserta->total_kontribusi_dibayar = (int)$peserta->kontribusi+
                                    (int)$peserta->extra_kontribusi + (int)$peserta->extra_mortalita+
                                    ((int)$peserta->pph_amount??0)-(
                                        ((int)$peserta->ppn??0)+
                                        ((int)$peserta->jumlah_potongan_langsung??0)+
                                        ((int)$peserta->brokerage_ujrah??0)
                                    );

                        $total_kontribusi += $peserta->total_kontribusi_dibayar;
                        $peserta->cancel_kontribusi_netto = $item['cancel_kontribusi_netto'];//($polis->refund / 100) * $peserta->total_kontribusi_dibayar;
                        $peserta->save();
                    }
                }

                $data->fee_base_brokerage = $total_ujroh_brokerage ;
                $data->pph_amount = $total_pph;
                $data->ppn_amount = $total_ppn;
                $data->total_kontribusi_gross = $total_kontribusi_gross;
                $data->total_potongan_langsung = $total_potongan_langsung;
                $data->total_kontribusi_tambahan = $total_kontribusi_tambahan;
                $data->total_kontribusi = Kepesertaan::where('kepesertaan.memo_cancel_id',$data->id)->sum('cancel_kontribusi_netto');
                $data->total_manfaat_asuransi = $total_manfaat_asuransi;
                $data->total_peserta = $total;  
                $data->save();

                $reasuradur = Kepesertaan::select('kepesertaan.*')->where('kepesertaan.memo_cancel_id',$data->id)
                                ->join('reas','reas.id','=','kepesertaan.reas_id')
                                ->join('reasuradur','reasuradur.id','=','reas.reasuradur_id')
                                ->where(function($table){
                                    $table->where('reasuradur.name','<>','OR')
                                            ->orWhere('reasuradur.name','<>','');
                                })
                                ->groupBy('reasuradur.id')
                                ->get();
                
                foreach($reasuradur as $item){
                    $reas_cancel = new ReasCancel();
                    $reas_cancel->memo_cancel_id = $data->id;
                    $reas_cancel->status = 0;
                    $reas_cancel->polis_id = $data->polis_id;
                    $reas_cancel->tanggal_pengajuan = $data->tanggal_pengajuan;
                    $reas_cancel->reas_id = $item->reas_id;
                    $reas_cancel->save();
                    
                    $reas_cancel->nomor = str_pad($reas_cancel->id,6, '0', STR_PAD_LEFT) ."/REAS-C/AJRI/".numberToRomawi(date('m')).'/'.date('Y');
                    
                    Kepesertaan::where(['memo_cancel_id'=>$data->id,'reas_id'=>$item->reas_id])->update(['reas_cancel_id'=>$reas_cancel->id]);
                    
                    $reas_cancel->total_peserta = Kepesertaan::where(['memo_cancel_id'=>$data->id,'reas_id'=>$item->reas_id])->get()->count();
                    $reas_cancel->total_manfaat_asuransi = Kepesertaan::where(['memo_cancel_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('nilai_manfaat_asuransi_reas');
                    $reas_cancel->total_kontribusi = Kepesertaan::where(['memo_cancel_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('total_kontribusi_reas');
                    $reas_cancel->save();   
                }

                session()->flash('message-success',__('Memo Cancel berhasil disubmit'));

                return redirect()->route('memo-cancel.index');
            });
        // }
        // catch (\Throwable $e) {
        //     $this->emit('message-error', json_encode($e));
        // }
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
