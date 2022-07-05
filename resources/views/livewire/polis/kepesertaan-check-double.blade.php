<div class="modal-dialog modal-lg" style="max-width:90%;"  role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-database"></i> Data Ganda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Pencarian..." />
                </div>
                <div class="col-md-9">
                    @if(count($check_id)>0)
                        <a href="javascript:void(0)" wire:click="keepAll" class="btn btn-success"><i class="fa fa-check-circle"></i> Keep All</a>
                        <a href="javascript:void(0)" wire:click="deleteAll" class="btn btn-danger"><i class="fa fa-trash"></i> Delete All</a>
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
                            <th class="text-center">
                                <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                            </th>
                            <th></th>
                            <th></th>
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
                        @foreach($kepesertaan as $k => $item)
                            <tr>
                                <td rowspan="2">{{$k+1}}</td>
                                <td></td>
                                <td>Existing</td>
                                <td></td>
                                <td>{{isset($item->parent->bank) ? $item->parent->bank : '-'}}</td>
                                <td>{{isset($item->parent->cab) ? $item->parent->cab : '-'}}</td>
                                <td>{{isset($item->parent->no_ktp) ? $item->parent->no_ktp : '-'}}</td>
                                <td>{{isset($item->parent->no_telepon) ? $item->parent->no_telepon : '-'}}</td>
                                <td>{{isset($item->parent->jenis_kelamin) ? $item->parent->jenis_kelamin : '-'}}</td>
                                <td>{{isset($item->parent->no_peserta) ? $item->parent->no_peserta : '-'}}</td>
                                <td>{{isset($item->parent->nama) ? $item->parent->nama : '-'}}</td>
                                <td>{{isset($item->parent->tanggal_lahir) ? date('d-M-Y',strtotime($item->parent->tanggal_lahir)) : '-'}}</td>
                                <td class="text-center">{{isset($item->parent->usia) ? $item->parent->usia : '-'}}</td>
                                <td>{{isset($item->parent->tanggal_mulai) ? date('d-M-Y',strtotime($item->parent->tanggal_mulai)) : '-'}}</td>
                                <td>{{isset($item->parent->tanggal_akhir) ? date('d-M-Y',strtotime($item->parent->tanggal_akhir)) : '-'}}</td>
                                <td class="text-right">{{isset($item->parent->basic) ? format_idr($item->parent->basic) : '-'}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="background:#ff000024">
                                <td class="text-center"><input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" /> </td>
                                <td>Double</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="keep({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Keep</a>
                                    <a href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger" title="Hapus Data"><i class="fa fa-trash"></i></a>
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
                            <tr style="background:#eae9e9;">
                                <td colspan="10"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
