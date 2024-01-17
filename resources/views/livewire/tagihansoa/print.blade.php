<html>
    <head>
        <title>Nomor Pengajuan : {{$data->nomor}}</title>
        <style>
            /* @page { margin: 0px; } */
            /* body { margin: 0px; } */
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:13px;
            }
            h1 {font-size: 20px;}
            .container {
                padding-left:20px;
                padding-right:20px;
                position:relative;
            }
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
            .page-break {
                page-break-after: always;
            }
            .container-peserta {
                font-size: 10px !important;
                padding-left: 0;
                padding-right: 0;
                margin-left:0;
                margin-right:0;
            }
            .table-peserta {
                font-size: 10px;
            }
            .table-peserta tr th {
                border-top: 2px solid;
                border-bottom: 1px solid;
                font-size: 8px;
            }
            .table-peserta tr td {
                border-bottom: 1px solid;
                font-size: 8px;
            }
            table tr td {
                padding: 5px 2px;
            }
            table.style2 {
                border-spacing: 3px;
                border: 1px solid;
            }
            table.style2 tr td,table.style2 tr th {
                border: 1px solid;
                padding:4px 4px'
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">MEMO INTERNAL</h1>
            <table>
                <tr>
                    <td>Nomor, Tanggal</td>
                    <td> : </td>
                    <td>{{$data->no_pengajuan}}, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                </tr>
                <tr>
                    <td>Kepada</td>
                    <td> : </td>
                    <td>Yth. Dept Accounting dan Finance</td>
                </tr>
                <tr>
                    <td>Dari</td>
                    <td> : </td>
                    <td>Dept. Reasuransi Syariah</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td> : </td>
                    <td>Kontribusi Reas ke PT. Reasuransi Nasional Indonesia Syariah</td>
                </tr>
            </table>
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <p>Dengan Hormat,</p>
            <p style="text-align:justify;">
                Sehubungan dengan pengiriman bordero Peserta Reasuransi ke {{isset($data->reasuradur->name) ? $data->reasuradur->name : ''}} dan menunjuk {{$data->nomor_syr}}
                perihal dimaksud, bersama ini kami sampaikan rincian Kontribusi Reasuransi yang harus dilakukan pembayaran sebelum tanggal {{date('d F Y',strtotime($data->tgl_jatuh_tempo))}}
            </p>
            <p style="text-align:justify;">
                Jumlah Kontribusi yang menjadi kewajiban PT. ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH sebesar (rekap terlampir) : 
            </p>
            <table style="width: 70%;margin:auto;">
                <tr>
                    <th colspan="3" class="text-left">Pembayaran Kontribusi Reas <br /><br /></th>
                </tr>
                <tr>
                    <td>Manfaat Asuransi Total</td>
                    <td>Rp</td>
                    <td class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</td>
                </tr>
                <tr>
                    <td>Manfaat Asuransi Reas</td>
                    <td>Rp</td>
                    <td class="text-right">{{format_idr($data->total_manfaat_asuransi_reas)}}</td>
                </tr>
                <tr>
                    <td>Kontribusi Gross</td>
                    <td>Rp</td>
                    <th class="text-right">{{format_idr($data->kontribusi_gross)}}</th>
                </tr>
                <tr>
                    <td>Ujroh</td>
                    <td>Rp</td>
                    <td class="text-right">{{format_idr($data->ujroh)}}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td>Kontribusi Netto</td>
                    <td>Rp</td>
                    <td class="text-right">{{format_idr($data->kontribusi_netto)}}</td>
                </tr>
                <tr>
                    <td>Refund & Endorsement</td>
                    <td>Rp</td>
                    <td class="text-right">-{{format_idr($data->refund + $data->endorsement)}}</td>
                </tr>
                <tr>
                    <td>Klaim</td>
                    <td>Rp</td>
                    <td class="text-right">-{{format_idr($data->klaim)}}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td>Kontribusi yang dibayar/diterima</td>
                    <td>Rp</td>
                    <th class="text-right">
                        {{$data->is_cn==1?'-':''}}    
                    {{format_idr(abs($data->total_kontribusi_dibayar))}}</th>
                </tr>
            </table>
            <p>Demikian disampaikan, atas perhatian dan kerjasamanya kami ucapkan terimakasih.</p>

            <table style="width: 100%;">
                <tr>
                    <td style="position:relative;width:70%;">
                        Hormat kami
                        <br />
                        <br />
                        <br />
                        <br />
                        <img src="{{public_path('assets/img/ahmad_syafei.png')}}" style="width: 120px;z-index:2;position:absolute;top:20px;" />
                        
                        <br>
                        <br>
                        <br>
                        <br>
                        <u>Ahmad Syafei</u><br />
                        Head of Teknik Syariah
                        <br>
                        <span style="z-index: 3">Dept. Underwriting Syariah</span>
                    </td>
                    <td style="width:30%;">
                        Diterima oleh,<br />
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        (.....................................) <br>
                        Div. Finance
                    </td>
                </tr>
            </table>
            <p>Bank Syariah Indonesia - Cabang Thamrin<br />
                A/C IDR: 7001391628<br />
                A/N Nasional Re qq Cabang Syariah
            </p>
        </div>
        <div class="page-break"></div>
        <div>
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <h1 class="text-center">DEBIT NOTE</h1>
            <h1 class="text-center">No : {{$data->nomor_cn_dn}}</h1>
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <table style="width:50%;float:left">
                <tr>
                    <td>Date</td>
                    <td> : </td>
                    <td>{{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                </tr>
                <tr>
                    <td>I/M No</td>
                    <td> : </td>
                    <td>{{$data->nomor}}</td>
                </tr>
                <tr>
                    <td>To</td>
                    <td> : </td>
                    <td>Div. Finance & Accounting</td>
                </tr>
            </table>
            <table style="width:50%;float:left">
                <tr>
                    <td>Reinsurance</td>
                    <td> : </td>
                    <td>{{isset($data->reasuradur->name) ? $data->reasuradur->name : '-'}}</td>
                </tr>
                <tr>
                    <td>Period</td>
                    <td> : </td>
                    <td>{{$data->period}}</td>
                </tr>
                <tr>
                    <td>Due Date</td>
                    <td> : </td>
                    <td>{{date('d-M-Y',strtotime($data->tgl_jatuh_tempo))}}</td>
                </tr>
            </table>
            <div style="clear:both"></div>
            <br />
            <table style="width: 80%;margin:auto;" class="style2">
                <tr>
                    <th>Description</th>
                    <th class="text-center">Curr</th>
                    <th class="text-center">Balance</td>
                </tr>
                <tr>
                    <td>Kontribusi Gross</td>
                    <td class="text-center">IDR</td>
                    <td class="text-right">{{format_idr($data->kontribusi_gross)}}</td>
                </tr>
                <tr>
                    <td>Ujroh</td>
                    <td class="text-center">IDR</td>
                    <td class="text-right">{{format_idr($data->ujroh)}}</td>
                </tr>
                <tr>
                    <td><strong>Kontribusi Nett</strong></td>
                    <td class="text-center"><strong>IDR</strong></td>
                    <td class="text-right">{{format_idr($data->kontribusi_netto)}}</td>
                </tr>
                <tr>
                    <td>Refund</td>
                    <td class="text-center">IDR</td>
                    <td class="text-right">-{{format_idr($data->refund)}}</td>
                </tr>
                <tr>
                    <td>Claim</td>
                    <td class="text-center">IDR</td>
                    <td class="text-right">-{{format_idr($data->klaim)}}</td>
                </tr>
                <tr>
                    <td><strong>Kontribusi Dibayar/diterima</strong></td>
                    <td class="text-center"><strong>IDR</strong></td>
                    <td class="text-right"><strong>{{format_idr(abs($data->total_kontribusi_dibayar))}}</strong></td>
                </tr>
            </table>
            <p>Terbilan :#{{terbilang(abs($data->total_kontribusi_dibayar))}}</p>
            <div style="float:right; widht: 300px;position:relative;text-align:center;">
                Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}
                <br />
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ahmad_syafei.png')}}" style="width: 120px;z-index:2;position:absolute;top:20px;" />
                <br>
                <br>
                <br>
                <br>
                <u>Ahmad Syafei</u><br />
                Head of Teknik Syariah
                <br>
                <span style="z-index: 3">Dept. Underwriting Syariah</span>
            </div>
        </div>
    </body>
</html>