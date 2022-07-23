<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Editable</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent="save">
                <div class="form-group">
                    @if($field=='jenis_kelamin')
                        <select class="form-control" wire:model="value">
                            <option> -- Pilih -- </option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                    @else
                        <input type="text" class="form-control" wire:model="value" />
                    @endif
                </div>
                <div class="form-group">
                    <span wire:loading wire:target="save">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
                <hr />
                <div class="form-group">
                    <button type="submit" wire:loading.remove wire:target="save" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
