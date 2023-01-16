<div class="modal-dialog" role="document">
    <form wire:submit.prevent="save">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-warning"></i> Submit Reas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($pengajuan as $item)
                        <a href="{{route('pengajuan.edit',$item->id)}}" target="_blank" class="badge badge-info">{{$item->dn_number}}</a>
                    @endforeach
                </div>
                <br />
                <div class="form-group">
                    <label>Reasuradur</label>
                    <select class="form-control" wire:model="reasuradur_id">
                        <option value=""> -- Pilih -- </option>
                        @foreach($reasuradur as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('reasuradur_rate_id')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Perhitungan Usia</label>
                    <select class="form-control" wire:model="perhitungan_usia">
                        <option value=""> -- Pilih -- </option>
                        <option value="1">Nears Birthday</option>
                        <option value="2">Actual Birthday</option>
                    </select>
                    @error('perhitungan_usia')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <span wire:loading wire:target="reasuradur_id">
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                @if($reasuradur_id)
                    <div wire:loading.remove wire:target="reasuradur_id">
                        <div class="form-group">
                            <label>Rate & UW Limit</label>
                            <select class="form-control" wire:model="reasuradur_rate_id">
                                <option value=""> -- Pilih -- </option>
                                @foreach($rate as $item)
                                    <option value="{{$item->id}}">{{$item->nama}} - OR ({{$item->or}}%) - Reas ({{$item->reas}}%)</option>
                                @endforeach
                            </select>
                            @error('reasuradur_rate_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>OR</label>
                                <input type="text" class="form-control" wire:model="or" readonly />
                                @error('or')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Reas</label>
                                <input type="text" class="form-control" wire:model="reas" readonly />
                            </div>
                            <div class="col-md-4 form-group">
                                <label>RI COM</label>
                                <input type="text" class="form-control" wire:model="ri_com" readonly />
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-md-4 form-group">
                                <label>Kadaluarsa Reas</label>
                                <input type="number" class="form-control" placeholder="Hari" wire:model="kadaluarsa_reas_hari" />
                                @error('kadaluarsa_reas_hari')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div> -->
                            <div class="col-md-4 form-group">
                                <label>Manfaat</label>
                                <select class="form-control" wire:model="manfaat">
                                    <option value=""> -- Pilih -- </option>
                                    <option> MENURUN </option>
                                    <option> TETAP </option>
                                </select>
                                @error('manfaat')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Type Reas</label>
                                <select class="form-control" wire:model="type_reas">
                                    <option value=""> -- Pilih -- </option>
                                    <option> TREATY </option>
                                    <option> FAKULTATIF </option>
                                </select>
                                @error('type_reas')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
                <hr />
                <div class="form-group">
                    <span wire:loading wire:target="save">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <button type="submit" wire:loading.remove wire:target="save" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
