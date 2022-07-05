<?php

namespace App\Http\Livewire\Polis;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Produk;
use App\Models\Provinsi;
use App\Models\Rate;
use Livewire\WithFileUploads;

class Insert extends Component
{
    public $no_polis,$nama,$provinsi_id,$alamat,$produks=[],$provinsi=[],$produk_id,$awal,$akhir,$keterangan,$status;
    public $masa_leluasa,$kadaluarsa_klaim,$pemulihan_kepesertaan,$reasuradur_id,$kelengkapan_berkas,$penyelesaian_perselisihan;
    public $iuran_tabbaru,$ujrah_atas_pengelolaan,$nisbah_hasil_investasi_peserta,$nisbah_hasil_investasi_pengelolaan,$surplus_uw_tabbaru;
    public $usia_minimal,$tipe,$model,$rate_persen,$ri_com,$ketentuan_uw_reas,$stnc,$kadaluarsa_reas,$no_perjanjian_reas,$perkalian_biaya_penutupan;
    public $rates=[],$reasuradur=[],$rate,$potong_langsung,$fee_base_brokerage,$maintenance,$admin_agency,$agen_penutup,$operasional_agency;
    public $ujroh_handling_fee_broker,$referal_fee,$pph,$ppn,$tujuan_pembayaran_nota_penutupan,$no_rekening,$bank,$tujuan_pembayaran_update;
    public $pks,$produksi_kontribusi,$surat_permohonan_tarif_kontribusi,$fitur_produk,$tabel_rate_premi,$spajks,$spajks_sementara,$copy_ktp;
    public $copy_npwp,$npwp,$copy_siup,$nota_penutupan,$tujuan_pembayaran_nama_penerima_refund,$bank_refund,$no_rekening_refund,$tujuan_pengiriman_surat;
    public $mcu_dicover_ajri,$kabupaten_id,$kode_kabupaten,$ket_diskon,$sektor_ekonomi,$mitra_pengimbang,$kerjasama_pemasaran,$asuransi_mikro,$pic_marketing;
    public $dc_aaji,$dc_ojk,$office,$channel,$segment,$line_of_business,$source_of_business,$no_nota_penutupan,$no_perjanjian_kerjasama,$peninjauan_ulang,$pembayaran_klaim;
    public $retroaktif,$waiting_period,$rate_single_usia,$total_bp,$no_sb,$uw_limit,$margin_rate,$ri_comm,$share_reinsurance,$lost_ratio,$profit_margin,$contingency_margin,$business_source;
    public $refund,$refund_to_pengalihan,$dana_tabbaru_reas,$dana_ujroh_reas,$stop_loss,$cut_loss,$refund_cut_loss;
    use WithFileUploads;

    public function render()
    {
        return view('livewire.polis.insert');
    }

    public function mount()
    {
        $this->rates = Rate::get();
        $this->produks = Produk::get();
        $this->provinsi = Provinsi::orderBy('nama','ASC')->get();
        $this->no_polis = date('ym').str_pad(Polis::count()+1,6, '0', STR_PAD_LEFT);
    }

    public function updated($propertyName)
    {
        if($this->produk_id)  $this->no_polis = $this->produk_id.date('ym').str_pad(Polis::where('produk_id',$this->produk_id)->count()+1,6, '0', STR_PAD_LEFT);
        if($propertyName =='iuran_tabbaru' and $this->iuran_tabbaru > 0) 
        $this->ujrah_atas_pengelolaan = 100 - $this->iuran_tabbaru;
    }

