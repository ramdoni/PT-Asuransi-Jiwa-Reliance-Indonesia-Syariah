@section('title', 'Trial Balance')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <select class="form-control" wire:model="coa_id">
                        <option value=""> --- COA --- </option>
                        @foreach(\App\Models\Coa::orderBy('name','ASC')->get() as $k=>$i)
                        <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pl-0 col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="px-0 col-md-1">
                    <select class="form-control" wire:model="year">
                        <option value=""> -- Year -- </option>
                        @foreach(\App\Models\Journal::select( DB::raw( 'YEAR(date_journal) AS year' ))->groupBy('year')->get() as $i)
                        <option>{{$i->year}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="month">
                        <option value=""> --- Month --- </option>
                        @foreach(month() as $k=>$i)
                        <option value="{{$k}}">{{$i}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="px-0 col-md-4">
                    <a href="javascript:void(0)" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download Excel</a>
                </div>
            </div>
            <div class="pt-0 body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list table-hover">
                        <thead>
                            <tr>
                                <th>No</th>           
                                <th>COA</th>          
                                <th>No Rekening</th>          
                                <th>Sub Account</th>                                    
                                <th class="text-right">Opening Balance</th>                                    
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Ending Balance</th>
                                <th>Cek</th>
                                <th>Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            @php($debit = isset($item->journal)?$item->journal->sum('debit'):0)
                            @php($kredit = isset($item->journal)?$item->journal->sum('kredit'):0)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>{{isset($item->code)?$item->code:''}}</td>
                                <td></td>
                                <td>{{$item->name}}</td>
                                <td class="text-right">{{format_idr($item->opening_balance)}}</td>
                                <td class="text-right">{{format_idr($debit)}}</td>
                                <td class="text-right">{{format_idr($kredit)}}</td>
                                <td class="text-right">{{format_idr(($item->opening_balance?$item->opening_balance:0)+$debit-$kredit)}}</td>
                                <td>-</td>
                                <td class="text-right">{{format_idr(($item->opening_balance?$item->opening_balance:0)+$debit-$kredit)}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>