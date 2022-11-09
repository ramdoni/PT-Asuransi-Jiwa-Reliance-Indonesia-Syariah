<div class="table-responsive">
    <table class="table table-hover table-bordered m-b-0 c_list table-nowrap">
        <thead style="background: #eee;">
            <tr>
                <th>No</th>
                <th class="text-center">
                    <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                </th>
                <th>
                    Double : {{$total_double}}<br />
                    Total : {{$total_pengajuan}}<br />
                </th>
                <th>
                    @if($total_selected>0)
                        <a href="javascript:void(0)" wire:loading.remove wire:target="delete_selected" wire:click="delete_selected" class="badge badge-danger badge-active"><i class="fa fa-trash"></i> Delete Selected</a>
                        <div wire:loading wire:target="delete_selected">
                            <span>
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                        </div>
                    @endif
                </th>
                <th>Hitung</th>
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
            </tr>
        </thead>
        <tbody>
            @php($num=$kepesertaan ? $kepesertaan->firstItem() : '')
            @foreach($kepesertaan as $k => $item)
                <tr style="{{$item->is_double==1?'background:#17a2b854':''}}" title="{{$item->is_double==1?'Data Ganda':''}}">
                    <td>{{$num}}@php($num++)</td>
                    <td class="text-center">
                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                    </td>
                    <td class="text-center">
                        @if($item->is_double==2)
                            <i class="fa fa-warning text-warning"></i>
                        @else
                            <i class="fa fa-check-circle text-success"></i>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0)" wire:loading.remove wire:target="delete({{$item->id}})" wire:click="delete({{$item->id}})"><i class="fa fa-trash text-danger"></i></a>
                        <span wire:loading wire:target="delete({{$item->id}})">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </td>
                    <td class="text-center">
                        @if($item->is_hitung==1)
                            <span title="Sudah dihitung"><i class="text-success fa fa-check-circle"></i></span>
                        @else
                            <span title="Belum dihitung"><i class="text-danger fa fa-close"></i></span>
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
                    <td>{{$item->masa_bulan}}</td>
                    <td class="text-right">
                        @if($item->is_double==1 || $item->akumulasi_ganda)
                            <a href="javascript:void(0)" wire:click="$emit('modal_show_double',{{$item->id}})">{{format_idr($item->basic)}}</a>
                        @else
                            {{format_idr($item->basic)}}
                        @endif
                        @php($total_nilai_manfaat += $item->basic)
                    </td>
                    <td class="text-right">
                        {{format_idr($item->dana_tabarru)}}
                        @php($total_dana_tabbaru += $item->dana_tabarru)
                    </td>
                    <td class="text-right">
                        {{format_idr($item->dana_ujrah)}}
                        @php($total_dana_ujrah += $item->dana_ujrah)
                    </td>
                    <td class="text-right">
                        {{format_idr($item->kontribusi)}}
                        @php($total_kontribusi += $item->kontribusi)
                    </td>
                    <td>
                        @if($item->use_em==0)
                            <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                        @else
                            <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">
                                {{format_idr($item->extra_mortalita)}}
                                @php($total_em += $item->extra_mortalita)
                            </span></a>
                            <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                        @endif
                    </td>
                    <td>
                        @if($item->extra_kontribusi)
                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">
                                {{format_idr($item->extra_kontribusi)}}
                                @php($total_ek += $item->extra_ek)
                            </a>
                        @else
                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                        @endif
                    </td>
                    <td class="text-right">
                        {{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}
                        @php($total_total_kontribusi += $item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)
                    </td>
                    <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                    <td>{{$item->ul}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="22">
                    <div wire:loading>
                        <span>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        load data...
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot style="background: #eee;">
            <tr>
                <th colspan="19">Total</th>
                <th class="text-right">{{format_idr($total_nilai_manfaat)}}</th>
                <th class="text-right">{{format_idr($total_dana_tabbaru)}}</th>
                <th class="text-right">{{format_idr($total_dana_ujrah)}}</th>
                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                <th class="text-right">{{format_idr($total_em)}}</th>
                <th class="text-right">{{format_idr($total_ek)}}</th>
                <th class="text-right">{{format_idr($total_total_kontribusi)}}</th>
            </tr>
        </tfoot>
    </table>
    <br />
    {{ $kepesertaan ? $kepesertaan->links() : ''}}
</div>
