<div class="modal-dialog modal-lg" style="max-width:90%;"  role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-database"></i> Underwriting Limit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover m-b-0 c_list">
                    <thead style="background: #eee;">
                        <tr>
                            <th colspan="2">Jumlah Uang Pertanggungan</th>
                            <th colspan="{{$usia->count()}}">Usia</th>

                        </tr>
                        <tr>
                            <th>Nilai Bawah</th>
                            <th>Nilai Atas</th>
                            @foreach($usia as $item)
                                <th>{{$item->usia}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nilai_bawah_atas as $n)
                            <tr>
                                <td>{{format_idr($n->min_amount)}}</td>
                                <td>{{format_idr($n->max_amount)}}</td>
                                @foreach($usia as $item)
                                    <td>{{isset($rows[$n->usia][$n->min_amount][$n->max_amount]) ? $rows[$n->usia][$n->min_amount][$n->max_amount] : '-'}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr />
            <form wire:submit.prevent="upload">
                <div class="form-group">
                    <label>File (xlsx)</label>
                    <input type="file" class="form-control" wire:model="file" />
                    @error('file')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group">
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-upload"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
