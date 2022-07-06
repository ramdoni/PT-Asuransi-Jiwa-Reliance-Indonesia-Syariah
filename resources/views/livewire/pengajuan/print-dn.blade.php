<html>
    <head>
        <title>Debit Note Number : {{$data->dn_number}}</title>
        <style>
            @page { margin: 0px; }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:12px;
            }
            p {
                font-size:14px;
            }
            h1 {font-size: 30px;}
            .container {padding-left:70px;padding-right:70px;}
            table.style1 tr td {padding-top:5px;}
            table.border, table.border tr td,table.border tr th {
                border:1px solid #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <br />
            <hr style="border-bottom:1px solid #000;border-top:3px solid #000;height:2px;border-right:0;border-left:0;" />
            <h1 style="text-align:center">Debit Note</h1>
            <hr style="border-bottom:3px solid #000;border-top:1px solid #000;height:2px;border-right:0;border-left:0;" />
            <table width="100%">
                <tr>
                    <td style="width: 50%">
                        No : {{$data->dn_number}}
                    </td>
                    <td style="width: 50%">
                        Jakarta, {{date('d F ')}}
                    </td>
                </tr>
            </table>
            <p><strong>Kepada Yth: <br />
                {{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <table style="width:100%" class="border">
                <tr>
                    <th>KETERANGAN</th>
                    <th>JUMLAH (Rp)</th>
                </tr>
                <tr>
                    <td colspan="2">
                        Tagihan Penutupan Asuransi produk <strong>RELIANCE PEMBIAYAAN SYARIAH</strong> dengan No Polis {{$data->polis->no_polis}} dan Jumlah Peserta {{$data->kepesertaan->count()}} orang (No Peserta {{$data->no_peserta_awal}} {{isset($data->no_peserta_akhir) ? " - {$data->no_peserta_akhir}" : '' }}  ).                     
                    </td>
                </tr>
                <tr>
                    <td>Kontribusi</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Extra Kontribusi</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Potong Langsung 10%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>PPN 0%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>PPh 0%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Biaya Polis dan Materai</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Biaya Sertifikat/Kartu @ Rp0</td>
                    <td></td>
                </tr>
                
                
            </table>
            <p>Pembayaran Kontribusi dapat dilakukan melalui transfer ke rekening sebagai berikut:</p>
            <table>
                <tr>
                    <td><strong>Bank</strong></td>
                    <td><strong>: Bank Syariah Indonesia</strong></td>
                </tr>
                <tr>
                    <td><strong>Atas Nama</strong></td>
                    <td><strong>: PT Asuransi Jiwa Reliance Indonesia</strong></td>
                </tr>
                <tr>
                    <td><strong>Nomor Rekening</strong></td>
                    <td><strong>: 8382828230</strong></td>
                </tr>
            </table>
            <p>
                Hormat Kami,<br />
                <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                <br />
                <br />
                <br />
                <b><u>{{isset(\Auth::user()->head_teknik->name) ? \Auth::user()->head_teknik->name : '-'}}</u></b>
                Head of Teknik Syariah
                <small>Catatan: Harap pembayaran kontribusi mencantumkan nomor Debit Note.</small>
            </p>
        </div>
    </body>
</html>