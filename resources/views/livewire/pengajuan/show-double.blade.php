<div class="modal-dialog modal-lg" style="min-width:90%" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Double Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive"> 
                <table class="table table-hover m-b-0 c_list table-nowrap" id="table_reject">
                    <thead style="background: #eee;text-transform: uppercase;">
                        <tr>
                            <th>No</th>
                            <th>Nama Bank</th>
                            <th>KC/KP</th>
                            <th>No KTP</th>
                            <th>No Telepon</th>
                            <th>Gender</th>
                            <th>Nama Peserta</th>
                            <th>Tgl. Lahir</th>
                            <th>Usia</th>
                            <th>TB</th>
                            <th>BB</th>
                            <th>Mulai Asuransi</th>
                            <th>Akhir Asuransi</th>
                            <th>Masa Asuransi</th>
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
                        @foreach($data as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$item->bank}}</td>
                                <td>{{$item->cab}}</td>
                                <td>{{$item->no_ktp}}</td>
                                <td>{{$item->no_telepon}}</td>
                                <td>{{$item->jenis_kelamin}}</td>
                                <td>
                                    @if($item->is_double==1)
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_show_double" wire:click="set_id({{$item->id}})">{{$item->no_peserta}}</a>
                                    @else
                                        {{$item->no_peserta}}
                                    @endif
                                </td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->tanggal_lahir ? date('d-M-Y',strtotime($item->tanggal_lahir)) : '-'}}</td>
                                <td class="text-center">{{$item->usia}}</td>
                                <td>{{$item->tinggi_badan}}</td>
                                <td>{{$item->berat_badan}}</td>
                                <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                <td class="text-center">{{$item->masa_bulan}}</td>
                                <td class="text-right">{{format_idr($item->basic)}}</td>
                                <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                <td class="text-right">{format_idr($item->extra_mortalita)}}</td>
                                <td class="text-right">{{format_idr($item->extra_kontribusi)}}</td>
                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi)}}</td>
                                <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)"data-dismiss="modal" aria-label="Close" class="btn btn-secondary">Close</a>
        </div>
    </div>
</div>
