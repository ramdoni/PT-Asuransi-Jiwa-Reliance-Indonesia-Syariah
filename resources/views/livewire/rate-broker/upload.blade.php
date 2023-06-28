<div class="modal-dialog"  role="document">
    <div class="modal-content">
        <form wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Upload Rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div wire:ignore>
                        <label>Polis</label>
                        <select class="form-control" id="polis_id_modal" wire:model="polis_id">
                            <option value=""> -- Polis -- </option>
                            @foreach($polis as $item)
                                <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('polis_id')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Packet</label>
                    <select class="form-control" wire:model="packet">
                        <option value=""> -- Packet -- </option>
                        @foreach($arr_packet as $key => $item)
                            <option value="{{$key}}">{{$item}}</option>
                        @endforeach
                    </select>
                    @error('packet')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group">
                    <label>File</label>
                    <input type="file" class="form-control" wire:model="file" />
                    @error('file')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <span wire:loading>
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-save"></i> Upload</button>
            </div>
        </form>
    </div>
</div>
@push('after-scripts')

    <script>
        select__modal = $('#polis_id_modal').select2();
        $('#polis_id_modal').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('polis_id', data);
        });
    </script>
@endpush