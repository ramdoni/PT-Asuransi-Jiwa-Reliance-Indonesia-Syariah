<div class="modal-content">
    <form wire:submit.prevent="save">
        <div class="row p-3">
            <div class="col-md-2">
                <input type="number" class="form-control" wire:model="keyword" placeholder="Searching Amount" />
            </div>
            <div class="col-md-7">
                <span class="alert alert-info">Premium Receivable : Rp. </label> {{format_idr($data->nominal)}}</span>
                <span class="alert alert-success">Voucher : {{format_idr($total)}}</span>
                <div wire:loading class="mt-1 ml-3">
                    <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-b-0 c_list">
                    <thead>
                        <tr>
                            <th></th>                                          
                            <th>Voucher Number</th>       
                            <th class="text-right">Amount</th>
                            <th class="text-right">Balance Usage</th>                               
                            <th class="text-right">Balance Remain</th>                               
                            <th>Voucher Date</th>           
                            <th>From Bank</th>
                            <th>To Bank</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $k => $item)
                        <tr>
                            <td>
                                <div class="form-group mb-0">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" {{($is_disabled and !in_array($item->id,$check_id))?'disabled':'' }} wire:model="check_id" value="{{$item->id}}">
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                            <td>{{$item->no_voucher}}</td>
                            <td>{{isset($item->amount) ? format_idr($item->amount) : '-'}}</td>
                            <td class="text-right">{{format_idr($item->balance_usage)}}</td>
                            <td class="text-right">{{format_idr($item->balance_remain)}}</td>
                            <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                            <td>{{isset($item->from_bank->no_rekening) ? $item->from_bank->no_rekening .'- '.$item->from_bank->bank.' an '. $item->from_bank->owner : '-'}}</td>
                            <td>{{isset($item->to_bank->no_rekening) ? $item->to_bank->no_rekening .'- '.$item->to_bank->bank.' an '. $item->to_bank->owner : '-'}}</td>
                            <td>{{$item->note}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <br />
            {{$lists->links()}}
        </div>
        <div class="modal-footer text-left" style="justify-content:left">
            @if($total!=0)
                <a href="javascript:void(0)" class="btn btn-info float-left" wire:click="submit">Submit</a>
            @endif
            <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Close</a>
        </div>
    </form>
</div>
@push('after-scripts')
<script>
    select__2 = $('.titipan_from_bank_account').select2();
    $('.titipan_from_bank_account').on('change', function (e) {
        var data = $(this).select2("val");
        @this.set('from_bank_account_id', data);
    });
    var selected__ = $('.titipan_from_bank_account').find(':selected').val();
    if(selected__ !="") select__2.val(selected__);
    
</script>
@endpush