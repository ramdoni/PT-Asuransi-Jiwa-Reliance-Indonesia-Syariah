<html>
    <head>
        <title>Nomor Pengajuan : {{$data->no_pengajuan}}</title>
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
        </style>
    </head>
    <body>
        <div class="container">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}?v=1" style="width: 100%;" />
            <hr style="margin-bottom: 5px;" />
            <h1 class="text-center">DEBIT NOTE</h1>
            <hr />
            <table style="width: 100%;">
                <tr>
                    <td width="70%">No : {{$data->nomor_dn}}</td>
                    <td width="30%;">Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                </tr>
            </table>
            <p>
                Kepada Yth:<br />
                <strong>{{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <table style="border:1px solid">
                <tr>
                    <th style="border-bottom: 1px solid;border-right:1px solid;padding-top:10px;padding-bottom:10px;">KETERANGAN</th>
                    <th style="border-bottom: 1px solid;">  NILAI MANFAAT REASURANSI (Rp)</th>
                </tr>
                <tr>
                    <td style="border-right:1px solid;padding-left:10px;padding-top:50px;padding-bottom:40px;">
                        <p>
                            Klaim Reasuransi Kepesertaan Asuransi Unit Syariah produk <b>{{isset($data->polis->produk->nama) ? $data->polis->produk->nama : '' }}</b>
                            dengan No Polis <strong>{{$data->polis->no_polis}}</strong> dan Jumlah Peserta 1 orang (No Peserta 
                                {{isset($data->kepesertaan->no_peserta) ? $data->kepesertaan->no_peserta : '-'}}
                        </p>
                    </td>
                    <td style="width: 180px;padding-top:40px;padding-bottom:40px;" class="text-right">
                        <strong>{{format_idr($data->nilai_klaim)}}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Terbilang : {{terbilang($data->nilai_klaim)}}</td>
                </tr>
                <tr>
                    <td colspan="2">Masa Tenggang Pembayaran sampain dengan : {{date('d F Y',strtotime($data->tgl_jatuh_tempo))}}</td>
                </tr>
            </table>
            <br />
            <br />
            <br />
            <div style="position: relative;">
                <p style="z-index:1">Hormat Kami,<br />
                    <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                </p>
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                <p>
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head Of Divisi Teknik Syariah
                </p>
            </div>
            <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
        <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
    </body>
</html>