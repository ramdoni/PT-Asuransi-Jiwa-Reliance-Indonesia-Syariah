<div class="row">
    <div class="col-md-6">  
        <table class="table ml-2">
            <tr>
                <td>1. Formulir Pengajuan Klaim </td>
                <td> 
                    @if($data->formulir_pengajuan_klaim)
                        <a href="{{asset($data->formulir_pengajuan_klaim)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="formulir_pengajuan_klaim">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="formulir_pengajuan_klaim" wire:model="formulir_pengajuan_klaim" />
                    @error('formulir_pengajuan_klaim')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror

                </td>
            </tr>
            <tr>
                <td>2. Surat Keterangan Meninggal dari Kelurahan/Kades</td>
                <td>
                    @if($data->surat_keterangan_meninggal_kelurahan)
                        <a href="{{asset($data->surat_keterangan_meninggal_kelurahan)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="surat_keterangan_meninggal_kelurahan">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="surat_keterangan_meninggal_kelurahan" wire:model="surat_keterangan_meninggal_kelurahan" />
                    @error('surat_keterangan_meninggal_kelurahan')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>3. Surat Keterangan Meninggal Dunia dari RS</td>
                <td>
                    @if($data->surat_keterangan_meninggal_rs)
                        <a href="{{asset($data->surat_keterangan_meninggal_rs)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="surat_keterangan_meninggal_rs">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="surat_keterangan_meninggal_rs" wire:model="surat_keterangan_meninggal_rs" />
                    @error('surat_keterangan_meninggal_rs')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>3. Copy Identitas Peserta Asuransi (KTP/kartu pst)</td>
                <td>
                    @if($data->copy_ktp)
                        <a href="{{asset($data->copy_ktp)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="copy_ktp">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="copy_ktp" wire:model="copy_ktp" />
                    @error('copy_ktp')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>4. Copy Identitas Ahli Waris (KTP,KK)</td>
                <td>
                    @if($data->copy_ktp_ahli_waris)
                        <a href="{{asset($data->copy_ktp_ahli_waris)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="copy_ktp_ahli_waris">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="copy_ktp_ahli_waris" wire:model="copy_ktp_ahli_waris" />
                    @error('copy_ktp_ahli_waris')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>5. Resume Medis/Surat Keterangan Dokter</td>
                <td>
                    @if($data->resume_medis)
                        <a href="{{asset($data->resume_medis)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="resume_medis">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="resume_medis" wire:model="resume_medis" />
                    @error('resume_medis')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>6. Daftar Angsuran/Baki Debet</td>
                <td>
                    @if($data->daftar_angsuran)
                        <a href="{{asset($data->daftar_angsuran)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="daftar_angsuran">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="daftar_angsuran" wire:model="resume_medis" />
                    @error('daftar_angsuran')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">  
        <table class="table ml-2">
            <tr>
                <td>8. Copy Akad Pembiayaan</td>
                <td>
                    @if($data->copy_akad_pembiayaan)
                        <a href="{{asset($data->copy_akad_pembiayaan)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="copy_akad_pembiayaan">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="copy_akad_pembiayaan" wire:model="copy_akad_pembiayaan" />
                    @error('copy_akad_pembiayaan')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>9. Surat Kuasa</td>
                <td>
                    @if($data->surat_kuasa)
                        <a href="{{asset($data->surat_kuasa)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="surat_kuasa">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="surat_kuasa" wire:model="surat_kuasa" />
                    @error('surat_kuasa')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>10. Surat Keterangan Ahli Waris</td>
                <td>
                    @if($data->surat_keterangan_ahli_waris)
                        <a href="{{asset($data->surat_keterangan_ahli_waris)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="surat_keterangan_ahli_waris">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="surat_keterangan_ahli_waris" wire:model="surat_keterangan_ahli_waris" />
                    @error('surat_keterangan_ahli_waris')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>11. Surat dari Pemegang Polis</td>
                <td>
                    @if($data->surat_dari_pemegang_polis)
                        <a href="{{asset($data->surat_dari_pemegang_polis)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="surat_dari_pemegang_polis">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="file" class="form-control" wire:loading.remove wire:target="surat_dari_pemegang_polis" wire:model="surat_dari_pemegang_polis" />
                    @error('surat_dari_pemegang_polis')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>12. Dokumen Lainnya</td>
                <td>
                    @if($data->dokumen_lain)
                        <a href="{{asset($data->dokumen_lain)}}" target="_blank"><i class="fa fa-check-circle text-success"></i></a>
                    @else
                        <i class="fa fa-warning text-warning" title="Belum upload"></i>
                    @endif 
                </td>
                <td>
                    <span wire:loading wire:target="dokumen_lain">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span> Upload...
                    </span>
                    <input type="text" class="form-control" wire:model="dokumen_lain_keterangan" placeholder="Nama Dokumen Pendukung" />
                    <input type="file" class="form-control" wire:loading.remove wire:target="dokumen_lain" wire:model="dokumen_lain" />
                    @error('dokumen_lain')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            @if($data->tanggal_dok_lengkap=="")
                <button type="button" class="btn btn-info" wire:click="dokumen_lengkap"><i class="fa fa-check-circle"></i> Dokumen Sudah Lengkap</button>
            @endif
        </div>
    </div>
</div>