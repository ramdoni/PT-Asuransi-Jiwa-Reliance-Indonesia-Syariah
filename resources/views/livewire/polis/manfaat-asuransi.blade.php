<div wire:ignore.self class="modal fade" id="modal_manfaat_asuransi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-database"></i> Manfaat Asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Manfaat Asuransi</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($data as $k => $i)
                            @if($is_insert and $i->id==$selected_id) @continue @endif
                            <tr wire:key="{{$i->id}}">
                                <td>{{$k+1}}</td>
                                <td>
                                    <a href="javascript:void(0)" wire:click="delete({{$i->id}})"><i class="fa fa-trash text-danger"></i></a>
                                    <a href="javascript:void(0)" wire:click="edit({{$i->id}})" class="mx-3"><i class="fa fa-edit"></i></a>
                                    {{$i->nama}}
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>
                    <table class="table m-b-0 c_list table-nowrap" style="width: 100%;">
                        @if($is_insert)
                            <tr>
                                <td style="width: 100px;">
                                    <a href="javascript:void(0)" wire:click="cancel" class="text-danger mr-2"><i class="fa fa-close"></i></a>  
                                    <a href="javascript:void(0)" wire:click="save" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Simpan</a>
                                </td>
                                <td colspan="2">
                                    <!-- <input type="text" class="form-control" wire:model="nama" /> -->
                                    <textarea class="form-control" wire:model="nama" style="height: 300px;"></textarea>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">
                                    <a href="javascript:void(0)" wire:click="insert"><i class="fa fa-plus"></i> Tambah</a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <hr />
                <div class="form-group">
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>