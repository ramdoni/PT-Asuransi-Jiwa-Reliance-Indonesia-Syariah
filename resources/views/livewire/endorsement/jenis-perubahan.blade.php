<div wire:ignore.self class="modal fade" id="modal_jenis_perubahan" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-database"></i> Jenis Perubahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table m-b-0 c_list table-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Perubahan</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach($data as $k => $i)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$i->name}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    @if($is_insert)
                        <tr>
                            <th></th>
                            <td>
                                <input type="text" class="form-control" wire:model="name" />
                            </td>
                            <td>
                                <a href="#" wire:click="save" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Simpan</a>
                                <a href="#" wire:click="$set('is_insert',false)" class="text-danger ml-2"><i class="fa fa-close"></i></a>  
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="2">
                                <a href="#" wire:click="$set('is_insert',true)"><i class="fa fa-plus"></i> Tambah</a>
                            </td>
                        </tr>
                    @endif
                </table>
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