@section('title', 'Rate Broker')
@section('sub-title', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <select class="form-control" wire:model="filter_polis_id">
                        <option value=""> -- Polis -- </option>
                        @foreach($polis as $item)
                            <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="filter_packet">
                        <option value=""> -- Packet -- </option>
                        @foreach($arr_packet as $k => $val)
                            <option value="{{$k}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="javascript:void(0)" class="btn btn-primary" wire:click="$set('insert',true)"><i class="fa fa-plus"></i> Rate</a>
                    <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload</a>
                    <span wire:loading wire:target="insert">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Polis</th>                                    
                                <th>Packet</th>                                    
                                <th class="text-center">Period</th>                                    
                                <!-- <th class="text-center">Permintaan Bank</th>                                     -->
                                <th class="text-center">Ajri</th>
                                <th class="text-center">Ari</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($insert)
                                <tr>
                                    <td></td>
                                    <td style="width: 400px;">
                                        <div wire:ignore>
                                            <select class="form-control" id="polis_id" wire:model="polis_id">
                                                <option value=""> -- Polis -- </option>
                                                @foreach($polis as $item)
                                                    <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('polis_id')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <select class="form-control" wire:model="packet">
                                            <option value=""> -- Packet -- </option>
                                            @foreach($arr_packet as $key => $item)
                                                <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                        @error('packet')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Period" wire:model="period" />
                                        @error('period')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <!-- <td>
                                        <input type="text" class="form-control" placeholder="Permintaan Bank" wire:model="permintaan_bank" />
                                        @error('permintaan_bank')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td> -->
                                    <td>
                                        <input type="text" class="form-control" placeholder="Ajri" wire:model="ajri" />
                                        @error('ajri')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="ARI" wire:model="ari" />
                                        @error('ari')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <div wire:loading.remove wire:target="save">
                                            <a href="javascript:void(0)" wire:click="save"><i class="fa fa-save text-success"></i></a>
                                            <a href="javascript:void(0)" wire:click="$set('insert',false)"><i class="fa fa-close text-danger"></i></a>
                                        </div>
                                        <span wire:loading wire:target="save">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                    </td>
                                </tr>
                            @endif
                            @foreach($data as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($item->polis->no_polis) ? $item->polis->no_polis .' / '.$item->polis->nama : '-'}}</td>
                                    <td class="text-center">{{isset($arr_packet[$item->packet]) ? $arr_packet[$item->packet] : '-'}}</td>
                                    <td class="text-center">{{$item->period}}</td>
                                    <!-- <td class="text-center">{{$item->permintaan_bank}}</td> -->
                                    <td class="text-center">{{$item->ajri}}</td>
                                    <td class="text-center">{{$item->ari}}</td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="text-danger"  wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$data->links()}}
                <br />
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
    </script>
@endpush
<div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('rate-broker.upload')
</div>