<?php

namespace App\Http\Livewire\RecoveryClaim;

use Livewire\Component;
use App\Models\RecoveryClaim;
use App\Models\Kepesertaan;
use App\Models\Polis;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$is_download=false,$filter=[],$is_rekon=false,$check_id=[],$polis=[],$filter_polis_id;
    public $filter_peserta;
    public function render()
    {
        $data = $this->get_data();

        return view('livewire.recovery-claim.index')->with(['data'=>$data->paginate(100)]);
    }

    public function mount()
    {
        $this->polis = RecoveryClaim::with('polis')->groupBy('polis_id')->get();
    }

    public function updated($propertyName)
    {
        if($propertyName=='filter_polis_id'){
            $this->check_id = [];
        }
    }

    public function delete()
    {
        RecoveryClaim::find($this->selected_id)->delete();
        
        Kepesertaan::where('recovery_claim_id',$this->selected_id)->update(['recovery_claim_id'=>null]);

        $this->emit('message-success','Recovery Claim berhasil dihapus');$this->emit('modal','hide');
    }

    public function get_data()
    {
        $data = RecoveryClaim::with(['polis','klaim'])->orderBy('id','DESC')
            ->whereHas('kepesertaan', function($q)
                {   
                    if($this->filter_peserta) {
                        $q->where('nama', 'LIKE', "%{$this->filter_peserta}%")
                            ->orWhere('no_peserta', 'LIKE', "%{$this->filter_peserta}%");
                    }
                });

        foreach($this->filter as $k=>$v){
            $data->where($k,$v);
        }
        
        if($this->filter_polis_id) $data->where('polis_id',$this->filter_polis_id);
        
        

        return $data;
    }

    public function generateDn()
    {
        $this->validate([
            'check_id' => 'required'
        ]);

        $date = date('Y-m-d H:i:s');
        $running_number_dn = get_setting('running_number_dn_recovery_claim')+1;
        update_setting('running_number_dn_recovery_claim',$running_number_dn);
        $nomor_dn = str_pad($running_number_dn,4, '0', STR_PAD_LEFT) ."/AJRIUS-DN-KLRS/".numberToRomawi(date('m'))."/".date('Y');
                
        foreach($this->check_id as $k => $id){
            RecoveryClaim::find($id)->update([
                'rekon_status'=>1,
                'rekon_date'=>$date,
                'rekon_dn'=>$nomor_dn
            ]);
        }

        $this->emit('message-success','Rekon updated');$this->check_id = [];$this->is_rekon = false;
    }

    public function clear_filter()
    {
        $this->filter = [];
    }

    public function downloadExcel()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Entigi System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Daftar Peserta")
                                    ->setKeywords("office 2007 openxml php");

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A1', 'PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH');
        $activeSheet->mergeCells("A1:L1");
        
        $activeSheet->setCellValue('A3', 'L I S T  OF  C L A I M');
        $activeSheet->mergeCells("A3:L3");
        
        $activeSheet->getRowDimension('1')->setRowHeight(34);
        $activeSheet->getStyle('A1')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ]);

        $activeSheet->getStyle('A3')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ]);;
        $polis = Polis::find($this->filter_polis_id);
        $activeSheet->setCellValue('A6', 'Tipe Bisnis')
                    ->setCellValue('C6', " : {$polis->tipe}")
                    ->setCellValue('A7', 'Mata Uang')
                    ->setCellValue('C7', " : IDR")
                    ->setCellValue('A8', 'Plan')
                    ->setCellValue('C8', " : {$polis->produk->singkatan}")
                    ->setCellValue('A9', 'Nama Pemegang Polis')
                    ->setCellValue('C9', " : {$polis->nama}")
                    ;

        $activeSheet->getStyle("B4:B7")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        $activeSheet->getStyle("C4:C7")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        
        $activeSheet
                ->setCellValue('A13', 'NO')
                ->setCellValue('B13', 'NOMOR')
                ->setCellValue('D13', 'NAMA PESERTA')
                ->setCellValue('E13', 'TANGGAL')
                ->setCellValue('I13', 'MANFAAT ASURANSI TOTAL')
                ->setCellValue('J13', 'MANFAAT ASURANSI REAS')
                ->setCellValue('K13', 'ESTIMASI NILAI KLAIM')
                ->setCellValue('M13', 'STATUS KLAIM');

        $activeSheet
                ->setCellValue('B14', 'POLIS')
                ->setCellValue('C14', 'PESERTA')
                ->setCellValue('E14', 'LAHIR')
                ->setCellValue('F14', 'AWAL')
                ->setCellValue('G14', 'AKHIR')
                ->setCellValue('K14', 'TOTAL KLAIM')
                ->setCellValue('L14', 'SHARE REAS');
        
        $activeSheet->getColumnDimension('A')->setWidth(5.43);
        $activeSheet->getColumnDimension('B')->setWidth(20.14);
        $activeSheet->getColumnDimension('C')->setWidth(22.29);
        $activeSheet->getColumnDimension('D')->setWidth(20.71);
        $activeSheet->getColumnDimension('E')->setWidth(11.57);
        $activeSheet->getColumnDimension('F')->setWidth(11.57);
        $activeSheet->getColumnDimension('G')->setWidth(11.57);
        $activeSheet->getColumnDimension('H')->setWidth(11.57);
        $activeSheet->getColumnDimension('I')->setWidth(16.86);
        $activeSheet->getColumnDimension('J')->setWidth(16.86);
        $activeSheet->getColumnDimension('K')->setWidth(16.86);
        $activeSheet->getColumnDimension('L')->setWidth(16.86);
        $activeSheet->getColumnDimension('M')->setWidth(24.43);
        
        $activeSheet->mergeCells("A13:A14");
        $activeSheet->mergeCells("B13:C13");
        $activeSheet->mergeCells("D13:D14");
        $activeSheet->mergeCells("E13:H13");
        $activeSheet->mergeCells("K13:L14");
        $activeSheet->mergeCells("I13:I14");
        $activeSheet->mergeCells("J13:J14");
        $activeSheet->mergeCells("M13:M14");
            
        $activeSheet->getStyle("A13:M13")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                ]);
        $activeSheet->getStyle("A14:M14")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                ]);
       
        $data = RecoveryClaim::with(['polis','kepesertaan','klaim'])->whereIn('id',$this->check_id)->get();
        $num=15;
        $total_manfaat=0;$total_manfaat_reas=0;$total_klaim=0;$total_share_reas=0;
        $tanggal_pengajuan = date('Y-m-d');
        foreach($data as $k=>$i){
            $i->rekon_status = 1;
            $i->save();

            $status_klaim = '-';
            if($i->klaim){
                switch($i->klaim->status_pengajuan){
                    case "":
                        $status_klaim = 'Analisa';
                    break;
                    case 1:
                        $status_klaim = 'Diterima';
                    break;
                    case 2:
                        $status_klaim = 'Tolak';
                    break;
                    case 3:
                        $status_klaim = 'Tunda';
                    break;
                    case 4:
                        $status_klaim = 'Investigasi';
                    break;
                    case 5:
                        $status_klaim = 'Liable';
                    break;
                    case 6:
                        $status_klaim = 'STNC';
                    break;
                    case 7:
                        $status_klaim = 'Batal';
                    break;
                }
            }
            
            $tanggal_pengajuan = $i->created_at;

            $activeSheet
                ->setCellValue("A{$num}", $k+1)
                ->setCellValue("B{$num}", isset($i->polis->no_polis) ? $i->polis->no_polis :'-')
                ->setCellValue("C{$num}", isset($i->kepesertaan->no_peserta) ? $i->kepesertaan->no_peserta : '-')
                ->setCellValue("D{$num}", isset($i->kepesertaan->nama) ? $i->kepesertaan->nama : '-')
                ->setCellValue("E{$num}", isset($i->kepesertaan->tanggal_lahir) ? date('d-M-Y',strtotime($i->kepesertaan->tanggal_lahir)) : '-')
                ->setCellValue("F{$num}", isset($i->kepesertaan->tanggal_mulai) ? date('d-M-Y',strtotime($i->kepesertaan->tanggal_mulai)) : '-')
                ->setCellValue("G{$num}", isset($i->kepesertaan->tanggal_akhir) ? date('d-M-Y',strtotime($i->kepesertaan->tanggal_akhir)) : '-')
                ->setCellValue("H{$num}", isset($i->created_at) ? date('d-M-Y',strtotime($i->created_at)) : '-')
                ->setCellValue("I{$num}", isset($i->kepesertaan->basic) ? $i->kepesertaan->basic : 0)
                ->setCellValue("J{$num}", isset($i->kepesertaan->nilai_manfaat_asuransi_reas) ? $i->kepesertaan->nilai_manfaat_asuransi_reas : 0)
                ->setCellValue("K{$num}", $i->nilai_klaim)
                ->setCellValue("L{$num}", isset($i->klaim->nilai_klaim_reas) ? $i->klaim->nilai_klaim_reas : 0)
                ->setCellValue("M{$num}", $status_klaim);
            
                $activeSheet->getStyle("I{$num}:L{$num}")->getNumberFormat()->setFormatCode('#,##0');

            $total_manfaat +=$i->kepesertaan->basic;
            $total_manfaat_reas +=$i->kepesertaan->nilai_manfaat_asuransi_reas;
            $total_klaim +=$i->nilai_klaim;
            $total_share_reas +=$i->klaim->nilai_klaim_reas;

            
            $num++;
        }
        $num++;
        $activeSheet->getStyle("A{$num}:M{$num}")->applyFromArray([
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'bold' => true,
            ]
        ]);

        $activeSheet
        ->setCellValue("I{$num}", $total_manfaat)
        ->setCellValue("J{$num}", $total_manfaat_reas)
        ->setCellValue("K{$num}", $total_klaim)
        ->setCellValue("L{$num}", $total_share_reas);
        
        $activeSheet->getStyle("I{$num}:L{$num}")->getNumberFormat()->setFormatCode('#,##0');

        $num += 2;
        $activeSheet->mergeCells("L{$num}:M{$num}");
        $activeSheet->mergeCells("L".($num+1).":M".($num+1));
        $activeSheet->mergeCells("L".($num+2).":M".($num+2));
        $activeSheet->mergeCells("L".($num+6).":M".($num+6));
        $activeSheet
            ->setCellValue("L{$num}", 'Jakarta, '.date('d F Y',strtotime($tanggal_pengajuan)))
            ->setCellValue("L".($num+1), "PT ASURANSI JIWA RELIANCE INDONESIA	")
            ->setCellValue("L".($num+2), "UNIT SYARIAH")
            ->setCellValue("L".($num+6), "Dept. Reasuransi Syariah")
        ;
        $activeSheet->getStyle("L".($num+1).":L".($num+2))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'bold' => true,
            ]
        ]);
        $activeSheet->getStyle("L{$num}:L".($num+6))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);
        // Rename worksheet
        // $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date('d-F-Y').'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        //header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $this->filter_polis_id='';$this->is_rekon=false;$this->check_id = [];
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },date('d-F-Y').'.xlsx');
    }
}
