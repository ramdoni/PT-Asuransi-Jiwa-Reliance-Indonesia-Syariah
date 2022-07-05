<div class="modal-dialog"  role="document">
    <div class="modal-content">
        <form wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Extra Mortalita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6">
                    <label>Persen</label>
                    <input type="text" class="form-control" wire:model="name" />
                    @error('name')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label>File (xlsx)</label>
                    <input type="file" class="form-control" wire:model="file" />
                    @error('file')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <span wire:loading wire:target="save,file">
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <button type="submit" wire:loading.remove wire:target="save,file" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>