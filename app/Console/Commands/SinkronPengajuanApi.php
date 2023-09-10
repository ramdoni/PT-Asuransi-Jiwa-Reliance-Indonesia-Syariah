<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LogApiPan;
use App\Models\Kepesertaan;
use App\Models\Pengajuan;
use App\Models\Polis;
use App\Models\UnderwritingLimit;
use App\Models\RateBroker;

class SinkronPengajuanApi extends Command
{
    public $pengajuan_id = 10354;
    public $polis_id = 75,$polis; // 6012303000036 - PT. PROTEKSI ANTAR NUSA QQ PT. BANK RIAU KEPRI SYARIAH (PERSERODA)
    public $branch = [
        101 => 'Cabang Utama',
        102 => 'Cabang Tembilahan',
        103 => 'Cabang Tanjung Pinang',
        104 => 'Cabang Dumai',
        105 => 'Cabang Selat Panjang',
        106 => 'Cabang Batam',
        107 => 'Cabang Pekanbaru',
        108 => 'Cabang Bengkalis',
        109 => 'Cabang Bangkinang',
        110 => 'Cabang Air Molek',
        111 => 'Cabang Tanjung Balai Karimun',
        112 => 'Cabang Pangkalan Kerinci',
        113 => 'Cabang Bagan Siapi-api',
        114 => 'Cabang Taluk Kuantan',
        115 => 'Cabang Pasir Pangaraian',
        116 => 'Cabang Siak Sri Indrapura',
        117 => 'Cabang Ranai',
        118 => 'Capem Tangkerang',
        119 => 'Capem Rumbai',
        120 => 'Capem Senapelan',
        121 => 'Capem Perawang',
        122 => 'Capem Duri',
        123 => 'Capem Tanjung Batu',
        124 => 'Capem Sei Pakning',
        125 => 'Capem Dabo Singkep',
        128 => 'Capem Ujung Batu',
        129 => 'Capem Bagan Batu',
        130 => 'Capem Sorek',
        132 => 'Capem Lubuk Baja',
        133 => 'Capem Belilas',
        134 => 'Capem Panam',
        135 => 'Cabang Bintan',
        136 => 'Kedai Marpoyan',
        137 => 'Capem Kandis',
        138 => 'Capem Lipat Kain',
        139 => 'Capem Petapahan',
        140 => 'Kedai Pasar Sail',
        141 => 'Kedai Sungai Apit',
        142 => 'Kedai Pasar Air Tiris',
        143 => 'Kedai Pasar Kuok',
        144 => 'Capem Tuanku Tambusai',
        145 => 'Kedai Jalan Durian',
        146 => 'Capem Tanjung Uban',
        147 => 'Kedai Pasar Minas',
        148 => 'Capem Sei Guntung',
        149 => 'Capem Jl Riau',
        150 => 'Kedai Pasar Ukui',
        151 => 'Kedai Pasar Bukit Kapur',
        152 => 'Kedai Pasar Sedanau',
        153 => 'Capem Dalu-dalu',
        154 => 'Capem Kota Tengah',
        155 => 'Capem Baserah',
        156 => 'Kedai Pasar Pangkalan Kerinci',
        157 => 'Kedai Pasar Peranap',
        158 => 'Kedai Pasar Pinggir',
        159 => 'Kedai Pasar Sukaramai',
        160 => 'Capem Lubuk Dalam',
        161 => 'Capem Batu Aji',
        162 => 'Kedai Pasar Tanjung Pinang',
        163 => 'Kedai Dayun',
        164 => 'Kedai Kabun',
        165 => 'Capem Ahmad Yani',
        166 => 'Kedai Pasar Pagi Arengka',
        167 => 'Kedai Tanjung Samak',
        168 => 'Kedai Pasar Lubuk Jambi',
        169 => 'Capem Ujung Tanjung',
        170 => 'Capem Tarempa',
        171 => 'Kedai Pasar Rengat',
        172 => 'Kedai Sei Lala',
        173 => 'Kedai Muara Lembu',
        174 => 'Capem Daik Lingga',
        175 => 'Capem Kota Baru',
        176 => 'Kedai Kuala Kilan',
        177 => 'Kedai Pasar Tandun',
        178 => 'Capem Flamboyan',
        179 => 'Kedai Rantau Kasai',
        180 => 'Capem Bintan Center',
        181 => 'Kedai Batupanjang Rupat',
        182 => 'Kedai Meral',
        183 => 'Kedai Midai',
        184 => 'Kedai Serasan',
        185 => 'Kedai Teluk Belitung Merbau',
        186 => 'Capem Botania',
        187 => 'Kedai Bandar Sei Kijang',
        188 => 'Kedai Pujud',
        189 => 'Kedai Sabak Auh',
        190 => 'Kedai Sungai Sembilan',
        191 => 'Cabang Jakarta',
        820 => 'Cabang Syariah Pekanbaru',
        821 => 'Cabang Syariah Tanjung Pinang',
        822 => 'Capem Syariah Tembilahan',
        823 => 'Capem Syariah Duri',
        824 => 'Capem Syariah Batam',
        825 => 'Capem Syariah Teluk Kuantan',
        826 => 'Capem Syariah Panam',
        827 => 'Capem Syariah Tanjung Balai Karimun',
        828 => 'Capem Syariah Pasir Pangaraian',
        830 => 'Kedai Syariah Kubu',
        831 => 'Kedai Syariah Bantan'
    ];

