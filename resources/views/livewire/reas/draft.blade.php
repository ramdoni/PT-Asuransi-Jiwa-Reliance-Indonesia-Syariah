<div>
    <div class="table-responsive">
        <table class="table table-hover m-b-0 c_list table-nowrap vertical-align-middle" id="table_postpone">
            <thead style="text-transform: uppercase;">
                <tr>
                    <th>No</th>
                    <th>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($reassign)
                            <label>
                                <input type="checkbox" value="1" wire:model="check_all" wire:click="checked_all" /> Check All
                            </label>
                        @endif
                    </th>
                    <th>No Pengajuan</th>
                    <th>No DN</th>
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
                    <th>Manfaat</th>
                    <th>Type Reas</th>
                    <th class="text-right">Kontribusi AJRI<br /><span class="sub_total">{{format_idr($kepesertaan->sum('extra_mortalita')+$kepesertaan->sum('kontribusi')+$kepesertaan->sum('extra_kontribusi'))}}</span></th>
                    <th>UW Limit</th>
                </tr>
            </thead>
            <tbody>
                @php($index_proses = 0)
                @foreach($kepesertaan as $k => $item)
                    @php($index_proses++)
                    <tr style="{{$item->is_double_reas==1?'background:#17a2b854':''}}" title="{{$item->is_double_reas==1?'Data Ganda':''}}">
                        <td>{{$index_proses}}</td>
                        <td>
                            @if($reassign)
                                <input type="checkbox" wire:model="assign_id.{{$item->id}}" value="{{$item->id}}" />
                            @endif
                        </td>
                        <td><a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">{{isset($item->pengajuan->no_pengajuan) ? $item->pengajuan->no_pengajuan : '-'}}</a></td>
                        <td>
                            @if(isset($item->pengajuan->dn_number))
                                <a href="{{route('pengajuan.print-dn',$item->pengajuan->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                {{$item->pengajuan->dn_number?$item->pengajuan->dn_number:'-'}}
                            @endif
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
                        <td class="text-right">{{$item->reas_manfaat}}</td>
                        <td class="text-right">{{$item->reas_type}}</td>
                        <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi)}}</td>
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
                    <th colspan="15" class="text-right">Total</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
