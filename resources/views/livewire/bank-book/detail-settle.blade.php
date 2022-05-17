<div class="modal-dialog" style="min-width: 95%;" role="document">
    <div class="modal-content">
        <form wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Settle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 table-responsive">
                        <div class="form-group">
                            <table class="table table-bordered">
                                <tr style="background:#eee">
                                    <th>No</th>
                                    <th>No Voucher</th>
                                    <th>Voucher Date</th>
                                    <th>Note</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                                @foreach($vouchers as $k => $item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{$item->no_voucher}}</td>
                                        <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                        <td>{{$item->note}}</td>
                                        <td class="text-right">{{format_idr($item->amount)}}</td>
                                    </tr>
                                    @php($total_voucher +=$item->amount)
                                @endforeach
                                <tr style="background:#eee">
                                    <td></td>
                                    <td></td>
                                    <th></th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_voucher)}}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 table-responsive">
                        <table class="table table-bordered mb-0">
                            <tr style="background:#eee">
                                <th>No</th>
                                <th>Type</th>
                                <th>Debit Note / Kwitansi</th>
                                <th>Description</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            @php($total_incomes=0)
                            @php($num=1)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>{{$item->type}}</td>
                                    <td>{{$item->dn}}</td>
                                    <td>{{$item->description}}</td>
                                    <td class="text-right">{{format_idr($item->amount)}}</td>
                                </tr>
                                @php($total_incomes += $item->amount)
                                @php($num++)
                            @endforeach
                            <tr style="background:#eee">
                                <td></td>
                                <td></td>
                                <td></td>
                                <th class="text-right">Total</th>
                                <th class="text-right">{{format_idr($total_incomes)}}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span wire:loading>
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Close</a>
            </div>
        </form>
    </div>
</div>