    public $pekerjaan = [
        1 => 'Pegawai Negeri Sipil termasuk Pensiunan',
        2 => 'Pegawai BUMN / BUMD / Swasta termasuk Pensiunan',
        3 => 'Pengajar dan Dosen',
        4 => 'Pedagang',
        5 => 'Petani dan Nelayan',
        6 => 'Pengrajin / Buruh / Pembantu Rumah Tangga',
        7 => 'Pengurus dan Pegawai Yayasan / LSM / Organisasi',
        8 => 'Ulama / Pendeta / Pem Organisasi dan Kelompok Agama',
        9 => 'Pelajar dan Mahasiswa',
        10 => 'Profesional dan Konsultan',
        11 => 'Pengusaha dan Wiraswasta',
        12 => 'Lain - Lain',
        13 => 'Ibu Rumah Tangga',
        14 => 'Pengurus atau Anggota Partai Politik',
        15 => 'Pejabat atau Pegawai Penyedia Jasa Keuangan',
        16 => 'Kepala Negara / Wakil Kepala Negara / Menteri / Pejabat Setingkat',
        17 => 'Hakim Agung / Hakim / Hakim Konsti / Jaksa / Penitra',
        18 => 'Pemeriksa Bea Cukai / Pemeriksa Pajak / Auditor',
        19 => 'Kepala dan Wakil Pemerintahan TK I dan TK II',
        20 => 'Pejabat Ekse / Ketua dan Anggota Legislatif / Ketua Parpol TK I',
        21 => 'Pejabat Eselon I dan II / Kepala Dep Keuangan / Bea Cukai',
        22 => 'Pimpinan BI / Direksi dan Komisaris BUMN dan BUMD',
        23 => 'TNI / POLRI / Hakim / Jaksa / Penyidik / Pengadilan',
        24 => 'Pejabat pemberi Perizinan / Kepala Unit Masyarakat',
        25 => 'Pimpinan Perguruan Tinggi Negeri',
        26 => 'Pimpinan Proyek / Bendahara Proyek',
        27 => 'Teroris / Organisasi Teroris',
        28 => 'Anggota Dewan Gub BI / Anggota Dewan Komisaris OJK',
        29 => 'Dir RSUD A / B / Wakil Dir RSUDA/Dir RS Khusus A',
        30 => 'Anggota DPRD / Lembaga Sejenis di Daerah',
        31 => 'Anggota MPR / DPR / DPD',
        32 => 'Pihak yang terkait dengan PEP (Pegawai Kontrak/Honorer)',
        33 => 'PJBT, Peg, Petugas Bidang Perizinan, Pengadaan',
        34 => 'Eselon II Instansi Pemerintah / Lembaga Negara',
        35 => 'Eselon I dan Pejabat setara Pusat, Militer, POLRI',
        36 => 'Anggota Badan Pemeriksa Keuangan (BPK)',
        37 => 'Pejabat Sektor Migas, Mineral dan Batu Bara',
        38 => 'Pejabat Pembuat Regulasi',
        39 => 'Anggota Komisi Yudisial / Dewan Pertimbangan Presiden',
        40 => 'Komnas HAM / KPK / KPI / PPU / KPAI / Komisi sesuai UU',
        41 => 'Duta Besar',
        42 => 'Pegawai Negeri Sipil termasuk Pensiunan (Top up)',
        43 => 'Karyawan Internal Bank Riau Kepri',
    ];

