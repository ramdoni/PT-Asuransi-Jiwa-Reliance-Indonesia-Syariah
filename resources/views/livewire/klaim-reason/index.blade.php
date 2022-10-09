@section('sub-title', 'Index')
@section('title', 'Klaim Reason')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <table class="table m-b-0 c_list">
                        <thead style="background:#eee">
                            <tr>
                                <th>No</th>
                                <th>Reason</th>
                                <th>Item</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                                <tr>
                                    <td>{{$k+1}}</td>
                                </tr>
                            @endforeach
                            @if($insert==false)
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <a href="javascript:void(0)"  wire:loading.remove wire:target="insert" wire:click="$set('insert',true)" class="badge badge-info badge-active"> Tambah</a>
                                        <span wire:loading wire:target="insert">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                    </td>
                                </tr>
                            @endif
                            @if($insert)
                                <tr>
                                    <td></td>
                                    <td><input type="text" class="form-control" /></td>
                                    <td></td>
                                    <td>
                                        <a href="javascript:void(0)" title="Batal" wire:click="$set('insert',false)"><i class="fa fa-times text-danger"></i></a>
                                        <a href="javascript:void(0)" title="Simpan" wire:click="save"><i class="fa fa-save text-info"></i></a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>
