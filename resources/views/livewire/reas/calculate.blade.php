<div>
    <div class="table-responsive">
        <table class="table table-hover m-b-0 c_list table-nowrap vertical-align-middle" id="table_postpone">
            <thead style="text-transform: uppercase;">
                <tr>
                    <th>No</th>
                    <th>No Pengajuan</th>
                    <th>No Polis</th>
                    <th>Nama Pemegang Polis</th>
                    <th>No Peserta</th>
                    <th>Nama Peserta</th>
                    <th>Gender</th>
                    <th>Tgl. Lahir</th>
                    <th>Usia</th>
                    <th>Mulai Asuransi</th>
                    <th>Akhir Asuransi</th>
                    <th>Jangka Waktu Asuransi</th>
                    <th class="text-right">Manfaat Asuransi<br /><span class="sub_total">{{format_idr($kepesertaan->sum('basic'))}}</span></th>
                    <th class="text-right">Manfaat Asuransi Reas<br /><span class="sub_total">{{format_idr($kepesertaan->sum('nilai_manfaat_asuransi_reas'))}}</span></th>
                    <th class="text-right">Manfaat Asuransi Ajri<br /><span class="sub_total">{{format_idr($kepesertaan->sum('reas_manfaat_asuransi_ajri'))}}</span></th>
                    <th>Manfaat</th>
                    <th>Type Reas</th>
                    <th>Rate</th>
                    <th class="text-right">Kontribusi Reas<br /><span class="sub_total">{{format_idr($kepesertaan->sum('total_kontribusi_reas'))}}</span></th>
                    <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr($kepesertaan->sum('reas_extra_kontribusi'))}}</span></th>
                    <th class="text-right">Ujroh<br /><span class="sub_total">{{format_idr($kepesertaan->sum('ujroh_reas'))}}</span></th>
                    <th class="text-right">Kontribusi Netto<br /><span class="sub_total">{{format_idr($kepesertaan->sum('net_kontribusi_reas'))}}</span></th>
                    <th>Akseptasi</th>
                    <th class="text-right">Kontribusi AJRI<br /><span class="sub_total">{{format_idr($kepesertaan->sum('kontribusi'))}}</span></th>
                    <th>UW Limit</th>
                </tr>
            </thead>
            <tbody>
                @php($index_proses = 0)
                @foreach($kepesertaan as $k => $item)
                    @php($index_proses++)
                    <tr style="{{$item->is_double==1?'background:#17a2b854':''}}" title="{{$item->is_double==1?'Data Ganda':''}}">
                        <td>{{$index_proses}}</td>
                        <td>
                            @if($reassign)
                                <input type="checkbox" wire:model="assign_id.{{$k}}" value="{{$item->id}}" />
                            @endif
                        </td>
                        <td>
                            <a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">{{isset($item->pengajuan->no_pengajuan) ? $item->pengajuan->no_pengajuan : '-'}}</a>
                        </td>
                        <td>{{isset($item->polis->no_polis) ? $item->polis->no_polis : '-'}}</td>
                        <td>{{isset($item->polis->nama) ? $item->polis->nama : '-'}}</td>
                        <td>{{$item->no_peserta}}</td>
                        <td>{{$item->nama}}</td>
                        <td class="text-center">{{$item->jenis_kelamin}}</td>
                        <td>{{date('d-m-Y',strtotime($item->tanggal_lahir))}}</td>
                        <td class="text-center">{{$item->usia}}</td>
                        <td>{{date('d-m-Y',strtotime($item->tanggal_mulai))}}</td>
                        <td>{{date('d-m-Y',strtotime($item->tanggal_akhir))}}</td>
                        <td class="text-center">{{$item->masa_bulan}}</td>
                        <td class="text-right">{{format_idr($item->basic)}}</td>
                        <td class="text-right">{{format_idr($item->nilai_manfaat_asuransi_reas)}}</td>
                        <td class="text-right">{{format_idr($item->reas_manfaat_asuransi_ajri)}}</td>
                        <td class="text-right">{{$item->reas_manfaat}}</td>
                        <td class="text-right">{{$item->reas_type}}</td>
                        <td class="text-right">{{$item->rate_reas}}</td>
                        <td class="text-right">{{format_idr($item->total_kontribusi_reas)}}</td>
                        <td class="text-right">
                            <a href="javascript:void(0)" wire:click="$emit('add-extra-kontribusi',{{$item->id}})">
                                @if($item->reas_extra_kontribusi==0)
                                    <i class="fa fa-plus"></i>
                                @else
                                    {{format_idr($item->reas_extra_kontribusi)}}
                                @endif
                            </a>
                        </td>
                        <td class="text-right">{{format_idr($item->ujroh_reas)}}</td>
                        <td class="text-right">{{format_idr($item->net_kontribusi_reas)}}</td>
                        <td class="text-center">{{$item->ul_reas}}</td>
                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                        <td class="text-center">{{$item->ul}}</td>
                    </tr>
                @endforeach
                @if($kepesertaan->count()==0)
                    <tr>
                        <td colspan="26">Empty</td>
                    </tr>
                @endif
            </tbody>
            <tfoot style="background: #eee;">
                <tr>
                    <th colspan="16" class="text-right">Total</th>

                </tr>
            </tfoot>
        </table>
    </div>
</div>
