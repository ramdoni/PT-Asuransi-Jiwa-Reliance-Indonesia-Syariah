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
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn-primary" wire:click="$set('insert',true)"><i class="fa fa-plus"></i> Rate</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Polis</th>                                    
                                <th class="text-center">Period</th>                                    
                                <th class="text-center">Permintaan Bank</th>                                    
                                <th class="text-center">Ajri</th>
                                <th class="text-center">Ari</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($insert)
                                <tr>
                                    <td></td>
                                    <td>
                                        <select class="form-control" wire:model="polis_id">
                                            <option value=""> -- Polis -- </option>
                                            @foreach($polis as $item)
                                                <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                        @error('polis_id')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Period" wire:model="period" />
                                        @error('period')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Permintaan Bank" wire:model="permintaan_bank" />
                                        @error('permintaan_bank')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
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
                                    <td class="text-center">{{$item->period}}</td>
                                    <td class="text-center">{{$item->permintaan_bank}}</td>
                                    <td class="text-center">{{$item->ajri}}</td>
                                    <td class="text-center">{{$item->ari}}</td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="text-danger"  wire:click=""><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_autologin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-sign-in"></i> Autologin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger close-modal">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirm_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-warning"></i> Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <p>Are you want delete this data ?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                <button type="button" wire:click="delete()" class="btn btn-danger close-modal">Yes</button>
            </div>
        </div>
    </div>
</div>
@section('page-script')
function autologin(action,name){
    $("#modal_autologin form").attr("action",action);
    $("#modal_autologin .modal-body").html('<p>Autologin as '+name+' ?</p>');
    $("#modal_autologin").modal("show");
}
@endsection