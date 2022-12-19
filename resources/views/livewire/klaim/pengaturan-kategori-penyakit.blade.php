<div class="row">
    <div class="col-md-4">
        <table class="table">
            <thead>
                <tr style="background:#eee">
                    <th>No</th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($data as $k => $item)
                <tr>
                    <td>{{$k+1}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        <a href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            @if($insert)
                <tr>
                    <td></td>
                    <td><input type="text" class="form-control" wire:model="name" /></td>
                    <td>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save" wire:click="save" class="text-success"><i class="fa fa-save"></i></a>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save" wire:click="$set('insert',false)" class="text-danger mx-2"><i class="fa fa-close"></i></a>
                        <span wire:loading wire:target="save">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="2" class="text-center">
                        <a href="javascript:void(0)" wire:click="$set('insert',true)"><i class="fa fa-plus"></i> Tambah</a>
                    </td>
                </tr>
            @endif
        </table>
    </div>
</div>         