<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-info"></i> Submit Reas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Reasuradur</label>
                <select class="form-control" wire:model="reasuradur_id">
                    <option value=""> -- Pilih -- </option>
                    @foreach($reasuradur as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            @if($reasuradur_id)
                <div class="form-control">
                    <label>Rate & UW Limit</label>
                    <select class="form-control">
                        <option value=""> -- Pilih -- </option>
                        @foreach($rate as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <hr />
            <div class="form-group">
                <span wire:loading wire:target="save">
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <button type="button" wire:loading.remove wire:target="delete" wire:click="delete" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>
    </div>
</div>