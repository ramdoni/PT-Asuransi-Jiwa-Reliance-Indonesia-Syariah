@section('sub-title', 'Index')
@section('title', 'Extra Mortalita')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <select class="form-control" wire:model="extra_mortalita_id">
                            <option value=""> -- Pilih -- </option>
                            @foreach($data as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-10">
                        <a href="javacript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_add"><i class="fa fa-plus"></i> Tambah</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    @if(count($raw_data)>0)
                        <table class="table table-hover m-b-0 c_list">
                            <thead style="background: #eee;">
                                <tr>
                                    <th>x/n</th>
                                    @foreach($row_tahun as $tahun)
                                        <th>{{$tahun->tahun}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($row_usia as $usia)
                                    <tr>
                                        <td style="background: #eee;">{{$usia->usia}}</td>
                                        @foreach($row_tahun as $tahun)
                                            <td>{{isset($raw_data[$tahun->tahun][$usia->usia]) ? $raw_data[$tahun->tahun][$usia->usia] : '-'}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('extra-mortalita.insert')
</div>