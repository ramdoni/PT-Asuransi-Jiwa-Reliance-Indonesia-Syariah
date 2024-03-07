<html>
    <head>
        <title>Nomor Pengajuan : {{$data->nomor}}</title>
        <style>
            /* @page { margin: 0px; } */
            /* body { margin: 0px; } */
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:12px;
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
            table.no-padding tr td {
                padding-top:0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">MEMO INTERNAL</h1>
            <table>
                <tr>
                    <td>Nomor</td>
                    <td> : </td>
                    <td>{{$data->nomor}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td> : </td>
                    <td>{{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
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
                    <td>Kontribusi yang
                        @if($data->is_cn==1) 
                            dibayar/<s>diterima</s>
                        @else
                            <s>dibayar</s>/diterima
                        @endif
                    </td>
                    <td>Rp</td>
                    <th class="text-right">
                        {{format_idr(abs($data->total_kontribusi_dibayar))}}
                    </th>
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
            <p>{{$data->bank_name}}<br />
                A/C IDR: {{$data->bank_no_rekening}}<br />
                A/N {{$data->bank_owner}}
            </p>
        </div>

        <div class="page-break"></div>
        
        <div  style="margin-top:-20px;">
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <h2 class="text-center" style="padding-top:0;margin-top:0;padding-bottom:0;margin-bottom:0;">DEBIT NOTE</h2>
            <h2 class="text-center" style="padding-top:0;margin-top:0;padding-bottom:0;margin-bottom:0;">No : {{$data->nomor_cn_dn}}</h2>
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <table style="width:50%;float:left" class="no-padding">
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
            <table style="width:50%;float:left" class="no-padding">
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
            <table style="width: 90%;margin:auto;" class="style2">
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
                    <td><strong>Kontribusi 
                        @if($data->is_cn==1) 
                            Dibayar/<s>diterima</s>
                        @else
                            <s>Dibayar</s>/diterima
                        @endif</strong></td>
                    <td class="text-center"><strong>IDR</strong></td>
                    <td class="text-right"><strong>{{format_idr(abs($data->total_kontribusi_dibayar))}}</strong></td>
                </tr>
            </table>
            <p>Terbilang :#{{terbilang(abs($data->total_kontribusi_dibayar))}}</p>
            <div style="float:right; widht: 300px;position:relative;text-align:center;">
                Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}
                <br />
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ahmad_syafei.png')}}" style="width: 110px;z-index:2;position:absolute;top:20px;" />
                <br>
                <br>
                <br>
                <u>Ahmad Syafei</u><br />
                Head of Teknik Syariah
                <br>
            </div>
            <div style="clear:both;">&nbsp;</div>
        </div>
        <hr />
        <br />
        <div>
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <h2 class="text-center" style="padding-top:0;margin-top:0;padding-bottom:0;margin-bottom:0;">DEBIT NOTE</h2>
            <h2 class="text-center" style="padding-top:0;margin-top:0;padding-bottom:0;margin-bottom:0;">No : {{$data->nomor_cn_dn}}</h2>
            <hr style="margin-bottom:1px;" />
            <hr style="margin-top:0" />
            <table style="width:50%;float:left" class="no-padding">
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
            <table style="width:50%;float:left" class="no-padding">
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
            <table style="width: 90%;margin:auto;" class="style2">
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
                    <td><strong>Kontribusi 
                        @if($data->is_cn==1) 
                            Dibayar/<s>diterima</s>
                        @else
                            <s>Dibayar</s>/diterima
                        @endif</strong></td>
                    <td class="text-center"><strong>IDR</strong></td>
                    <td class="text-right"><strong>{{format_idr(abs($data->total_kontribusi_dibayar))}}</strong></td>
                </tr>
            </table>
            <p>Terbilang :#{{terbilang(abs($data->total_kontribusi_dibayar))}}</p>
            <div style="float:right; widht: 300px;position:relative;text-align:center;">
                Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}
                <br />
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ahmad_syafei.png')}}" style="width: 110px;z-index:2;position:absolute;top:20px;" />
                <br>
                <br>
                <br>
                <u>Ahmad Syafei</u><br />
                Head of Teknik Syariah
                <br>
            </div>
        </div>
    </body>
</html>