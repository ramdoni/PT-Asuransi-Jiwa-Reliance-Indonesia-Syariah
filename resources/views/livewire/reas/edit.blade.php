@section('sub-title', $no_pengajuan)
@section('title', 'Reasuransi')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    @if($data->dn_number)
                                        <tr>
                                            <td><strong>Debit Note Number</strong></td>
                                            <td>: 
                                                {{$data->dn_number}}
                                                @if($data->dn_number)
                                                    <a href="{{route('pengajuan.print-dn',$data->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>No Pengajuan</strong></td>
                                        <td>: {{$no_pengajuan}} 
                                            {{-- <a href="javascript:void(0)" wire:loading.remove wire:target="hitung" wire:click="hitung"><i class="fa fa-refresh"></i></a>
                                            <span wire:loading wire:target="hitung">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pengajuan</strong></td>
                                        <td> : {{date('d F Y',strtotime($data->created_at))}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if($data->status==0)
                                                <span class="badge badge-warning">Underwriting</span>
                                            @endif
                                            @if($data->status==1)
                                                <span class="badge badge-info">Head Teknik</span>
                                            @endif
                                            @if($data->status==2)
                                                <span class="badge badge-info">Head Syariah</span>
                                            @endif
                                            @if($data->status==3)
                                                <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reasuradur</th>
                                        <td> : {{$data->reasuradur->name ? $data->reasuradur->name : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Rate & UW Limit</th>
                                        <td> : {{isset($data->rate_uw->nama) ? $data->rate_uw->nama : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>OR</th>
                                        <td> : {{$data->or}}%</td>
                                    </tr>
                                    <tr>
                                        <th>Reas</th>
                                        <td>: {{$data->reas}}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Jumlah Peserta</th>
                                        <td> : {{format_idr($data->jumlah_peserta)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Uang Asuransi Ajri</th>
                                        <td> : {{format_idr($data->manfaat_asuransi_ajri)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Uang Asuransi Reas</th>
                                        <td> : {{format_idr($data->manfaat_asuransi_reas)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Kontribusi Gross</th>
                                        <td> : {{format_idr($data->kontribusi)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Ujroh</th>
                                        <td> : {{format_idr($data->ujroh)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Kontribusi Netto</th>
                                        <td> : {{format_idr($data->kontribusi_netto)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="javascript:void(0)" wire:click="hitung" wire:loading.remove wire:target="hitung" class="badge badge-warning badge-active"><i class="fa fa-reload"></i> Hitung Reas</a>
                                            <span wire:loading wire:target="hitung">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive"> 
                        <table class="table table-hover m-b-0 c_list table-nowrap vertical-align-middle" id="table_postpone">
                            <thead style="text-transform: uppercase;">
                                <tr>
                                    <th>No</th>
                                    <th class="text-center">
                                        <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                                    </th>
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
                                    <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr($data->extra_kontribusi)}}</span></th>
                                    <th class="text-right">Extra Risk<br /><span class="sub_total"></span></th>
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
                                        <td class="text-center">
                                            @if($data->status==0 and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                            @if($data->status==2 and\Auth::user()->user_access_id==4)
                                                <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
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
                                                    <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                </div>
                                            @endif
                                            {{-- Head Teknik --}}
                                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                    <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                </div>
                                            @endif
                                            {{-- Head Syariah --}}
                                            @if($data->status==2 and \Auth::user()->user_access_id==4)
                                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                    <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
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
                                        <td class="text-right">{{$item->reas_manfaat}}</td>                                   
                                        <td class="text-right">{{$item->reas_type}}</td>                                   
                                        <td class="text-right">{{format_idr($item->basic)}}</td>
                                        <td class="text-right">{{format_idr($item->nilai_manfaat_asuransi_reas)}}</td>        
                                        <td class="text-right">{{format_idr($item->reas_manfaat_asuransi_ajri)}}</td>                         
                                        <td class="text-right">{{$item->reas_manfaat}}</td>                         
                                        <td class="text-right">{{$item->reas_type}}</td>     
                                        <td>{{format_idr($item->total_kontribusi_reas)}}</td>                         
                                        <td>{{format_idr($item->ujroh_reas)}}</td>                         
                                        <td>{{format_idr($item->net_kontribusi_reas)}}</td>    
                                        <td class="text-center">{{$item->ul_reas}}</td>    
                                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>                     
                                        <td class="text-center">{{$item->ul}}</td>                     
                                    </tr>
                                @endforeach
                                @if($data->kepesertaan->count()==0)
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
                    <hr />
                    <div class="form-group">
                        <a href="javascript:void(0)" class="mr-2" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <span wire:loading wire:target="submit_head_teknik,submit_head_syariah,submit_underwriting">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($data->kepesertaan->where('status_akseptasi',0)->count() == 0)
                            @if($data->status==0 and (\Auth::user()->user_access_id==2 || \Auth::user()->user_access_id==1))
                                <button type="button" wire:loading.remove wire:target="submit_underwriting" wire:click="submit_underwriting" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                <button type="button" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                            @if($data->status==2 and \Auth::user()->user_access_id==4)
                                <button type="button" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
