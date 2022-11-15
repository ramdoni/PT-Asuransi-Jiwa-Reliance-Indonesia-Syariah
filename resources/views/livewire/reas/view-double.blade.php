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
                            <th class="text-right">Nilai Manfaat Asuransi Reas</th>
                            <th class="text-right">Nilai Manfaat Asuransi Ajri</th>
                            <th class="text-right">Total Kontribusi</th>
                            <th class="text-right">Kontribusi Netto Reas</th>
                            <th>Tgl Stnc</th>
                            <th>UL</th>
                            <th>Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total_manfaat_reas=0)
                        @php($total_manfaat_ajri=0)
                        @php($total_basic=0)
                        @php($total_kontribusi=0)
                        @php($total_kontribusi_reas=0)
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
                                <td class="text-right">
                                    {{format_idr($item->basic)}}
                                    @php($total_basic += $item->basic)
                                </td>
                                <td class="text-right">
                                    {{format_idr($item->nilai_manfaat_asuransi_reas)}}
                                    @php($total_manfaat_reas +=$item->nilai_manfaat_asuransi_reas)
                                </td>
                                <td class="text-right">
                                    {{format_idr($item->reas_manfaat_asuransi_ajri)}}
                                    @php($total_manfaat_ajri +=$item->reas_manfaat_asuransi_ajri)
                                </td>
                                <td class="text-right">
                                    {{format_idr($item->extra_mortalita+$item->kontribusi)}}
                                    @php($total_kontribusi += $item->extra_mortalita+$item->kontribusi)
                                </td>
                                <td class="text-right">
                                    {{format_idr($item->net_kontribusi_reas)}}
                                    @php($total_kontribusi_reas += $item->extra_mortalita+$item->kontribusi)
                                </td>
                                <td>{{$item->tgl_stnc ? date('d-M-Y',strtotime($item->tgl_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #eee;">
                            <th colspan="14" class="text-right">Total</th>
                            <th class="text-right">{{format_idr($total_basic)}}</th>
                            <th class="text-right">{{format_idr($total_manfaat_reas)}}</th>
                            <th class="text-right">{{format_idr($total_manfaat_ajri)}}</th>
                            <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                            <th class="text-right">{{format_idr($total_kontribusi_reas)}}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
