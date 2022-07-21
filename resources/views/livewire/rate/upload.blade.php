<div class="modal-dialog modal-lg"  role="document" style="max-width:90%;">
    <div class="modal-content">
        <form wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Upload Rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list">
                        <thead style="background: #eee;">
                            <tr>
                                <th rowspan="2">Usia / Masa Asuransi</th>
                                @if(isset($get_bulan))
                                    @if($get_bulan->count())
                                        <th colspan="{{$get_bulan->count()}}">Asuransi (Bulan)</th>
                                    @endif
                                @endif
                            </tr>
                            <tr>
                                @if(isset($get_bulan))
                                    @foreach($get_bulan as $bulan)
                                        <td>{{$bulan->bulan}}</td>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($data)
                                @foreach ($data as $k => $item)
                                    <tr>
                                        <td>{{$item->tahun}}</td>
                                        @foreach($get_bulan as $bulan)
                                            <td>{{isset($raw_data[$item->tahun][$bulan->bulan]) ? $raw_data[$item->tahun][$bulan->bulan] : '-'}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <hr />
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>File</label>
                        <input type="file" class="form-control" wire:model="file" />
                        @error('file')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span wire:loading>
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-save"></i> Upload</button>
            </div>
        </form>
    </div>
</div>