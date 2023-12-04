<div class="modal-dialog"  role="document">
    <div class="modal-content">
        <form wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Reasuradur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Reasuradur</label>
                    <select class="form-control" wire:model="reasuradur_id">
                        <option value=""> -- Select -- </option>
                        @foreach(\App\Models\Reasuradur::get() as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('reasuradur_id')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" wire:model="nama" />
                    @error('nama')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>OR</label>
                        <input type="number" class="form-control" wire:model="or" />
                        @error('or')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Reas</label>
                        <div wire:loading wire:target="or">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>
                        <input wire:loading.remove wire:target="or" type="number" class="form-control" wire:model="reas" readonly />
                        @error('reas')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label>RI COM (%)</label>
                        <input type="number"  class="form-control" wire:model="ri_com" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Model Reas</label>
                        <select class="form-control" wire:model="model_reas">
                            <option value=""> -- Pilih -- </option>
                            @foreach(['OR','Surplus','QS','QS_Surplus'] as $item)
                                <option>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Max OR</label>
                        <input type="text"  class="form-control" wire:model="max_or" />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Refund(%)</label>
                        <input type="number" min=0 class="form-control" wire:model="persentase_refund" />
                    </div>
                    
                    <div class="col-md-12 form-group">
                        <label>Rumus Pengembalian</label>
                        <select class="form-control" wire:model="type_pengembalian_kontribusi">
                            <option value="1">Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross reas</option>
                            <option value="2">Nilai Pengembalian Kontribusi = t/n x dana tabarru’reas</option>
                            <option value="3">Nilai Pengembalian Kontribusi = t/n x % x dana tabarru’reas</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Dana Tabbaru(%)</label>
                        <input type="number" min=0 class="form-control" wire:model="tabbaru" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div wire:loading>
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </div>
                <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>