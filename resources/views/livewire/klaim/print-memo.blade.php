<html>
    <head>
        <title>Klaim Number : {{$data->no_memo}}</title>
        <style>
            @page { 
                margin-left: 2.54cm;
                margin-right: 2.54cm;
                size: 210mm 297mm; 
            }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:10pt;
            }
            h1 {font-size: 14pt;}
            table {border-collapse: collapse;}
            table.style1 tr td {padding-top:5px;}
            table.border{border:2px solid #000;}
            table.border tr td,table.border tr th {
                border:1px solid #000;
                border-bottom:2px solid #000;
                border-right:2px solid #000;
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
        </style>
    </head>
    <body>
        <br />
        <br />
        <br />
        <br />
        <h1 style="font-size:14pt;text-align:center;"><u>MEMO PEMBAYARAN KLAIM</u></h1>
        <br />
        <table style="width:100%;border-top:1px solid;border-bottom:1px solid;">
            <tr>
                <td style="padding-top:10px;padding-bottom:10px">Nomor</td>
                <td> : </td>
                <td>{{$data->no_memo}}</td>
                <td>Tanggal, {{date('d F Y',strtotime($data->created_at))}}</td>
            </tr>
            <tr>
                <td style="padding-top:10px;padding-bottom:10px">Kepada</td>
                <td> : </td>
                <td colspan="2">Dept Finance</td>
            </tr>
            <tr>
                <td style="padding-top:10px;padding-bottom:10px">Dari</td>
                <td> : </td>
                <td colspan="2">Dept Klaim Syariah</td>
            </tr>
            <tr>
                <td style="padding-top:10px;padding-bottom:10px">Perihal</td>
                <td> : </td>
                <td colspan="2"><strong>Pembayaran Manfaat Asuransi (Klaim)</strong></td>
            </tr>
        </table>
        <br />
        <p>
            <i>Assalamu’alaikum Wr. Wb.</i>
        </p>
        <p style="text-align: justify">
            Berikut kami sampaikan pembayaran manfaat asuransi (santunan) dengan rincian sebagai berikut:
        </p>
        <table style="width:100%;">
            <tr>
                <td>Nomor polis</td>
                <td style="width:20px;"> : </td>
                <td>{{isset($data->polis->no_polis) ? $data->polis->no_polis : '-'}}</td>
            </tr>
            <tr>
                <td>Pemegang Polis</td>
                <td> : </td>
                <td>{{isset($data->polis->nama) ? $data->polis->nama : '-'}}</td>
            </tr>
            <tr>
                <td>Produk Asuransi</td>
                <td> : </td>
                <td>{{isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'}}</td>
            </tr>
            <tr>
                <td>Nama Peserta</td>
                <td> : </td>
                <td>{{isset($data->kepesertaan->nama) ? $data->kepesertaan->nama : '-'}}</td>
            </tr>
            <tr>
                <td>Nomor Peserta</td>
                <td> : </td>
                <td>{{isset($data->kepesertaan->no_peserta) ? $data->kepesertaan->no_peserta : '-'}}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td> : </td>
                <td>{{isset($data->kepesertaan->tanggal_lahir) ? date('d F Y',strtotime($data->kepesertaan->tanggal_lahir)) : '-'}}</td>
            </tr>
            <tr>
                <td>Periode Asuransi</td>
                <td> : </td>
                <td>{{date('d M Y',strtotime($data->kepesertaan->tanggal_mulai))}} sd {{date('d M Y',strtotime($data->kepesertaan->tanggal_akhir))}} ({{$data->kepesertaan->masa_bulan}} Bulan) </td>
            </tr>
            <tr>
                <td>Klaim Disetujui</td>
                <td> : </td>
                <td>{{format_idr($data->nilai_klaim_disetujui)}}</td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td> : </td>
                <td>{{terbilang($data->nilai_klaim_disetujui)}}</td>
            </tr>
            <tr>
                <td>Ditransfer ke </td>
                <td> : </td>
                <td>
                    <table>
                        <tr>
                            <td>Nomor Rekening</td>
                            <td> : </td>
                            <td>505 5050009</td>
                        </tr>
                        <tr>
                            <td>Bank-Cabang</td>
                            <td> : </td>
                            <td>Bank Syariah Mandiri Cab Saharjo – Jakarta Selatan</td>
                        </tr>
                        <tr>
                            <td>Atas Nama</td>
                            <td> : </td>
                            <td>PT. Asuransi Jasindo Syariah</td>
                        </tr>
                        <tr>
                            <td>Mata Uang</td>
                            <td> : </td>
                            <td>Rupiah</td>
                        </tr>
                        <tr>
                            <td>Jatuh Tempo</td>
                            <td> : </td>
                            <td>{{$data->jatuh_tempo ? date('d M Y',strtotime($data->jatuh_tempo)) : '-'}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terimakasih.</p>
        <p>Wassalamu’alaikum Wr. Wb</p>
        <table style="width:100%;">
            <tr>
                <td></td>
                <td style="text-align:center;">Diterima oleh,<br /><br /><br /><br /><br />
                    <br /><br /><br />
                </td>
            </tr>
            <tr>
                <td style="text-align:left;">
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head of Teknik Syariah
                </td>
                <td style="text-align:center;">
                    <strong>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</strong><br />
                    Tanggal & Nama Jelas
                </td>
            </tr>
        </table>
    </body>
</html>