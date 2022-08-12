<div class="col-lg-6">
    <div class="card">
        <div class="header pb-0">
            <div class="row">
                <div class="col-md-10">
                    <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_rate_insert"><i class="fa fa-plus"></i> Rate & UL</a>
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
                            <th>Reas</th>
                            <th>Name</th>
                            <th>Rate</th>
                            <th>UL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$item->name}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_rate_insert" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.rate-insert')
</div>