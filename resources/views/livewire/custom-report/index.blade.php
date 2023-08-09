@section('title', 'Custom Report')
@section('sub-title', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-3">
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
                                <th>Nama</th>                                    
                                <th>File</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="text-danger"  wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i></a>
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
<div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('custom-report.upload')
</div>