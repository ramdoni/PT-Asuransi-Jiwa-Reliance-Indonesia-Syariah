@section('sub-title','Nomor Pengajuan :'.$no_pengajuan)
@section('title', 'Pengajuan Harian')
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
                            @if($message_error)
                                <span class="text-danger">{{$message_error}}</span>
                            @endif
                        </div>
                        <span wire:loading wire:target="polis_id">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($polis_id and $message_error=="")
                            <div class="col-md-2">
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
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Masa Asuransi</label>
                                    <select class="form-control" wire:model="masa_asuransi">
                                        <option value=""> -- Pilih -- </option>
                                        <option value="1">Day to Day</option>
                                        <option value="2">Day to Day -1</option>
                                    </select>
                                    @error('masa_asuransi')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>File (xlsx)</label>
                                    <a href="{{asset('template/template-kepesertaan.xlsx')}}"><i class="fa fa-download"></i> Template</a>
                                    <span wire:loading wire:target="file">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                    <input type="file" wire:loading.remove wire:target="file" class="form-control" wire:model="file" />
                                    @error('file')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <br />
                                    @if($file)
                                        <a href="javascript:void(0)" wire:loading.remove wire:target="clear_file,save,hitung" wire:click="clear_file" class="text-danger mt-5"><i class="fa fa-times"></i> Clear</a>
                                        @if($is_calculate==false)
                                            <a href="javascript:void(0)" wire:loading.remove wire:click="calculate" class="btn btn-warning mx-2"><i class="fa fa-refresh"></i> Hitung</a>
                                        @endif
                                        @if($total_pengajuan >0)
                                            <button wire:loading.remove wire:target="save,file,hitung" type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> Upload Pengajuan</button>
                                        @endif
                                    @endif
                                    @if(count($check_id)>0)
                                        <a href="javascript:void(0)" wire:click="keepAll" wire:model="modelKeepAll" class="btn btn-success"><i class="fa fa-check-circle"></i> Keep All</a>
                                        <a href="javascript:void(0)" wire:click="deleteAll" class="btn btn-danger"><i class="fa fa-trash"></i> Delete All</a>
                                    @endif
                                    @if($is_calculate)
                                        <span>
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span> Sedang Menghitung...
                                        </span>
                                    @endif
                                    <span wire:loading wire:target="clear_file,save,polis_id,masa_asuransi,keepAll,deleteAll">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    @livewire('pengajuan.insert-row',['polis_id'=>$polis_id],key(1))
                </form>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modal_add_extra_kontribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-extra-kontribusi')
</div>
<div wire:ignore.self class="modal fade" id="modal_add_em" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-em')
</div>
<div wire:ignore.self class="modal fade" id="modal_show_double" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('pengajuan.show-double')
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
        var channel = pusher.subscribe('pengajuan');
        channel.bind('generate', function(data) {
            Livewire.emit('set_calculate',false);
            console.log(data);
            if(data.transaction_id=={{$transaction_id}}){
                show_toast(data.message,'top-center');
            }
        });

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
