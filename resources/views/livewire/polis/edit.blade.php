@section('sub-title', $data->no_polis)
@section('title', 'Pemegang Polis')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <ul class="nav nav-tabs-new2">
                <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#profil">{{ __('Polis') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kepesertaan">{{ __('Kepesertaan') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active show" id="profil">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No Polis </label> : {{$no_polis}}
                            </div>
                            <div class="form-group">
                                <label>Nama Pemegang Polis <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="nama" />
                                @error('nama')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Provinsi <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="provinsi_id">
                                    <option value="">-- Pilih --</option>
                                    @foreach($provinsi as $item)
                                        <option value="{{$item->id}}">{{$item->nama}}</option>
                                    @endforeach
                                </select>
                                @error('provinsi_id')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="alamat" />
                                @error('alamat')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Produk <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="produk_id">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach($produks as $item)
                                            <option value="{{$item->id}}">{{$item->singkatan}} / {{$item->nama}} / {{$item->klasifikasi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Rate</label>
                                    <input type="text" wire:model="rate" class="form-control" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>Awal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="awal">
                                    @error('awal')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label>Akhir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="akhir">
                                    @error('akhir')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Keterangan</label>
                                    <select class="form-control" wire:model="keterangan">
                                        <option value="">-- pilih --</option>
                                        <option value="New">New</option>
                                        <option value="Renewal">Renewal</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <select class="form-control" wire:model="status">
                                        <option value="">-- pilih --</option>
                                        <option value="Inforce">Inforce</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Masa Leluasa (Grace Periode) *hari</label>
                                <input type="number" class="form-control" wire:model="masa_leluasa" placeholder="Hari Kalender" />
                            </div>
                            <div class="form-group">
                                <label>Kelengkapan Berkas Manfaat Asuransi</label>
                                <input type="number" class="form-control" wire:model="kelengkapan_berkas" placeholder="Hari Kalender" />
                            </div>
                            <div class="form-group">
                                <label>Kadaluarsa Klaim *hari</label>
                                <input type="number" class="form-control" wire:model="kadaluarsa_klaim" placeholder="Hari Kalender" />
                            </div>
                            <div class="form-group">
                                <label>Pemulihan Kepesertaan Asuransi *hari</label>
                                <input type="number" class="form-control" wire:model="pemulihan_kepesertaan" placeholder="Hari Kalender" />
                            </div>
                            <div class="form-group">
                                <label>Penyelesaian Perselisihan *hari</label>
                                <input type="number" class="form-control" wire:model="penyelesaian_perselisihan" placeholder="Hari Kalender" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Iuran Tabbaru <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="iuran_tabbaru" />
                                    @error('iuran_tabbaru')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ujrah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="ujrah_atas_pengelolaan" placeholder="Ujrah Atas Pengelolaan Polis untuk Pengelola" readonly />    
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nisbah Hasil Investasi (Peserta)</label>
                                <input type="text" class="form-control" wire:model="nisbah_hasil_investasi_peserta" />
                            </div>
                            <div class="form-group">
                                <label>Nisbah Hasil Investasi (Pengelolaan)</label>
                                <input type="text" class="form-control" wire:model="nisbah_hasil_investasi_pengelolaan" />
                            </div>
                            <div class="form-group">
                                <label>Surplus Underwriting (Dana Tabbaru)</label>
                                <input type="text" class="form-control" wire:model="surplus_uw_tabbaru" />
                            </div>
                            <div class="form-group">
                                <label>Surplus Underwriting (Peserta)</label>
                                <input type="text" class="form-control" wire:model="surplus_uw_peserta" />
                            </div>
                            <div class="form-group">
                                <label>Surplus Underwriting (Pengelola)</label>
                                <input type="text" class="form-control" wire:model="surplus_uw_pengelola" />
                            </div>
                            <div class="form-group">
                                <label>Usia Minimal Kepesertaan Asuransi</label>
                                <input type="number" class="form-control" wire:model="usia_minimal" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Reasuradur</label>
                                    <select class="form-control" wire:model="reasuradur_id">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach($reasuradur as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tipe</label>
                                    <select class="form-control" wire:model="tipe">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach(['Fakultatif','OR','Treaty'] as $item)
                                            <option>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" class="form-control" wire:model="model" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Rate (%)</label>
                                    <input type="text" class="form-control" wire:model="rate_persen" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>RI COM (%)</label>
                                    <input type="text" class="form-control" wire:model="ri_com" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Ketentuan UW Reas</label>
                                <input type="file" class="form-control" wire:model="ketentuan_uw_reas" />
                            </div>
                            <div class="form-group">
                                <label>STNC</label>
                                <input type="text" class="form-control" wire:model="stnc" />
                            </div>
                            <div class="form-group">
                                <label>Kadaluarsa Reas</label>
                                <input type="text" class="form-control" wire:model="kadaluarsa_reas" />
                            </div>
                            <div class="form-group">
                                <label>No Perjanjian Reas</label>
                                <input type="text" class="form-control" wire:model="no_perjanjian_reas" />
                            </div>
                            <div class="form-group">
                                <label>Perkalian Biaya Penutupan</label>
                                <select class="form-control" wire:model="perkalian_biaya_penutupan">
                                    <option value=""> -- Pilih -- </option>
                                    <option>Kontribusi Gross</option>
                                    <option>Kontribusi Dibayar</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Potong Langsung (%)</label>
                                <input type="text" class="form-control" wire:model="potong_langsung" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            
                            <div class="form-group">
                                <label>Fee Base / Brokerage (%)</label>
                                <input type="text" class="form-control" wire:model="fee_base_brokerage" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Maintenance (%)</label>
                                    <input type="text" class="form-control" wire:model="maintenance" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Admin Agency (%)</label>
                                    <input type="text" class="form-control" wire:model="admin_agency" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Agen Penutup (%)</label>
                                    <input type="text" class="form-control" wire:model="agen_penutup" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Operasional Agency (%)</label>
                                    <input type="text" class="form-control" wire:model="operasional_agency" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Ujroh Handling Fee (%)</label>
                                <input type="text" class="form-control" wire:model="ujroh_handling_fee_broker" />
                            </div>
                            <div class="form-group">
                                <label>Referal Fee (%)</label>
                                <input type="text" class="form-control" wire:model="referal_fee" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>PPh (%)</label>
                                    <input type="text" class="form-control" wire:model="pph" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>PPN (%)</label>
                                    <input type="text" class="form-control" wire:model="ppn" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tujuan Pembayaran Nota Penutupan</label>
                                <input type="text" class="form-control" wire:model="tujuan_pembayaran_nota_penutupan" />
                            </div>
                            <div class="form-group">
                                <label>No Rekening</label>
                                <input type="text" class="form-control" wire:model="no_rekening" />
                            </div>
                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" class="form-control" wire:model="bank" />
                            </div>
                            <div class="form-group">
                                <label>Tujuan Pembayaran Update</label>
                                <input type="text" class="form-control" wire:model="tujuan_pembayaran_update" />
                            </div>
                            <div class="form-group">
                                <label>PKS</label>
                                <input type="checkbox" wire:model="pks" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Produksi Kontribusi</label>
                                <input type="checkbox" wire:model="produksi_kontribusi" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Surat Permohonan Tarif Kontribusi</label>
                                <input type="checkbox" wire:model="surat_permohonan_tarif_kontribusi" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Fitur Produk</label>
                                <input type="checkbox" wire:model="fitur_produk" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Tabel Rate Premi</label>
                                <input type="checkbox" wire:model="tabel_rate_premi" value="1" />
                            </div>
                            <div class="form-group">
                                <label>SPAJKS (Surat Permohonan Asuransi Jiwa Kumpulan Syariah)</label>
                                <input type="checkbox" wire:model="spajks" value="1" />
                            </div>
                            <div class="form-group">
                                <label>SPAJKS Sementara</label>
                                <input type="checkbox" wire:model="spajks_sementara" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Copy KTP</label>
                                <input type="checkbox" wire:model="copy_ktp" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Copy NPWP</label>
                                <input type="checkbox" wire:model="copy_npwp" value="1" />
                            </div>
                            <div class="form-group">
                                <label>NPWP</label>
                                <input type="text" class="form-control" wire:model="npwp" />
                            </div>
                            <div class="form-group">
                                <label>Copy Siup / No. Ijin usaha</label>
                                <input type="checkbox" wire:model="copy_siup" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Nota Penutupan</label>
                                <input type="checkbox" wire:model="nota_penutupan" value="1" />
                            </div>
                            <div class="form-group">
                                <label>Tujuan Pembayaran / Nama Penerima Refund</label>
                                <input type="text" class="form-control" wire:model="tujuan_pembayaran_nama_penerima_refund" />
                            </div>
                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" class="form-control" wire:model="bank_refund" />
                            </div>
                            <div class="form-group">
                                <label>No. Rekening</label>
                                <input type="text" class="form-control" wire:model="no_rekening_refund" />
                            </div>
                            <div class="form-group">
                                <label>MCU dicover Ajri</label>
                                <input type="text" class="form-control" wire:model="mcu_dicover_ajri" />
                            </div>
                            <div class="form-group">
                                <label>Kabupaten</label>
                                <select class="form-control" wire:model="kabupaten_id">
                                    <option value=""> -- Pilih -- </option>
                                    @if($provinsi_id)
                                        @foreach(\App\Models\Kabupaten::where('provinsi_id',$provinsi_id)->get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kode Kabupaten</label>
                                <input type="text" class="form-control" wire:model="kode_kabupaten" />
                            </div>
                            <div class="form-group">
                                <label>Cabang Pemasaran</label>
                                <select class="form-control" wire:model="cabang_pemasaran">
                                    <option value=""> -- Pilih -- </option>
                                    @foreach($provinsi as $item)
                                        <option value="{{$item->id}}">{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Ket Diskon/HF di Memo</label>
                                <select class="form-control" wire:model="ket_diskon">
                                    <option value=""> -- pilih -- </option>
                                    <option>Diskon</option>
                                    <option>Handling Fee</option>
                                    <option>Ujroh Fee</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Sektor Keuangan</label>
                                <input type="text" class="form-control" wire:model="sektor_keuangan" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mitra Pengimbang</label>
                                <input type="text" class="form-control" wire:model="mitra_pengimbang" />
                            </div>
                            <div class="form-group">
                                <label>Kerjasama Pemasaran</label>
                                <input type="text" class="form-control" wire:model="kerjasama_pemasaran" />
                            </div>
                            <div class="form-group">
                                <label>Asuransi Mikro</label>
                                <input type="text" class="form-control" wire:model="asuransi_mikro" />
                            </div>
                            <div class="form-group">
                                <label>PIC Marketing versi Marsup</label>
                                <input type="text" class="form-control" wire:model="pic_marketing" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>DC AAJI</label>
                                    <select class="form-control" wire:model="dc_aaji">
                                        <option value=""> -- Pilih -- </option>
                                        <option>BROKER</option>
                                        <option>DIRECT MARKETING</option>
                                        <option>KEAGENAN</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>DC OJK</label>
                                    <select class="form-control" wire:model="dc_ojk">
                                        <option value=""> -- Pilih -- </option>
                                        <option>AGEN</option>
                                        <option>BROKER</option>
                                        <option>DIRECT MARKETING</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Office</label>
                                <input type="text" class="form-control" wire:model="office" />
                            </div>
                            <div class="form-group">
                                <label>Channel</label>
                                <select class="form-control" wire:model="channel">
                                    <option value=""> -- Pilih -- </option>
                                    <option>AGENCY</option>
                                    <option>BROKER ASURANSI</option>
                                    <option>CO-INSURANCE</option>
                                    <option>CROSS SELLING</option>
                                    <option>DIRECT MARKETING</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Segment</label>
                                <input type="text" class="form-control" wire:model="segment" />
                            </div>
                            <div class="form-group">
                                <label>Line of Business</label>
                                <select class="form-control" wire:model="line_of_business">
                                    <option value=""> -- Pilih -- </option>
                                    <option>TRADISIONAL</option>
                                    <option>JANGKAWARSA</option>
                                    <option>EKAWARSA</option>
                                    <option>DWIGUNA</option>
                                    <option>DWIGUNA KOMBINASI</option>
                                    <option>KECELAKAAN DIRI</option>
                                    <option>OTHER TRADISIONAL</option>
                                </select>
                                @error('line_of_business')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Source of Business</label>
                                <select class="form-control" wire:model="source_of_business">
                                    <option value=""> -- Pilih -- </option>
                                    <option>BANK UMUM (AJK)</option>
                                    <option>BPR (AJK</option>
                                    <option>GTL</option>
                                    <option>LEASING (AJK)</option>
                                    <option>OTHER AJK (KOPRASI,LPD,ETC)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>No Nota Penutupan</label>
                                <input type="text" class="form-control" wire:model="no_nota_penutupan" />
                            </div>
                            <div class="form-group">
                                <label>No Perjanjian Kerjasama (PKS)</label>
                                <input type="text" class="form-control" wire:model="no_perjanjian_kerjasama" />
                            </div>
                            <div class="form-group">
                                <label>Peninjauan Ulang</label>
                                <input type="text" class="form-control" wire:model="peninjauan_ulang" />
                            </div>
                            <div class="form-group">
                                <label>Pembayaran Klaim</label>
                                <input type="text" class="form-control" wire:model="pembayaran_klaim" />
                            </div>
                            <div class="form-group">
                                <label>Retroaktif</label>
                                <input type="number" class="form-control" wire:model="retroaktif" placeholder="Hari" />
                            </div>
                            <div class="form-group">
                                <label>Waiting Period</label>
                                <input type="number" class="form-control" wire:model="waiting_period" placeholder="Bulan" />
                            </div>
                            <div class="form-group">
                                <label>Rate (Single/Usia)</label>
                                <select class="form-control" wire:model="rate_single_usia">
                                    <option value=""> -- Pilih -- </option>
                                    <option>Single</option>
                                    <option>Usia</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total BP</label>
                                <input type="text" class="form-control" wire:model="total_bp" />
                            </div>
                            <div class="form-group">
                                <label>No SB</label>
                                <input type="text" class="form-control" wire:model="no_sb" />
                            </div>
                            <div class="form-group">
                                <label>UW Limit</label>
                                <input type="text" class="form-control" wire:model="uw_limit" />
                            </div>
                            <div class="form-group">
                                <label>Margin Rate(min 10%)</label>
                                <input type="text" class="form-control" wire:model="margin_rate" />
                            </div>
                            <div class="form-group">
                                <label>RI Comm(10%-15%)</label>
                                <input type="text" class="form-control" wire:model="ri_comm" />
                            </div>
                            <div class="form-group">
                                <label>Share Reinsurance (40:60) (AJRI : Reas)</label>
                                <input type="text" class="form-control" wire:model="share_reinsurance" />
                            </div>
                            <div class="form-group">
                                <label>Loss ratio (to be review 40% - 50% to gross)</label>
                                <input type="text" class="form-control" wire:model="lost_ration" />
                            </div>
                            <div class="form-group">
                                <label>Profit Margin</label>
                                <input type="text" class="form-control" wire:model="profit_margin" />
                            </div>
                            <div class="form-group">
                                <label>Contingency Margin</label>
                                <input type="text" class="form-control" wire:model="contingency_margin" />
                            </div>
                            <div class="form-group">
                                <label>Business Source (Man Risk Recommendation) & UW Guideline</label>
                                <input type="text" class="form-control" wire:model="business_source" />
                            </div>
                            <div class="form-group">
                                <label>Refund %</label>
                                <input type="text" class="form-control" wire:model="refund" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Refund to Pengalihan %</label>
                                <input type="text" class="form-control" wire:model="refund_to_pengalihan" />
                            </div>
                            <div class="form-group">
                                <label>Dana Tabbaru Reas %</label>
                                <input type="text" class="form-control" wire:model="dana_tabbaru_reas" />
                            </div>
                            <div class="form-group">
                                <label>Dana Ujroh Reas %</label>
                                <input type="text" class="form-control" wire:model="dana_ujroh_reas" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Stop Loss</label>
                                    <input type="text" class="form-control" wire:model="stop_loss" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Cut Loss</label>
                                    <input type="text" class="form-control" wire:model="cut_loss" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Refund Cut Loss</label>
                                <input type="text" class="form-control" wire:model="refund_cut_loss" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="kepesertaan">
                    @if(\Auth::user()->user_access_id==3)
                        @livewire('polis.kepesertaan-akseptasi',['data'=>$data])
                    @else
                        @livewire('polis.kepesertaan',['data'=>$data])
                    @endif
                </div>
            </div>
            <div class="body">
                <hr />
                <span wire:loading wire:target="save,submit_draft,submit_issued">
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <div class="form-group" wire:loading.remove wire:target="save,submit_draft,submit_issued">
                    <a href="javascript:void(0)" class="mr-3" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
                    <button type="button" class="btn btn-info" wire:click="submit_draft"><i class="fa fa-save"></i> Save as Draft</button>
                    <button type="button" class="btn btn-success" wire:click="submit_issued"><i class="fa fa-arrow-right"></i> Issued</button>
                </div>
            </div>
        </div>
    </div>
</div>