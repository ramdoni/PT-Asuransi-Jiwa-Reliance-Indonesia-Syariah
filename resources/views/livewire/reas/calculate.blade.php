<div>
    <div class="table-responsive"> 
        <table class="table table-hover m-b-0 c_list table-nowrap vertical-align-middle" id="table_postpone">
            <thead style="text-transform: uppercase;">
                <tr>
                    <th>No</th>
                    <th class="text-center">Status</th>
                    <th>
                        @if(count($check_id)>0)
                            <span wire:loading wire:target="approveAll,rejectAll">
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                            <a href="javascript:void(0)" wire:click="approveAll" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima Semua</a>
                        @endif
                    </th>
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
                    <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th class="text-right">Manfaat Asuransi<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th class="text-right">Manfaat Asuransi Reas<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th class="text-right">Manfaat Asuransi Ajri<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th>Manfaat</th>
                    <th>Type Reas</th>
                    <th class="text-right">Kontribusi Reas<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th class="text-right">Ujroh<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th class="text-right">Kontribusi Netto<br /><span class="sub_total">{{format_idr(0)}}</span></th>
                    <th>Akseptasi</th>
                    <th class="text-right">Kontribusi AJRI<br /><span class="sub_total">{{format_idr(0)}}</span></th>
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
                            @if($item->status_reas==0)
                                <span class="badge badge-warning">Draft</span>
                            @endif
                            @if($item->status_reas==1)
                                <span class="badge badge-success">Calculate</span>
                            @endif
                            @if($item->status_reas==2)
                                <span class="badge badge-danger">Skip</span>
                            @endif
                        </td>
                        <td>
                            <span wire:loading wire:target="approve({{$item->id}})">
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                            {{-- Underwriting --}}
                            @if($data->status==0 and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                </div>
                            @endif
                            {{-- Head Teknik --}}
                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                </div>
                            @endif
                            {{-- Head Syariah --}}
                            @if($data->status==2 and \Auth::user()->user_access_id==4)
                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                </div>
                            @endif
                        </td>
                        <td><a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">{{isset($item->pengajuan->no_pengajuan) ? $item->pengajuan->no_pengajuan : '-'}}</a></td>
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
                        <td class="text-right">
                            <a href="javascript:void(0)" wire:click="$emit('add-extra-kontribusi',{{$item->id}})">
                                @if($item->reas_extra_kontribusi==0)
                                    <i class="fa fa-plus"></i>    
                                @else
                                    {{format_idr($item->reas_extra_kontribusi)}}
                                @endif
                            </a>
                        </td>                                           
                        <td class="text-right">{{format_idr($item->basic)}}</td>
                        <td class="text-right">{{format_idr($item->nilai_manfaat_asuransi_reas)}}</td>        
                        <td class="text-right">{{format_idr($item->reas_manfaat_asuransi_ajri)}}</td>                         
                        <td class="text-right">{{$item->reas_manfaat}}</td>                         
                        <td class="text-right">{{$item->reas_type}}</td>     
                        <td class="text-right">{{format_idr($item->total_kontribusi_reas)}}</td>                         
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
    {{$kepesertaan->links()}}
</div>