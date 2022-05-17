@section('title', 'Others Payable / Receivable')
@section('parentPageTitle', '')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#payable">Payable</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#receivable">Receivable</a></li>
                </ul>
                <div class="px-0 tab-content">
                    <div class="tab-pane show active" id="payable">
                        @livewire('accounting.others.payable')
                    </div>
                    <div class="tab-pane" id="receivable">
                        @livewire('accounting.others.receivable')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>