@section('sub-title', 'Index')
@section('title', 'Produk')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header pb-0">
                <div class="row">
                    <div class="col-md-1">
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
                    <div class="col-md-9">
                        <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_add"><i class="fa fa-plus"></i> Produk</a>
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
                                <th>Kode Produk</th>
                                <th>Singkatan Produk</th>
                                <th>Nama Produk</th>
                                <th>Klasifikasi</th>
                                <th>Running Number</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td>{{$item->kode}}</td>
                                    <td><a href="javascript:void(0)" wire:click="$emit('set-id',{{$item->id}})" data-toggle="modal" data-target="#modal_edit">{{$item->singkatan}}</a></td>
                                    <td>{{$item->nama}}</td>
                                    <td>{{$item->klasifikasi}}</td>
                                    <td>{{$item->running_number}}</td>
                                    <td><a href="javascript:void(0)" class="text-danger" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            @endforeach
                            @if($data->count()==0)
                                <tr><td class="text-center" colspan="9"><i>empty</i></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br />
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modal_add" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('produk.insert')
</div>

<div wire:ignore.self class="modal fade" id="modal_edit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('produk.edit')
</div>