@section('title', __('Index'))
@section('parentPageTitle', __('Bank Book'))
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="tabbable">
                    <ul class="nav nav-tabs-new2">
                        @foreach(\App\Models\BankAccount::where('is_client',0)->where('status',1)->get() as $k=>$item)
                            <li class="nav-item"><a class="nav-link {{$k==0?'active':''}}" data-toggle="tab" href="#bank-{{$item->id}}">{{ $item->bank }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-0 tab-content">
                    @foreach(\App\Models\BankAccount::where('is_client',0)->where('status',1)->get() as $k=> $item)
                        @livewire('bank-book.detail',['data'=>$item,'active'=>$k==0?true:false],key($item->id))
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<style>
    .tabbable .nav-tabs-new2 {
        overflow-x: auto;
        overflow-y:hidden;
        flex-wrap: nowrap;
    }
    .tabbable .nav-tabs-new2 .nav-link {
        white-space: nowrap;
    }
</style>
@endpush