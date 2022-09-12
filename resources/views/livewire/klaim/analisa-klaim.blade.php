<div class="row">
    <div class="col-md-6">  
        <table class="table ml-2">
            <tr>
                <td>1. Sumber Informasi</td>
                <td>
                    <textarea class="form-control" wire:model="sumber_informasi"></textarea>
                    @error('sumber_informasi')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>2 Sebab Meninggal</td>
                <td>
                    <textarea class="form-control" wire:model="sebab_meninggal"></textarea>
                    @error('sebab_meninggal')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>3. Riwayat Penyakit</td>
                <td>
                    <textarea class="form-control" wire:model="riwayat_penyakit"></textarea>
                    @error('riwayat_penyakit')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>4. Tempat Meninggal</td>
                <td>
                    <textarea class="form-control" wire:model="tempat_meninggal"></textarea>
                    @error('tempat_meninggal')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>5. Verifikasi via telpon</td>
                <td>
                    <textarea class="form-control" wire:model="verifikasi_via_telpon"></textarea>
                    @error('verifikasi_via_telpon')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>6. Analisa Medis</td>
                <td>
                    <textarea class="form-control" wire:model="analisa_medis"></textarea>
                    @error('analisa_medis')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>7. Kesimpulan</td>
                <td>
                    <textarea class="form-control" wire:model="kesimpulan"></textarea>
                    @error('kesimpulan')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
            </tr>
        </table>
        <div class="form-group">
            <span wire:loading wire:target="save">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span> Submit...
            </span>
            <a href="javascript:void(0)" wire:loading.remove wire:target="save" class="btn btn-info" wire:click="save"><i class="fa fa-check-circle"></i> Submit Analisa Klaim</a>
        </div>
    </div>
</div>