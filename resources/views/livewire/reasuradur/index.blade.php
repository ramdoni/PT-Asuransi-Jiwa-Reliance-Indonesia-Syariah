@section('sub-title', 'Index')
@section('title', 'Reasuradur')
<div class="clearfix row">
    <div class="col-lg-6">
        <div class="card">
            <div class="header pb-0">
                <div class="row">
                    <div class="col-md-2">
                        <div class="pl-3 pt-2 form-group" x-data="{open_dropdown:false}" @click.away="open_dropdown = false">
                            <a href="javascript:void(0)" x-on:click="open_dropdown = ! open_dropdown" class="dropdown-toggle">
                                    Filter <i class="fa fa-search-plus"></i>
                            </a>
                            <div class="dropdown-menu show-form-filter" x-show="open_dropdown">
                                <form class="p-2">
                                    <div class="from-group my-2">
                                        <input type="text" class="form-control" wire:model="filter_keyword" placeholder="Keyword" />
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_add"><i class="fa fa-plus"></i> Reasuradur</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list">
                        <thead style="background: #eee;">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($insert)
                                <tr>
                                    <td><input type="text" class="form-control" wire:model="label" placeholder="Label" /></td>
                                    <td><input type="file" class="form-control" wire:model="rate" /></td>
                                    <td><input type="file" class="form-control" wire:model="rate" /></td>
                                </tr>
                            @endif
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td><a href="javascript:void(0)" wire:click="$emit('set-id',{{$item->id}})" data-toggle="modal" data-target="#modal_edit">{{$item->name}}</a></td>
                                    <td></td>
                                </tr>
                                @if($insert==false)
                                    <tr>
                                        <td colspan="3" class="text-center"><a href="javascript:void(0)" wire:click="$set('insert',true)" class="badge badge-warning badge-active"><i class="fa fa-plus"></i> Tambah</a></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.insert')
</div>
<div wire:ignore.self class="modal fade" id="modal_edit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('rate.edit')
</div>