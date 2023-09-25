<html>
    <head>
        <title>Nomor Pengajuan : {{$data->nomor}}</title>
        <style>
            @page { margin: 0px; }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
            }
            h1 {font-size: 20px;}
            .container {padding-left:20px;padding-right:20px;}
            .text-center {
                text-align:center;
            }
            .text-right {
                text-align:right;
            }
            .text-left {
                text-align:left;
            }
            table {
                border-collapse: separate; 
                border-spacing: 0em;
            }
            table.style1 tr td {padding-top:5px;}
            table.border {
                border-left: 1px solid black;
                border-bottom: 1px solid black;
            }
            table.border tr td,table.border tr th {
                border-top:1px solid #000;
                border-right:1px solid #000;
                padding:2px 5px;
                margin:0;
            }
            /* table.border tr{
                border:1px solid #000;
            } */
            /* table.border tr td:first-child{
                border-left: 0.5px solid #000;
                border-top: 0.5px solid #000;
            }
            table.border tr td:last-child{
                border-right: 0.5px solid #000;
            } */

            ol {
                margin:0 0 1.5em;
                padding:0;
                counter-reset:item;
            }
            
            ol>li {
                margin:0;
                padding:0 0 0 2em;
                text-indent:-2em;
                list-style-type:none;
                counter-increment:item;
            }
            
            ol>li:before {
                display:inline-block;
                width:1.5em;
                padding-right:0.5em;
                font-weight:bold;
                text-align:right;
                content:counter(item) ".";
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">INTERNAL MEMO</h1>
            <table>
                <tr>
                    <td style="width:150px;">Kepada</td>
                    <td>: Dept. Finance Syariah</td>
                </tr>
                <tr>
                    <td>Dari</td>
                    <td>: Admin Syariah</td>
                </tr>
                <tr>  
                    <td>Tanggal</td>
                    <td>: {{date('d-M-Y',strtotime($data->tanggal_pengajuan))}}</td>
                </tr>

                <tr>
                    <td>No</td>
                    <td>: {{$data->nomor}}</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>: Permohonan Pembayaran Biaya Penutupan</td>
                </tr>
                <tr>
                    <td>Pemegang Polis</td>
                    <td>: {{$data->polis->nama}}</td>
                </tr>
            </table>
            <p>Assalamu ‘alaikum Wr. Wb<br />Dengan Hormat,	</p>
            <p>Sehubungan dengan Pembayaran Kontribusi yang telah diterima, mohon dapat dilakukan pembayaran Biaya Penutupan dengan data sebagai berikut :</p>
            
            <table class="table table-hover m-b-0 c_list table-nowrap border">
                <thead style="vertical-align:middle;background: #eeeeee7d;">
                    <tr>
                        <th>Ket.</th>
                        <th>Perkalian Biaya Penutupan</th>
                        <th>Penerima Pembayaran</th>
                        <th>Nama Bank</th>
                        <th>No Rekening</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>MAINTENANCE</td>
                        <td>{{$data->perkalian_biaya_penutupan}}</td>
                        <td>{{$data->maintenance_penerima}}</td>
                        <td>{{$data->maintenance_nama_bank}}</td>
                        <td>{{$data->maintenance_no_rekening}}</td>
                    </tr>
                    <tr>
                        <td>AGEN PENUTUP</td>
                        <td>{{$data->perkalian_biaya_penutupan}}</td>
                        <td>{{$data->admin_agency_penerima}}</td>
                        <td>{{$data->admin_agency_nama_bank}}</td>
                        <td>{{$data->admin_agency_no_rekening}}</td>
                    </tr>
                    <tr>
                        <td>ADMIN AGENCY</td>
                        <td>{{$data->perkalian_biaya_penutupan}}</td>
                        <td>{{$data->agen_penutup_penerima}}</td>
                        <td>{{$data->agen_penutup_nama_bank}}</td>
                        <td>{{$data->agen_penutup_no_rekening}}</td>
                    </tr>
                    <tr>
                        <td>UJROH(Handling Fee) BROKERS</td>
                        <td>{{$data->perkalian_biaya_penutupan}}</td>
                        <td>{{$data->ujroh_handling_fee_broker_penerima}}</td>
                        <td>{{$data->ujroh_handling_fee_broker_nama_bank}}</td>
                        <td>{{$data->ujroh_handling_fee_broker_no_rekening}}</td>
                    </tr>
                    <tr>
                        <td>REFERAL FEE</td>
                        <td>{{$data->perkalian_biaya_penutupan}}</td>
                        <td>{{$data->referal_fee_penerima}}</td>
                        <td>{{$data->referal_fee_nama_bank}}</td>
                        <td>{{$data->referal_fee_no_rekening}}</td>
                    </tr>
                </tbody>
            </table>
            <br />
            <table class="table table-hover m-b-0 c_list table-nowrap border">
                <thead style="vertical-align:middle">
                    <tr>
                        <th rowspan="2">No Polis</th>
                        <th rowspan="2">Pemegang Polis</th>
                        <th rowspan="2">No Debit Note</th>
                        <th rowspan="2">Kontribusi Gross</th>
                        <th rowspan="2">Kontribusi Nett</th>
                        <th rowspan="2">Tanggal Bayar</th>
                        <th>Maintenance</th>
                        <th>Agen Penutup</th>
                        <th>Admin Agency</th>
                        <th>Ujroh (Handling Fee) Broker</th>
                        <th>Referal Fee</th>
                    </tr>
                    <tr>
                        <th class="text-center">{{$data->maintenance}}%</th>
                        <th class="text-center">{{$data->agen_penutup}}%</th>
                        <th class="text-center">{{$data->admin_agency}}%</th>
                        <th class="text-center">{{$data->ujroh_handling_fee_broker}}%</th>
                        <th class="text-center">{{$data->referal_fee}}%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan as $k => $item)
                        <tr>
                            <td>{{$item->polis->no_polis}}</td>
                            <td>{{$item->polis->nama}}</td>
                            <td>{{$item->dn_number}}</td>
                            <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                            <td class="text-right">{{format_idr($item->kontribusi - $item->potong_langsung - $item->brokerage_ujrah)}}</td>
                            <td>{{$item->payment_date ? date('d-M-Y',strtotime($item->payment_date)) : '-'}}</td>
                            <td class="text-right">{{format_idr($item->maintenance)}}</td>
                            <td class="text-right">{{format_idr($item->agen_penutup)}}</td>
                            <td class="text-right">{{format_idr($item->admin_agency)}}</td>
                            <td class="text-right">{{format_idr($item->ujroh_handling_fee_broker)}}</td>
                            <td class="text-right">{{format_idr($item->referal_fee)}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-left">Total</td>
                        <td class="text-right">{{format_idr($data->total_kontribusi_gross)}}</td>
                        <td class="text-right">{{format_idr($data->total_kontribusi_nett)}}</td>
                        <th></th>
                        <td class="text-right">{{format_idr($data->total_maintenance)}}</td>
                        <td class="text-right">{{format_idr($data->total_agen_penutup)}}</td>
                        <td class="text-right">{{format_idr($data->total_admin_agency)}}</td>
                        <td class="text-right">{{format_idr($data->total_ujroh_handling_fee_broker)}}</td>
                        <td class="text-right">{{format_idr($data->total_referal_fee)}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-left">SUB TOTAL</td>
                        <th colspan="5" class="text-right">
                            {{format_idr(
                                $data->total_maintenance+
                                $data->total_agen_penutup+
                                $data->total_admin_agency+
                                $data->total_ujroh_handling_fee_broker+
                                $data->total_referal_fee
                            )}}
                        </th>
                    </tr>
                </tfoot>
            </table>
            <p>Data tersebut sudah sesuai dengan data di Underwriting Syariah</p>
            <p>
                Demikian disampaikan,atas perhatian dan kerjasamanya diucapkan terima kasih.<br />
                Wassalamu ‘alaikum Wr. Wb.
            </p>
            <table style="width: 100%;">
                <tr>
                    <td>Mengajukan,</td>
                    <td>Mengetahui,</td>
                    <td>Mengetahui,</td>
                    <td>Mengetahui,</td>
                    <td>Diterima Oleh,</td>
                </tr>
                <tr>
                    <td>
                        <img src="{{asset('assets/img/estikomah.png')}}" style="width: 80px;" />
                        <p style="padding-bottom:0;margin-bottom:0;">Estikomah</p>
                        <p style="border-top:1px solid;width:90%;margin-top:0;padding-top:0;">Admin</p>
                    </td>
                    <td>
                        <img src="{{asset('assets/img/sutarto.png')}}" style="width: 80px;" />
                        <p style="padding-bottom:0;margin-bottom:0;">Sutarto</p>
                        <p style="border-top:1px solid;width:90%;margin-top:0;padding-top:0;">Marketing</p>
                    </td>
                    <td>
                        <img src="{{asset('assets/img/ahmad_syafei.png')}}" style="width: 70px;" />
                        <p style="padding-bottom:0;margin-bottom:0;">Ahmad Syafei</p>
                        <p style="border-top:1px solid;width:90%;margin-top:0;padding-top:0;">Teknik Syariah</p>
                    </td>
                    <td style="vertical-align: text-bottom;">
                        <br />
                        <br />
                        <br />
                        <br />
                        <p style="padding-bottom:0;margin-bottom:0;">Budy Dharma Sadewa</p>
                        <p style="border-top:1px solid;width:90%;margin-top:0;padding-top:0;">General Manager</p>
                    </td>
                    <td>
                        <br />
                        <br />
                        <br />
                        <p>&nbsp;</p>                        
                        <p style="border-top:1px solid;width:90%">Dept. Finance</p>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>