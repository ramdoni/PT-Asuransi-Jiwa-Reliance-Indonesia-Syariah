@section('title', 'Administrator')
@section('parentPageTitle', 'Distribution Channel')

<div class="clearfix row">
    <div class="col-lg-9">
        <div class="card">
            <div class="header row">
                <div class="col-md-1">
                    <a href="javascript:void()" wire:click="$set('is_insert',true)" class="btn btn-primary"><i class="fa fa-plus"></i> Distribution Channel</a>
                </div>
            </div>
            <div class="pt-0 body">
                <div class="table-responsive">
                    <table class="table m-b-0 c_list">
                        <tr style="background:#eee">
                            <th>Type</th>
                            <th>Channel</th>
                            <th></th>
                        </tr>
                        @if($is_insert)
                            <tr wire:loading.remove wire:target="save">
                                <td>
                                    {{-- <input type="text" class="form-control" wire:model="type" placeholder="Type" wire:keydown.enter="save" /> --}}
                                    <select class="form-control" wire:model="type">
                                        <option value=""> -- Select Type -- </option>
                                        @foreach(config('vars.distribution_type') as $item)
                                            <option>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model="name" placeholder="Name" wire:keydown.enter="save" />
                                </td>
                                <td>
                                    <a href="javascript:void()" wire:click="save" class="badge badge-info badge-active"><i class="fa fa-save"></i> Save</a>
                                </td>
                            </tr>
                            <tr wire:loading wire:target="save">
                                <td colspan="3" class="text-center">
                                    <span>
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                </td>
                            </tr>
                        @endif
                        @foreach($data as $item)
                            <tr>
                                <td>{{$item->type}}</td>
                                <td>{{$item->name}}</td>
                                <td>
                                    <a wire:loading.remove wire:target="delete({{$item->id}})" href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger"><i class="fa fa-trash"></i></a>
                                    <span wire:loading wire:target="delete({{$item->id}})">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>