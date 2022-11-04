<div class="modal-dialog modal-lg" style="max-width:90%;"  role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Double Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered m-b-0 c_list">
                    <thead style="background: #eee;">
                        <tr>
                            <th>No</th>
                            <th>No Pengajuan</th>
                            <th>No Pengajuan Reas</th>
                            <th>Nama Bank</th>
                            <th>KC/KP</th>
                            <th>No KTP</th>
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
                                <td>
                                    @if(isset($item->pengajuan->no_pengajuan))
                                        <a href="{{route('pengajuan.edit',['data'=>$item->pengajuan_id])}}" target="_blank">{{$item->pengajuan->no_pengajuan}}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(isset($item->reas->no_pengajuan))
                                        <a href="{{route('reas.edit',$item->reas_id)}}" target="_blank">{{$item->reas->no_pengajuan}}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{$item->bank}}</td>
                                <td>{{$item->cab}}</td>
                                <td>{{$item->no_ktp}}</td>
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
                                <td class="text-right">{{format_idr($item->extra_mortalita)}}</td>
                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi)}}</td>
                                <td>{{$item->tgl_stnc ? date('d-M-Y',strtotime($item->tgl_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
