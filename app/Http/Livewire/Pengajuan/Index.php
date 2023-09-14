<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected,$check_id=[],$is_pengajuan_reas=false,$is_pengajuan_memo_ujroh=false;
    public $filter_keyword,$filter_status_invoice,$filter_status,$start_tanggal_pengajuan,$end_tanggal_pengajuan,$start_tanggal_pembayaran,$end_tanggal_pembayaran;
    public $start_tanggal_akseptasi,$end_tanggal_akseptasi,$polis_pengajuan,$polis_id;
    public function render()
    {
        $data = Pengajuan::with(['polis','account_manager','reas'])
                ->orderBy('created_at','DESC');

        if($this->filter_keyword) $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('pengajuan') as $column){
                $table->orWhere($column,'LIKE',"%{$this->filter_keyword}%");
            }
        });
        if($this->filter_status_invoice) $data->where('status_invoice',$this->filter_status_invoice);
        if($this->filter_status) $data->where('status',$this->filter_status);
        if($this->start_tanggal_pengajuan and $this->end_tanggal_pengajuan){
            if($this->start_tanggal_pengajuan == $this->end_tanggal_pengajuan)
                $data->whereDate('created_at',$this->start_tanggal_pengajuan);
            else
                $data->whereBetween('created_at',[$this->start_tanggal_pengajuan,$this->end_tanggal_pengajuan]);
        }

        if($this->start_tanggal_pembayaran and $this->end_tanggal_pembayaran){
            if($this->start_tanggal_pembayaran == $this->end_tanggal_pembayaran)
                $data->whereDate('payment_date',$this->start_tanggal_pembayaran);
            else
                $data->whereBetween('payment_date',[$this->start_tanggal_pembayaran,$this->end_tanggal_pembayaran]);
        }

        if($this->start_tanggal_akseptasi and $this->end_tanggal_akseptasi){
            if($this->start_tanggal_akseptasi == $this->end_tanggal_akseptasi)
                $data->whereDate('head_syariah_submit',$this->start_tanggal_akseptasi);
            else
                $data->whereBetween('head_syariah_submit',[$this->start_tanggal_akseptasi,$this->end_tanggal_akseptasi]);
        }
        
        if($this->polis_id) $data->where('polis_id',$this->polis_id);

        $total_all = clone $data;
        $total_paid = clone $data;
        $total_unpaid = clone $data;

        // if($this->is_pengajuan_memo_ujroh) $data->where('status',3)->whereNull('');
        
        return view('livewire.pengajuan.index')->with([
                'data'=>$data->paginate(100),
                'total_all'=>$total_all->count(),
                'total_dn'=>$total_all->sum('net_kontribusi'),
                'total_dn_count'=> $total_all->whereNotNull('dn_number')->count(),
                'total_dn_paid'=>$total_paid->where('status_invoice',1)->sum('net_kontribusi'),
                'total_dn_unpaid'=>$total_unpaid->whereNotNull('dn_number')->where('status_invoice','0')->sum('net_kontribusi'),
            ]);
    }

    public function mount()
    {
        $this->polis_pengajuan = Pengajuan::select('polis.id','polis.nama')
                                    ->join('polis','polis.id','=','pengajuan.polis_id')
                                    ->groupBy('polis.id')->get();
    }

    public function clear_filter()
    {
        $this->reset(['filter_status_invoice','filter_keyword']);
    }

    public function set_id($id)
    {
        $this->selected = Pengajuan::find($id);
    } 

    public function submit_reas()
    {
        $this->emit('set_pengajuan',$this->check_id);
        $this->emit('modal_submit_reas');
    }

    public function delete()
    {
        Kepesertaan::where('pengajuan_id',$this->selected->id)->delete();

        $this->selected->delete();

        session()->flash('message-success',__('Pengajuan berhasil dihapus'));

        return redirect()->route('pengajuan.index');
    }

    public function set_memo_ujroh()
    {
        $this->is_pengajuan_memo_ujroh = true;
    }

    public function downloadExcel($data,$status=1)
    {
        $data = Pengajuan::find($data);
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("PMT System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Daftar Peserta")
                                    ->setKeywords("office 2007 openxml php");

        $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';
        if($status==1) $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';
        if($status==2) $title = 'DAFTAR KEPESERTAAN TERTUNDA ASURANSI JIWA KUMPULAN SYARIAH';

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A1', $title);
        $activeSheet->mergeCells("A1:O1");
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

        $activeSheet->setCellValue('B4', 'DEBIT NOTE NUMBER')
                    ->setCellValue('C4', " : ".$data->dn_number)
                    ->setCellValue('B5', 'NOMOR POLIS')
                    ->setCellValue('C5', " : ".$data->polis->no_polis)
                    ->setCellValue('B6', 'PEMEGANG POLIS')
                    ->setCellValue('C6', ' : '.(isset($data->polis->nama) ? $data->polis->nama : '-'))
                    ->setCellValue('B7', 'PRODUK ASURANSI')
                    ->setCellValue('C7', ' : '.(isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'));

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

        if($status==1){
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
        }

        if($status==2){
            $activeSheet
                    ->setCellValue('A8', 'NO')
                    ->setCellValue('B8', 'NAMA PESERTA')
                    ->setCellValue('C8', 'TGL. LAHIR')
                    ->setCellValue('D8', 'USIA')
                    ->setCellValue('E8', 'MULAI ASURANSI')
                    ->setCellValue('F8', 'AKHIR ASURANSI')
                    ->setCellValue('G8', 'NILAI MANFAAT ASURANSI')
                    ->setCellValue('H8', 'DANA TABBARU')
                    ->setCellValue('I8', 'DANA UJRAH')
                    ->setCellValue('J8', 'KONTRIBUSI')
                    ->setCellValue('K8', 'EXTRA MORTALITA')
                    ->setCellValue('L8', 'EXTRA KONTRIBUSI')
                    ->setCellValue('M8', 'TOTAL KONTRIBUSI')
                    ->setCellValue('N8', 'TGL STNC')
                    ->setCellValue('O8', 'UL')
                    ->setCellValue('P8', 'KET')
                    ;
            $activeSheet->getStyle("A8:P8")->applyFromArray([
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
                    ]);
        }

        if($status==3){
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
        }

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

        if($status==1){
            $k=0;
            foreach($data->kepesertaan->where('status_akseptasi',$status) as $k => $i){
                $k++;
                $activeSheet
                    ->setCellValue('A'.$num,$k)
                    ->setCellValue('B'.$num,$i->no_peserta)
                    ->setCellValue('C'.$num,$i->nama)
                    ->setCellValue('D'.$num,$i->tanggal_lahir?date('d-M-Y',strtotime($i->tanggal_lahir)) : '-')
                    ->setCellValue('E'.$num,$i->usia)
                    ->setCellValue('F'.$num,$i->tanggal_mulai?date('d-M-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('G'.$num,$i->tanggal_akhir?date('d-M-Y',strtotime($i->tanggal_akhir)) : '-')
                    ->setCellValue('H'.$num,$i->basic)
                    ->setCellValue('I'.$num,$i->dana_tabarru)
                    ->setCellValue('J'.$num,$i->dana_ujrah)
                    ->setCellValue('K'.$num,$i->kontribusi)
                    ->setCellValue('L'.$num,$i->extra_mortalita?$i->extra_mortalita:'-')
                    ->setCellValue('M'.$num,$i->extra_kontribusi?$i->extra_kontribusi:'-')
                    ->setCellValue('N'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('O'.$num,$i->tanggal_stnc?date('d-M-Y',strtotime($i->tanggal_stnc)) : '-')
                    ->setCellValue('P'.$num,$i->ul)
                    ->setCellValue('Q'.$num,$i->reason_reject);

                    $activeSheet->getStyle("H{$num}:K{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    if($i->extra_mortalita) $activeSheet->getStyle("L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                    if($i->extra_kontribusi) $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("A{$num}:Q{$num}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ]
                    ],
                ]);
                $num++;
            }

            $total_basic = $data->kepesertaan->where('status_akseptasi',$status)->sum('basic');
            $total_dana_tabarru = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_tabarru');
            $total_dana_ujrah = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_ujrah');
            $total_kontribusi = $data->kepesertaan->where('status_akseptasi',$status)->sum('kontribusi');
            $total_em = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_mortalita');
            $total_ek = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_kontribusi');

            $activeSheet
                        ->setCellValue("B{$num}",'TOTAL')
                        ->setCellValue("H{$num}",$total_basic)
                        ->setCellValue("I{$num}",$total_dana_tabarru)
                        ->setCellValue("J{$num}",$total_dana_ujrah)
                        ->setCellValue("K{$num}",$total_kontribusi)
                        ->setCellValue("L{$num}",$total_em)
                        ->setCellValue("M{$num}",$total_ek)
                        ->setCellValue("N{$num}",$total_kontribusi+$total_em+$total_ek);
            $activeSheet->getStyle("H{$num}:N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("A{$num}:Q{$num}")->applyFromArray([
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
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        if($status==2){
            $k=0;
            foreach($data->kepesertaan->where('status_akseptasi',$status) as $i){
                $k++;
                $activeSheet
                    ->setCellValue('A'.$num,$k)
                    ->setCellValue('B'.$num,$i->nama)
                    ->setCellValue('C'.$num,$i->tanggal_lahir?date('d-M-Y',strtotime($i->tanggal_lahir)) : '-')
                    ->setCellValue('D'.$num,$i->usia)
                    ->setCellValue('E'.$num,$i->tanggal_mulai?date('d-M-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('F'.$num,$i->tanggal_akhir?date('d-M-Y',strtotime($i->tanggal_akhir)) : '-')
                    ->setCellValue('G'.$num,$i->basic)
                    ->setCellValue('H'.$num,$i->dana_tabarru)
                    ->setCellValue('I'.$num,$i->dana_ujrah)
                    ->setCellValue('J'.$num,$i->kontribusi)
                    ->setCellValue('K'.$num,$i->extra_mortalita?$i->extra_mortalita:'-')
                    ->setCellValue('L'.$num,$i->extra_kontribusi?$i->extra_kontribusi:'-')
                    ->setCellValue('M'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('N'.$num,$i->tanggal_stnc?date('d-M-Y',strtotime($i->tanggal_stnc)) : '-')
                    ->setCellValue('O'.$num,$i->ul)
                    ->setCellValue('P'.$num,$i->reason_reject);

                if($i->extra_mortalita) $activeSheet->getStyle("K{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                if($i->extra_kontribusi) $activeSheet->getStyle("L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("G{$num}:J{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("A{$num}:P{$num}")->applyFromArray([
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
                ]);

                $num++;
            }

            $total_basic = $data->kepesertaan->where('status_akseptasi',$status)->sum('basic');
            $total_dana_tabarru = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_tabarru');
            $total_dana_ujrah = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_ujrah');
            $total_kontribusi = $data->kepesertaan->where('status_akseptasi',$status)->sum('kontribusi');
            $total_em = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_mortalita');
            $total_ek = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_kontribusi');

            $activeSheet
                        ->setCellValue("B{$num}",'TOTAL')
                        ->setCellValue("G{$num}",$total_basic)
                        ->setCellValue("H{$num}",$total_dana_tabarru)
                        ->setCellValue("I{$num}",$total_dana_ujrah)
                        ->setCellValue("J{$num}",$total_kontribusi)
                        ->setCellValue("K{$num}",$total_em)
                        ->setCellValue("L{$num}",$total_ek)
                        ->setCellValue("M{$num}",$total_kontribusi+$total_em+$total_ek);
            $activeSheet->getStyle("G{$num}:M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("A{$num}:P{$num}")->applyFromArray([
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
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        if($status==3){
            $k=0;
            foreach($data->kepesertaan->where('status_akseptasi',1) as $k => $i){
                $k++;
                $activeSheet
                    ->setCellValue('A'.$num,$k)
                    ->setCellValue('B'.$num,$i->no_peserta)
                    ->setCellValue('C'.$num,$i->nama)
                    ->setCellValue('D'.$num,$i->no_ktp)
                    ->setCellValue('E'.$num,$i->tanggal_lahir?date('d-M-Y',strtotime($i->tanggal_lahir)) : '-')
                    ->setCellValue('F'.$num,$i->usia)
                    ->setCellValue('G'.$num,$i->tanggal_mulai?date('d-M-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('H'.$num,$i->tanggal_akhir?date('d-M-Y',strtotime($i->tanggal_akhir)) : '-')
                    ->setCellValue('I'.$num,$i->basic)
                    ->setCellValue('J'.$num,$i->dana_tabarru)
                    ->setCellValue('K'.$num,$i->dana_ujrah)
                    ->setCellValue('L'.$num,$i->kontribusi)
                    ->setCellValue('M'.$num,$i->extra_mortalita?$i->extra_mortalita:'-')
                    ->setCellValue('N'.$num,$i->extra_kontribusi?$i->extra_kontribusi:'-')
                    ->setCellValue('O'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('P'.$num,$i->tanggal_stnc?date('d-M-Y',strtotime($i->tanggal_stnc)) : '-')
                    ->setCellValue('Q'.$num,$i->ul)
                    ->setCellValue('R'.$num,$i->reason_reject);

                    $activeSheet->getStyle("I{$num}:L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    if($i->extra_mortalita) $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                    if($i->extra_kontribusi) $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("A{$num}:R{$num}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ]
                    ],
                ]);
                $num++;
            }

            $total_basic = $data->kepesertaan->where('status_akseptasi',$status)->sum('basic');
            $total_dana_tabarru = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_tabarru');
            $total_dana_ujrah = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_ujrah');
            $total_kontribusi = $data->kepesertaan->where('status_akseptasi',$status)->sum('kontribusi');
            $total_em = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_mortalita');
            $total_ek = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_kontribusi');

            $activeSheet
                        ->setCellValue("C{$num}",'TOTAL')
                        ->setCellValue("I{$num}",$total_basic)
                        ->setCellValue("J{$num}",$total_dana_tabarru)
                        ->setCellValue("K{$num}",$total_dana_ujrah)
                        ->setCellValue("L{$num}",$total_kontribusi)
                        ->setCellValue("M{$num}",$total_em)
                        ->setCellValue("N{$num}",$total_ek)
                        ->setCellValue("O{$num}",$total_kontribusi+$total_em+$total_ek);
            $activeSheet->getStyle("I{$num}:O{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("A{$num}:R{$num}")->applyFromArray([
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
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        $num++;
        $num++;
        $num++;
        if($status==1){
            $activeSheet
                ->setCellValue('N'.$num,"Jakarta, ".date('d F Y'))
                ->setCellValue('N'.($num+4),"Underwriting Syariah");

            $activeSheet->getStyle("N".$num)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
            $activeSheet->getStyle("N".($num+4))->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font'=>[
                        'bold'=>true
                    ]
                ]);
            }else{
                $activeSheet
                        ->setCellValue('K'.$num,"Jakarta, ".date('d F Y'))
                        ->setCellValue('K'.($num+4),"Underwriting Syariah");

                    $activeSheet->getStyle("K".$num)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                    $activeSheet->getStyle("K".($num+4))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font'=>[
                                'bold'=>true
                            ]
                        ]);
            }

        // Rename worksheet
        $activeSheet->setTitle('Pengajuan');
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
