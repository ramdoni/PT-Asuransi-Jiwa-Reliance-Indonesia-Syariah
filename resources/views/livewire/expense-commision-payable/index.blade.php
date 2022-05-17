@section('title', 'Account Payable')
@section('parentPageTitle', 'Commision Payable')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="row">
            @if($paging_total_==1)
            <div class="col-md-3">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Fee Base</h6>
                            <span>{{ format_idr($fee_base) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Maintenance</h6>
                            <span>{{ format_idr($maintenance) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-warning">Admin Agency</h6>
                            <span>{{ format_idr($admin_agency) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-yellow  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-danger">Agen Penutup</h6>
                            <span>{{ format_idr($agen_penutup) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-red  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="background: red;width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="$set('paging_total_',2)" style="position: absolute;right: 0;top: 16px;"><i class="fa fa-angle-right"></i></button>
            @endif
            @if($paging_total_==2)
            <div class="col-md-3">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Operasional Agency</h6>
                            <span>{{ format_idr($operasional_agency) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Handling Fee Broker</h6>
                            <span>{{ format_idr($handling_fee_broker) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-warning">Referal Fee</h6>
                            <span>{{ format_idr($referal_fee) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-yellow  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="$set('paging_total_',1)" style="position: absolute;left: 0;top: 16px;"><i class="fa fa-angle-left"></i></button>
            @endif
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="unit">
                            <option value=""> --- Unit --- </option>
                            <option value="1">[K] Konven </option>
                            <option value="2">[S] Syariah</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="status">
                            <option value=""> --- Status --- </option>
                            <option value="2"> Paid</option>
                            <option value="4"> Draft</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <a href="{{route('expense.commision-payable.insert')}}" class="btn btn-success"><i class="fa fa-plus"></i> Commision Payable</a>
                        <a href="javascript:;" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
                        <div wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>                                    
                                <th rowspan="2">Status</th>                                    
                                <th rowspan="2">Voucher number</th>                                      
                                <th rowspan="2">Settle Date</th>  
                                <th rowspan="2">Debit Note / Kwitansi</th>
                                <th rowspan="2">Policy Number / Policy Holder</th>      
                                <th rowspan="2" class="text-right">Total Payment</th>
                                <th colspan="4" class="text-center">Fee Base</th>
                                <th colspan="4" class="text-center">Maintenance</th>
                                <th colspan="4" class="text-center">Admin Agency</th>
                                <th colspan="4" class="text-center">Agen Penutup</th>
                                <th colspan="4" class="text-center">Operasional Agency</th>
                                <th colspan="4" class="text-center">Handling Fee Broker</th>
                                <th colspan="4" class="text-center">Referal Fee</th>
                            </tr>
                            <tr>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                                <th>Biaya</th>
                                <th>Nama Penerima</th>
                                <th>Bank Penerima</th>
                                <th>Rekening Penerima</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$data->firstItem()+$k}}</td>
                                <td>
                                    {!!status_expense($item->status)!!}
                                    @if($item->status==4)
                                    <a href="javascript:;" class="text-danger" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($item->bank_books))
                                        @foreach($item->bank_books as $k => $bank_book)
                                            @if($k>0) @continue @endif
                                            @if($bank_book->bank_books->no_voucher) 
                                                <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher">{{$bank_book->bank_books->no_voucher}}</a>
                                            @endif
                                        @endforeach
                                        @if($item->bank_books->count()>1) <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher"><i class="fa fa-plus"></i></a> @endif
                                    @endif
                                </td>
                                <td>{{$item->settle_date ? date('d M Y', strtotime($item->settle_date)) : '-'}}</td>
                                <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                                <td>{{$item->recipient ? $item->recipient : '-'}}</td>
                                <td class="text-right">{{format_idr($item->payment_amount)}}</td>
                                
                                <td>{{isset($item->payment_fee_base->payment_amount) ? format_idr($item->payment_fee_base->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_fee_base->name) ? $item->payment_fee_base->name : '-'}}</td>
                                <td>{{isset($item->payment_fee_base->bank) ? $item->payment_fee_base->bank : '-'}}</td>
                                <td>{{isset($item->payment_fee_base->account_number) ? $item->payment_fee_base->account_number : '-'}}</td>

                                <td>{{isset($item->payment_maintenance->payment_amount) ? format_idr($item->payment_maintenance->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_maintenance->name) ? $item->payment_maintenance->name : '-'}}</td>
                                <td>{{isset($item->payment_maintenance->bank) ? $item->payment_maintenance->bank : '-'}}</td>
                                <td>{{isset($item->payment_maintenance->account_number) ? $item->payment_maintenance->account_number : '-'}}</td>
                                
                                <td>{{isset($item->payment_admin_agency->payment_amount) ? format_idr($item->payment_admin_agency->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_admin_agency->name) ? $item->payment_admin_agency->name : '-'}}</td>
                                <td>{{isset($item->payment_admin_agency->bank) ? $item->payment_admin_agency->bank : '-'}}</td>
                                <td>{{isset($item->payment_admin_agency->account_number) ? $item->payment_admin_agency->account_number : '-'}}</td>

                                <td>{{isset($item->payment_agen_penutup->payment_amount) ? format_idr($item->payment_agen_penutup->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_agen_penutup->name) ? $item->payment_agen_penutup->name : '-'}}</td>
                                <td>{{isset($item->payment_agen_penutup->bank) ? $item->payment_agen_penutup->bank : '-'}}</td>
                                <td>{{isset($item->payment_agen_penutup->account_number) ? $item->payment_agen_penutup->account_number : '-'}}</td>

                                <td>{{isset($item->payment_operasional_agency->payment_amount) ? format_idr($item->payment_operasional_agency->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_operasional_agency->name) ? $item->payment_operasional_agency->name : '-'}}</td>
                                <td>{{isset($item->payment_operasional_agency->bank) ? $item->payment_operasional_agency->bank : '-'}}</td>
                                <td>{{isset($item->payment_operasional_agency->account_number) ? $item->payment_operasional_agency->account_number : '-'}}</td>

                                <td>{{isset($item->payment_handling_fee_broker->payment_amount) ? format_idr($item->payment_handling_fee_broker->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_handling_fee_broker->name) ? $item->payment_handling_fee_broker->name : '-'}}</td>
                                <td>{{isset($item->payment_handling_fee_broker->bank) ? $item->payment_handling_fee_broker->bank : '-'}}</td>
                                <td>{{isset($item->payment_handling_fee_broker->account_number) ? $item->payment_handling_fee_broker->account_number : '-'}}</td>

                                <td>{{isset($item->payment_referal_fee->payment_amount) ? format_idr($item->payment_referal_fee->payment_amount) : '-'}}</td>
                                <td>{{isset($item->payment_referal_fee->name) ? $item->payment_referal_fee->name : '-'}}</td>
                                <td>{{isset($item->payment_referal_fee->bank) ? $item->payment_referal_fee->bank : '-'}}</td>
                                <td>{{isset($item->payment_referal_fee->account_number) ? $item->payment_referal_fee->account_number : '-'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" wire:ignore.self id="modal_detail_voucher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('expense-reinsurance.detail-voucher')
</div>