    public $jenis_pembiayaan = [
        111 => 'Murabahah non KUK Konsumsi',
        112 => 'Murabahah non KUK Konsumsi ANN',
        113 => 'Murabahah Konsumsi Pegawai BRS',
        114 => 'Murabahah Kepemilikan Emas',
        122 => 'Murabahah non KUK Konsumsi Flat LS',
        123 => 'Murabahah non KUK Konsumsi Annuitas LS',
        301 => 'Kredit Aneka Guna Plus',
        306 => 'Kredit KPR Bank Riau / Anuitas',
        307 => 'Kredit KPR Bank Riau / Flat',
        311 => 'Kredit KKB Roda Dua (Flat)',
        312 => 'Kredit KKB Roda Empat (Flat)',
        321 => 'Kredit KAG - Efektif Anuitas',
        322 => 'Kredit Aneka Guna (Menurun)',
        323 => 'KAG Electro',
        326 => 'Kredit Karyawan Bank Riau',
        327 => 'Kredit KAG Pra Pensiun',
        331 => 'Kredit Eks Kartu Kredit',
        332 => 'Eks Kartu Kredit',
        355 => 'MMQ Konsumtif',
        358 => 'MMQ Konsumtif Pegawai BRK',
        359 => 'MMQ Konsumtif LS',
        401 => 'Ijarah',
        404 => 'Pembiayaan Gadai / Rahn Emas',
        405 => 'Ijarah Multi Jasa Umrah',
        406 => 'Ijarah Multi Jasa Lainnya',
        408 => 'Ijarah Flat LS',
        410 => 'Ijarah Multi Jasa Lainnya LS',
        411 => 'PEMBIAYAAN GADAI/RAHN EMAS LS',
        606 => 'Kredit KPR BRK Anuitas Restruk',
        607 => 'KAG Efektif Anuitas Restruk',
        610 => 'KPR Bank Riau / Flat Restruk',
        674 => 'RECVD Kredit KPR Bank Riau / Anuits',
        675 => 'RECVD Kredit KPR Bank Riau / Flat',
        678 => 'RECVD Kredit KAG Efektif Anuitas',
        755 => 'RECVD MMQ Konsumtif',
        801 => 'RECVD Ijarah'
    ];
    public $jenis_pengajuan = [
        1 => 'CAC / Free Cover',
        2 => 'CBC (Case By Case)'
    ];
    public $benefit = [
        1 => 'All Cover',
        2 => 'Jiwa'
    ];
    public $packet = [
        '01' => 'Karyawan Bank Riaukepri (PA+ND)',
        '02' => 'Karyawan Bank Riaukepri (PA+ND+PHK)',
        '03' => 'Karyawan Bank Riaukepri (PA+ND+PHK+WP)',
        '04' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI (PA+ND)',
        '05' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI (PA+ND+PHK)',
        '06' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI PA+ND+PHK+WP)',
        '07' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND)',
        '08' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND+PHK)',
        '09' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND+PHK+WP)',
        '10' => 'Wiraswasta Profesional (PA+ND)',
        '11' => 'DPRD (PAW)',
        '12' => 'PENSIUNAN',
        '13' => 'PRAPENSIUN',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:pengajuan-api';

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
        $log = LogApiPan::whereMonth('created_at',8)->whereYear('created_at',2023)->get();

        /**
         * 
         * Running Number Peserta : 807
         * 
         * 
         * {"id_transaksi":"3214171604","id_pengajuan":"PPOL-0014787","kode_broker":"05","kode_cabang":"186","nomor_akad":"0000140.3.15.2023.186","nomor_rekening":"1868014023","nama":"EGIE APRIANDI","ktp":"2171060504999008","npwp":"842853541223000","jenis_kelamin":"L","pekerjaan":"23","tgl_lahir":"1999-04-05","tgl_buka":"2023-08-28","tenor":"120","jenis_Pembiayaan":"332","jenis_pengajuan":"1","plafond":"250000000","bunga":"26.88","premi_yang_dibayarkan":"6720000","benefit":"1","packet":"06"}
         * 
         */
        foreach($log as $k => $json){
            $item = json_decode($json->request);
            if(isset($item->id_transaksi)){

                $period = (int)($item->tenor  / 12);
                // check rate
                $rate_broker = RateBroker::where(['packet'=>$item->packet,'period'=>$period])->first();
                $usia =  hitung_umur(date('Y-m-d',strtotime($item->tgl_lahir)));

                $uw = UnderwritingLimit::whereRaw("{$item->plafond} BETWEEN min_amount and max_amount")->where(['usia'=>$usia,'polis_id'=>$this->polis_id])->first();
                
                $validate = [];

                foreach($item as $key => $val){
                    $validate[$key] = $val;
                }

                // $validator = \Validator::make($r->all(), 
                $validator = \Validator::make($validate, 
                        [ 
                            'id_transaksi'=>'required',
                            'id_pengajuan'=>'required',
                            'kode_broker'=>'required',
                            'kode_cabang'=>'required',
                            'nomor_akad'=>'required',
                            'nomor_rekening'=>'required',
                            'nama'=>'required',
                            'ktp'=>'required',
                            'npwp'=>'required',
                            'jenis_kelamin'=>'required',
                            'pekerjaan'=>'required',
                            'tgl_lahir'=>'required',
                            'tgl_buka'=>'required',
                            'tenor'=>'required',
                            'jenis_Pembiayaan'=>'required',
                            'jenis_pengajuan'=>'required',
                            'plafond'=>'required',
                            'bunga'=>'required',
                            'premi_yang_dibayarkan'=>'required',
                            'benefit'=>'required',
                            'packet'=>'required'
                        ]);

                if ($validator->fails()) {
                    echo "1. {$item->nama} Pengajuan Polis Asuransi Gagal\n";
                    continue;
                }
                
                if($rate_broker==""){
                    echo "2. {$item->nama} Pengajuan Polis Asuransi Gagal, Rate tidak ditemukan\n";
                    continue;
                }

                if(!$uw) {
                    $uw = UnderwritingLimit::where(['usia'=>$usia,'polis_id'=>$this->polis_id])->orderBy('max_amount','ASC')->first();
                }
                if(!$uw){
                    echo "3. {$item->nama} Mohon melengkapi SPK dan Copy KTP\n";
                    continue;
                }

                if($uw->keterangan=='NM' || $uw->keterangan=="") {
                    echo "4. {$item->nama} Mohon melengkapi SPK dan Copy KTP\n";
                    continue;
                }

                if(in_array($uw->keterangan,['A','B','C','D','E','E + FS'])){
                    echo "4. {$item->nama} Mohon melengkapi hasil medis {$uw->keterangan}\n";
                    continue;
                }

                // check no pengajuan
                $pengajuan = Pengajuan::find($this->polis_id);
            
                $peserta = Kepesertaan::where(['pengajuan_id'=>$this->pengajuan_id,'no_ktp'=>$item->ktp])->first();
                if(!$peserta){
                    $peserta = new Kepesertaan();
                    $peserta->tanggal_mulai = date('Y-m-d',strtotime($item->tgl_buka));
                    $peserta->tanggal_akhir = date('Y-m-d',strtotime("+{$item->tenor} months",strtotime($item->tgl_buka)));
                    $peserta->polis_id = $this->polis_id;
                    $peserta->pengajuan_id = $this->pengajuan_id;
                    $peserta->is_temp = 1;
                    $peserta->status_polis = 'Akseptasi';
                    $peserta->cab = $item->kode_cabang;
                    $peserta->no_akad_kredit = $item->nomor_akad;
                    $peserta->nomor_rekening = $item->nomor_rekening;
                    $peserta->nama = $item->nama;
                    $peserta->no_ktp = $item->ktp;
                    $peserta->npwp = $item->npwp;
                    $peserta->jenis_kelamin = $item->jenis_kelamin=='L' ? 'Laki-laki' : 'Perempuan';
                    $peserta->pekerjaan = @$this->pekerjaan[$item->pekerjaan];
                    $peserta->tanggal_lahir = date('Y-m-d',strtotime($item->tgl_lahir));
                    $peserta->masa_bulan = $item->tenor;
                    $peserta->jenis_pembiayaan = @$this->jenis_pembiayaan[$item->jenis_Pembiayaan];
                    $peserta->jenis_pengajuan = @$this->jenis_pengajuan[$item->jenis_pengajuan];
                    $peserta->basic = $item->plafond;
                    $peserta->bunga = $item->bunga;
                    $peserta->kontribusi = $item->plafond*($rate_broker->ajri/1000);
                    $peserta->benefit = @$this->benefit[$item->benefit];
                    $peserta->packet = @$this->packet[$item->packet];    
                    $peserta->usia = $usia;
                    $peserta->rate = $rate_broker->ajri;
                    $peserta->ari_kontribusi = 0;
                    $peserta->ari_rate = $rate_broker->ari;
                    $peserta->status_akseptasi =  0;
                    $peserta->save();
                    
                    $peserta->ul = $uw->keterangan;

                    $polis = Polis::find($this->polis_id);

                    $running_number = $polis->running_number_peserta+1;
                    $no_peserta = (isset($polis->produk->id) ? $polis->produk->id : '0') ."-". date('ym').str_pad($running_number,7, '0', STR_PAD_LEFT).'-'.str_pad($polis->running_number,3, '0', STR_PAD_LEFT);
                    $peserta->no_peserta = $no_peserta;
                    $peserta->save();
                    
                    // save running number
                    $polis->running_number_peserta = $running_number;
                    $polis->save();

                    echo "{$k}.".$item->id_transaksi ."\n";
                }
            }
        }
    }
}
