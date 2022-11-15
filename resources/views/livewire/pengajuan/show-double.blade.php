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
                            <th>No Pengajuan</th>
                            <th>Nama Bank</th>
                            <th>KC/KP</th>
                            <th>No KTP</th>
                            <th>No Telepon</th>
                            <th>Gender</th>
                            <th>No Peserta</th>
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
                                <td>
                                    @if(isset($item->pengajuan->no_pengajuan))
                                        <a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">{{$item->pengajuan->no_pengajuan}}</a>
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
                                <td>{{$item->tinggi_badan}}</td>
                                <td>{{$item->berat_badan}}</td>
                                <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                <td class="text-center">{{$item->masa_bulan}}</td>
                                <td class="text-right">{{format_idr($item->basic)}}</td>
                                <td class="text-right">
                                    @php($dana_tabarru = ($item->kontribusi*$item->polis->iuran_tabbaru)/100)
                                    {{format_idr($dana_tabarru)}}
                                </td>
                                <td class="text-right">
                                    @php($dana_ujrah = ($item->kontribusi*$item->polis->ujrah_atas_pengelolaan)/100) 
                                    {{format_idr($dana_ujrah)}}
                                </td>
                                <td class="text-right">
                                    {{format_idr($item->kontribusi)}}
                                </td>
                                <td class="text-right">{{format_idr($item->extra_mortalita)}}</td>
                                <td class="text-right">{{format_idr($item->extra_kontribusi)}}</td>
                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi)}}</td>
                                <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if($data)
                        @php($nilai_manfaat_approve = $data->sum('basic'))
                        @php($dana_tabbaru_approve = $data->sum('dana_tabarru'))
                        @php($dana_ujrah_approve = $data->sum('dana_ujrah'))
                        @php($kontribusi_approve = $data->sum('kontribusi'))
                        @php($extra_mortalita_approve = $data->sum('extra_mortalita'))
                        @php($extra_kontribusi_approve = $data->sum('extra_kontribusi'))
                        <tfoot style="background: #eee;">
                            <tr>
                                <th colspan="16" class="text-right">Akumulasi</th>
                                <th class="text-right">{{format_idr($nilai_manfaat_approve)}}</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <span wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
            <a href="javascript:void(0)" wire:loading.remove data-dismiss="modal" aria-label="Close" class="btn btn-secondary">Close</a>
        </div>
    </div>
</div>
