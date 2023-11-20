<?php

namespace App\Http\Livewire\RecoveryClaim;

use Livewire\Component;
use App\Models\RecoveryClaim;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$is_download=false,$filter=[],$is_rekon=false,$check_id=[],$polis=[],$filter_polis_id;

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
        $data = RecoveryClaim::with(['polis','kepesertaan','klaim'])->orderBy('id','DESC');
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
        ]);;

        $activeSheet->setCellValue('A6', 'Tipe Bisnis')
                    ->setCellValue('C6', " : ")
                    ->setCellValue('A7', 'Mata Uang')
                    ->setCellValue('C7', " : ")
                    ->setCellValue('A8', 'Plan')
                    ->setCellValue('C8', " : ")
                    ->setCellValue('A9', 'Perusahaan')
                    ->setCellValue('C9', " : ")
                    ->setCellValue('A10', 'Nama Pemegang Polis')
                    ->setCellValue('C10', " : ")
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
                ->setCellValue('A8', 'NO')
                ->setCellValue('B8', 'NO PESERTA')
                ->setCellValue('C8', 'NAMA PESERTA')
                ->setCellValue('D8', 'TGL. LAHIR')
                ->setCellValue('E8', 'USIA')
                ->setCellValue('F8', 'MULAI ASURANSI')
                ->setCellValue('G8', 'AKHIR ASURANSI')
                ->setCellValue('H8', 'NILAI MANFAAT ASURANSI')
                ->setCellValue('I8', 'DANA TABBARU')
                ->setCellValue('J8', 'DANA UJRAH')
                ->setCellValue('K8', 'KONTRIBUSI')
                ->setCellValue('L8', 'EXTRA MORTALITA')
                ->setCellValue('M8', 'EXTRA KONTRIBUSI')
                ->setCellValue('N8', 'TOTAL KONTRIBUSI')
                ->setCellValue('O8', 'TGL STNC')
                ->setCellValue('P8', 'UL')
                ->setCellValue('Q8', 'KET');

        $activeSheet->getStyle("A8:Q8")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
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
                ]);


        $activeSheet
                ->setCellValue('A8', 'NO')
                ->setCellValue('B8', 'NO PESERTA')
                ->setCellValue('C8', 'NAMA PESERTA')
                ->setCellValue('D8', 'NO KTP')
                ->setCellValue('E8', 'TGL. LAHIR')
                ->setCellValue('F8', 'USIA')
                ->setCellValue('G8', 'MULAI ASURANSI')
                ->setCellValue('H8', 'AKHIR ASURANSI')
                ->setCellValue('I8', 'NILAI MANFAAT ASURANSI')
                ->setCellValue('J8', 'DANA TABBARU')
                ->setCellValue('K8', 'DANA UJRAH')
                ->setCellValue('L8', 'KONTRIBUSI')
                ->setCellValue('M8', 'EXTRA MORTALITA')
                ->setCellValue('N8', 'EXTRA KONTRIBUSI')
                ->setCellValue('O8', 'TOTAL KONTRIBUSI')
                ->setCellValue('P8', 'TGL STNC')
                ->setCellValue('Q8', 'UL')
                ->setCellValue('R8', 'KET');

        $activeSheet->getStyle("A8:R8")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
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
                ]);
        

        $activeSheet->getColumnDimension('A')->setWidth(5);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);
        $activeSheet->getColumnDimension('J')->setAutoSize(true);
        $activeSheet->getColumnDimension('K')->setAutoSize(true);
        $activeSheet->getColumnDimension('L')->setAutoSize(true);
        $activeSheet->getColumnDimension('M')->setAutoSize(true);
        $activeSheet->getColumnDimension('N')->setAutoSize(true);
        $activeSheet->getColumnDimension('O')->setAutoSize(true);
        $activeSheet->getColumnDimension('P')->setAutoSize(true);
        $activeSheet->getColumnDimension('Q')->setAutoSize(true);
        $activeSheet->getColumnDimension('R')->setAutoSize(true);
        $num=9;

       

        // Rename worksheet
        // $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$data->no_pengajuan.'.xlsx"');
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
        },$data->no_pengajuan.'.xlsx');
    }
}