    public function save()
    {
        $this->validate([
            'no_polis'=>'required',
            'nama'=>'required',
            'provinsi_id'=>'required',
            'alamat'=>'required',
            'ketentuan_uw_reas'=>'required|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
            'line_of_business' => 'required',
            'iuran_tabbaru' => 'required'
        ]);

        $data = new Polis();
        $data->no_polis = $this->no_polis;
        $data->nama = $this->nama;
        $data->provinsi_id = $this->provinsi_id;
        $data->alamat = $this->alamat;
        $data->produk_id = $this->produk_id;
        $data->awal = $this->awal;
        $data->akhir = $this->akhir;
        $data->rate = $this->rate;
        $data->keterangan = $this->keterangan;
        $data->status = $this->status;
        $data->masa_leluasa = $this->masa_leluasa;
        $data->kelengkapan_berkas = $this->kelengkapan_berkas;
        $data->kadaluara_klaim = $this->kadaluarsa_klaim;
        $data->pemulihan_kepesertaan = $this->pemulihan_kepesertaan;
        $data->penyelesaian_perselisihan = $this->penyelesaian_perselisihan;
        $data->iuran_tabbaru = $this->iuran_tabbaru;
        $data->ujrah_atas_pengelolaan = $this->ujrah_atas_pengelolaan;
        $data->nisbah_hasil_investasi_peserta = $this->nisbah_hasil_investasi_peserta;
        $data->nisbah_hasil_investasi_pengelolaan = $this->nisbah_hasil_investasi_pengelolaan;
        $data->surplus_uw_tabbaru = $this->surplus_uw_tabbaru;
        $data->surplus_uw_peserta = $this->surplus_uw_peserta;
        $data->surplus_uw_pengelola = $this->surplus_uw_pengelola;
        $data->usia_minimal = $this->usia_minimal;
        $data->reasuradur_id = $this->reasuradur_id;
        $data->tipe = $this->tipe;
        $data->model = $this->model;
        $data->rate_persen = $this->rate_persen;
        $data->ri_com = $this->ri_com;
        $data->stnc = $this->stnc;
        $data->kadaluarsa_reas  = $this->kadaluarsa_reas;
        $data->no_perjanjian_reas  = $this->no_perjanjian_reas;
        $data->perkalian_biaya_penutupan  = $this->perkalian_biaya_penutupan;
        $data->potong_langsung = $this->potong_langsung;
        $data->fee_base_brokerage = $this->fee_base_brokerage;
        $data->maintenance = $this->maintenance;
        $data->admin_agency = $this->admin_agency;
        $data->agen_penutup = $this->agen_penutup;
        $data->operasional_agency = $this->operasional_agency;
        $data->ujroh_handling_fee_broker = $this->ujroh_handling_fee_broker;
        $data->referal_fee = $this->referal_fee;
        $data->pph = $this->pph;
        $data->ppn = $this->ppn;
        $data->tujuan_pembayaran_nota_penutupan = $this->tujuan_pembayaran_nota_penutupan; 
        $data->no_rekening = $this->no_rekening;
        $data->bank = $this->bank;
        $data->tujuan_pembayaran_update = $this->tujuan_pembayaran_update;
        $data->pks = $this->pks;
        $data->produksi_kontribusi = $this->produksi_kontribusi;
        $data->surat_permohonan_tarif_kontribusi = $this->surat_permohonan_tarif_kontribusi;
        $data->fitur_produk = $this->fitur_produk;
        $data->tabel_rate_premi = $this->tabel_rate_premi;
        $data->spajks = $this->spajks;
        $data->spajks_sementara = $this->spajks_sementara;
        $data->copy_ktp = $this->copy_ktp;
        $data->copy_npwp = $this->copy_npwp;
        $data->npwp = $this->npwp;
        $data->copy_siup = $this->copy_siup;
        $data->nota_penutupan = $this->nota_penutupan;
        $data->tujuan_pembayaran_nama_penerima_refund = $this->tujuan_pembayaran_nama_penerima_refund;
        $data->bank_refund = $this->bank_refund;
        $data->no_rekening_refund = $this->no_rekening_refund;
        $data->tujuan_pengiriman_surat = $this->tujuan_pengiriman_surat;
        $data->mcu_dicover_ajri = $this->mcu_dicover_ajri;
        $data->kabupaten_id = $this->kabupaten_id;
        $data->kode_kabupaten = $this->kode_kabupaten;
        $data->ket_diskon = $this->ket_diskon;
        $data->sektor_ekonomi = $this->sektor_ekonomi;
        $data->mitra_pengimbang = $this->mitra_pengimbang;
        $data->kerjasama_pemasaran = $this->kerjasama_pemasaran;
        $data->asuransi_mikro = $this->asuransi_mikro;
        $data->pic_marketing = $this->pic_marketing;
        $data->dc_aaji = $this->dc_aaji;
        $data->dc_ojk = $this->dc_ojk;
        $data->office = $this->office;
        $data->channel = $this->channel;
        $data->segment = $this->segment;
        $data->line_of_business = $this->line_of_business;
        $data->source_of_business = $this->source_of_business;
        $data->no_nota_penutupan = $this->no_nota_penutupan;
        $data->no_perjanjian_kerjasama = $this->no_perjanjian_kerjasama;
        $data->peninjauan_ulang = $this->peninjauan_ulang;
        $data->pembayaran_klaim = $this->pembayaran_klaim;
        $data->retroaktif = $this->retroaktif;
        $data->waiting_period = $this->waiting_period;
        $data->rate_single_usia - $this->rate_single_usia;
        $data->total_bp = $this->total_bp;
        $data->no_sb = $this->no_sb;
        $data->uw_limit = $this->uw_limit;
        $data->margin_rate = $this->margin_rate;
        $data->ri_comm = $this->ri_comm;
        $data->share_reinsurance = $this->share_reinsurance;
        $data->lost_ratio = $this->lost_ratio;
        $data->profit_margin = $this->profit_margin;
        $data->contingency_margin = $this->contingency_margin;
        $data->business_source = $this->business_source;
        $data->refund = $this->refund;
        $data->refund_to_pengalihan = $this->refund_to_pengalihan;
        $data->dana_tabbaru_reas = $this->dana_tabbaru_reas;
        $data->dana_ujroh_reas = $this->dana_ujroh_reas;
        $data->stop_loss = $this->stop_loss;
        $data->cut_loss = $this->cut_loss;
        $data->refund_cut_loss = $this->refund_cut_loss;
        $data->save(); 

        if($this->ketentuan_uw_reas){
            $doc = 'ketentuan_uw_reas.'.$this->ketentuan_uw_reas->extension();
            $this->ketentuan_uw_reas->storePubliclyAs("public/polis/{$data->id}",$doc);
            $data->ketentuan_uw_reas ="storage/polis/{$data->id}/{$doc}";
            $data->save();
        }

        $this->emit('modal','hide');
        $this->emit('reload-page');
    }
}
