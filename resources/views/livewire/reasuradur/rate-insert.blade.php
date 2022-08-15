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
                    <div class="form-group col-md-6">
                        <label>OR</label>
                        <input type="number" class="form-control" wire:model="or" />
                        @error('or')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
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
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Rate</label>
                        <input type="file" class="form-control" wire:model="rate" />
                        @error('rate')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>UW Limit</label>
                        <input type="file" class="form-control" wire:model="uw_limit" />
                        @error('uw_limit')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>