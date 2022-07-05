<div class="">
    <div class="row">
        <div class="col-md-2 form-group">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
        </div>
        <div class="col-md-10 form-group">
            @if($data->status_akseptansi==0)
                <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload Peserta</a>
                <a href="javascript:void(0)" wire:click="calculate" wire:loading.remove wire:target="calculate" class="btn btn-danger" ><i class="fa fa-refresh"></i> Hitung</a>
            @endif
            <span wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
        </div>
    </div>
    <div class="table-responsive"> 
        <table class="table table-hover table-bordered m-b-0 c_list">
            <thead style="background: #eee;">
                <tr>
                    <th>No</th>
                    <th>Hitung</th>
                    <th>Ket</th>
                    <th>Bordero</th>
                    <th>Nama Bank</th>
                    <th>KC/KP</th>
                    <th>No KTP</th>
                    <th>Status</th>
                    <th>No Telepon</th>
                    <th>Gender</th>
                    <th>No Peserta</th>
                    <th>Nama Peserta</th>
                    <th>Tgl. Lahir</th>
                    <th>Usia</th>
                    <th>Mulai Asuransi</th>
                    <th>Akhir Asuransi</th>
                    <th class="text-right">Nilai Manfaat Asuransi</th>
                    <th class="text-right">Dana Tabarru</th>
                    <th class="text-right">Dana Ujrah</th>
                    <th class="text-right">Kontribusi</th>
                    <th class="text-right">Extra Mortality</th>
                    <th class="text-right">Extra Kontribusi</th>
                    <th class="text-right">Total Kontribusi</th>
                    <th>Tgl Stnc</th>
                    <th>UL</th>
                    <th>Ket</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kepesertaan as $k => $item)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td class="text-center">
                            @if($item->is_hitung==1)
                                <span title="Sudah dihitung"><i class="text-success fa fa-check-circle"></i></span>
                            @else
                                <span title="Belum dihitung"><i class="text-danger fa fa-close"></i></span>
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                        <td>{{$item->bank}}</td>
                        <td>{{$item->cab}}</td>
                        <td>{{$item->no_ktp}}</td>
                        <td></td>
                        <td>{{$item->no_telepon}}</td>
                        <td>{{$item->jenis_kelamin}}</td>
                        <td>{{$item->no_peserta}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->tanggal_lahir ? date('d-M-Y',strtotime($item->tanggal_lahir)) : '-'}}</td>
                        <td class="text-center">{{$item->usia}}</td>
                        <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                        <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                        <td class="text-right">{{format_idr($item->basic)}}</td>
                        <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                        <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                        <td>
                            @if($item->use_em==0)
                                <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                            @else
                                <span class="text-right">{{format_idr($item->extra_mortalita)}}</span>
                                <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                            @endif
                        </td>
                        <td>
                            @if($item->extra_kontribusi)
                                {{format_idr($item->extra_kontribusi)}}
                            @else
                                <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                            @endif
                        </td>
                        <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}</td>
                        <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                        <td>{{$item->ul}}</td>
                        <td>{{$item->keterangan}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_extra_kontribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-extra-kontribusi')
</div>
<div wire:ignore.self class="modal fade" id="modal_add_em" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-em')
</div>
<div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.upload-kepesertaan',['data'=>$data])
</div>
<div wire:ignore.self class="modal fade" id="modal_uw_limit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.underwriting-limit',['data'=>$data])
</div>
<div wire:ignore.self class="modal fade" id="modal_check_double" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.kepesertaan-check-double',['data'=>$data])
</div>
