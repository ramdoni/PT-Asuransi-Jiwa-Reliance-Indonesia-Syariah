@section('sub-title','Pengajuan')
@section('title', 'Klaim')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group" wire:ignore>
                                <label>No Polis</label>
                                <select class="form-control" id="polis_id" wire:model="polis_id">
                                    <option value=""> -- Select Polis -- </option>
                                    @foreach($polis as $item)
                                        <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                    @endforeach
                                </select>
                                @error('polis_id')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Peserta</label>
                            <select class="form-control" id="kepesertaan_id" wire:model="kepesertaan_id">
                                <option value=""> -- Select Peserta -- </option>
                                @foreach($kepesertaan as $item)
                                    <option value="{{$item->id}}">{{$item->no_peserta}} / {{$item->nama}}</option>
                                @endforeach
                            </select>
                            @error('kepesertaan_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <span wire:loading wire:target="polis_id">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script>
        select__2 = $('#polis_id').select2();
        $('#polis_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('polis_id', data);
        });
        var selected__ = $('#polis_id').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
         Livewire.on('modal_show_double', (msg) => {
            $('#modal_show_double').modal('show');
        });
    </script>
@endpush
