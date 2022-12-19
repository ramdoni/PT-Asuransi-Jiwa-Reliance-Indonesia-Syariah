<html>
    <head>
        <title>Klaim Number : {{$data->no_pengajuan}}</title>
        <style>
            @page { 
                margin-left: 2.54cm;
                margin-right: 2.54cm;
                size: 210mm 297mm; 
            }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:11pt;
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
       <div style="width:50%;float:left">
            <p>Jakarta, {{date('d F Y',strtotime($data->created_at))}}</p>
        </div>
        <div style="width:50%;float:left">
            <table style="width:100%;">
                <tr>
                    <td>Nomor </td>
                    <td> : </td>
                    <td> {{$data->no_surat_diterima}}</td>
                </tr>
                <tr>
                    <td>Perihal </td>
                    <td> : </td>
                    <td> <strong>Pemberitahuan Keputusan Klaim</strong></td>
                </tr>
            </table>
        </div>
        <div style="clear:both"></div>
        <div>
            Kepada.<br />
            <strong>Pemegang Polis<br />
                {{$data->polis->nama}}
            </strong>
            <br />Di<br />Tempat
        </div>
        <h6>UP. Bagian Klaim</h6>
        <p><i>Assalamu’alaikum Wr. Wb.</i></p>
        <p>Terima kasih atas penyampaian Data Peserta untuk Pengajuan Klaim Asuransi Jiwa, sebagai berikut: </p>
        <table style="width:90%;margin:auto;">
            <tr>
                <td>Nomor polis</td>
                <td> : </td>
                <td>{{$data->polis->no_polis ? $data->polis->no_polis : '-'}}</td>
            </tr>
            <tr>
                <td>Pemegang Polis</td>
                <td> : </td>
                <td>{{$data->polis->nama ? $data->polis->nama : '-'}}</td>
            </tr>
            <tr>
                <td>Produk Asuransi</td>
                <td> : </td>
                <td>{{$data->polis->produk->nama ? $data->polis->produk->nama : '-'}}</td>
            </tr>
            <tr>
                <td>Nama Peserta</td>
                <td> : </td>
                <td><strong>{{$data->kepesertaan->nama ? $data->kepesertaan->nama : '-'}}</strong></td>
            </tr>
            <tr>
                <td>Nomor Peserta</td>
                <td> : </td>
                <td>{{$data->kepesertaan->no_peserta ? $data->kepesertaan->no_peserta : '-'}}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td> : </td>
                <td>{{$data->kepesertaan->tanggal_lahir ? date('d M Y',strtotime($data->kepesertaan->tanggal_lahir)) : '-'}}</td>
            </tr>
            <tr>
                <td>Periode Asuransi</td>
                <td> : </td>
                <td>{{$data->kepesertaan->tanggal_mulai ? date('d M Y',strtotime($data->kepesertaan->tanggal_mulai)) .' sd '. date('d M Y',strtotime($data->kepesertaan->tanggal_akhir)) : '-'}}</td>
            </tr>
            <tr>
                <td>Uang Asuransi</td>
                <td> : </td>
                <td>Rp. {{$data->nilai_klaim ? format_idr($data->nilai_klaim) : '-'}}</td>
            </tr>
            <tr>
                <td>Uang Asuransi</td>
                <td> : </td>
                <td>Rp. {{$data->tanggal_meninggal ? date('d M Y',strtotime($data->tanggal_meninggal)) : '-'}}</td>
            </tr>
            <tr>
                <td>Usia Polis</td>
                <td> : </td>
                <td>{{$data->tanggal_meninggal ? hitung_umur($data->kepesertaan->tanggal_mulai,3,$data->tanggal_meninggal) : '-'}}</td>
            </tr>
        </table>
        <p style="text-align: justify">
            Dengan ini disampaikan bahwa setelah melakukan verifikasi dan analisa berdasarkan dokumen klaim yang didapatkan, pengajuan klaim tersebut dapat <b>diterima dan disetujui</b> sebesar Rp {{format_idr($data->nilai_klaim_disetujui)}} ({{terbilang($data->nilai_klaim_disetujui)}}).
        </p>
        <p style="text-align: justify">
            Mohon konfirmasinya untuk nilai klaim yang di setujui tersebut selambat-lambatnya 3 (tiga) hari kerja sejak tanggal pemberitahuan ini disampaikan guna proses pembayaran lebih lanjut. Apabila tidak ada konfirmasi, maka kami menganggap sudah menyetujuinya dan bila ada keberatan dikemudian hari mohon maaf tidak dapat diakomodir.
        </p>
        <p>
            Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terimakasih.
        </p>
        <p>
            <i>Wassalamu’alaikum Wr. Wb.</i><br />
            <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
        </p>
        <br />
        <br />
        <br />
        <br />
        <br />
        <strong><i>Ahmad Syafei</i></strong><br />
        Head of Teknik Syariah
    </body>
</html>