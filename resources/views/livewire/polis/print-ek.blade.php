<html>
    <head>
        <title>Surat Pernyataan Extra Kontribusi</title>
        <style>
            @page { margin: 0px; }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:12px;
            }
            .container {padding-left:70px;padding-right:70px;}
            table.style1 tr td {padding-top:5px;}
        </style>
    </head>
    <body>
        <img src="logo.jpg" style="width:150px; margin-top:20px;margin-left:40px;" />
        <div class="container">
            <table width="100%">
                <tr>
                    <td style="width: 50%">
                        Jakarta, {{date('d F Y')}}
                    </td>
                    <td style="width: 50%">
                        <table>
                            <tr>
                                <td>Nomor </td>
                                <td>: {{$data->nomor_ek}}</td>
                            </tr>
                            <tr>
                                <td>Perihal </td>
                                <td>: Pemberitahuan Extra Kontribusi</td>
                            </tr>
                            <tr>
                                <td>Lamp </td>
                                <td>: -</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br />
            <br />
            <p>Kepada Yth.<br />
                <b>{{$data->polis->nama}}</b>
            </p>
            <p>Dengan hormat,</p>
            <p>Terima kasih atas penyampaian Data Kepesertaan untuk Penutupan Asuransi Jiwa, sebagai berikut :</p>
            <table style="width:90%;margin:auto;" class="style1">
                <tr>
                    <td style="width:40%">Nomor polis</td>
                    <td style="width:2%"> : </td>
                    <td>{{$data->polis->no_polis}} </td>
                </tr>
                <tr>
                    <td>Pemegang Polis</td>
                    <td> : </td>
                    <td>{{$data->polis->nama}} </td>
                </tr>
                <tr>
                    <td>Jenis Asuransi</td>
                    <td> : </td>
                    <td>{{$data->polis->produk->nama}} </td>
                </tr>
                <tr>
                    <td>Nama calon peserta</td>
                    <td> : </td>
                    <th style="text-align:left;">{{$data->nama}} </th>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td> : </td>
                    <th style="text-align:left;">{{date('d F Y',strtotime($data->tanggal_lahir))}}</th>
                </tr>
                <tr>
                    <td>Nilai Manfaat Asuransi</td>
                    <td> : </td>
                    <th style="text-align:left;">{{format_idr($data->basic)}} </th>
                </tr>
                <tr>
                    <td>Masa Asuransi</td>
                    <td> : </td>
                    <th style="text-align:left;">{{$data->masa_bulan}} </th>
                </tr>
                <tr>
                    <td>Mulai Asuransi</td>
                    <td> : </td>
                    <th style="text-align:left;">{{date('d F Y',strtotime($data->tanggal_mulai))}}</th>
                </tr>
            </table>
            <p style="text-align: justify;">dengan ini disampaikan bahwa berdasarkan data calon Peserta tersebut diatas setelah diseleksi berdasarkan dokumen Surat Pernyataan Kesehatan (SPK), calon Peserta dapat diterima dengan akseptasi Non Medical Substandard dengan Extra Kontribusi dikarenakan {{$data->status_ek}}</p>
            <p style="text-align: justify;">Bila Calon peserta bersedia dikenakan Extra Kontribusi tersebut, mohon Surat Persetujuan Extra Kontribusi (terlampir), ditandatangani oleh yang bersangkutan dan disampaikan kembali kepada PT. Asuransi Jiwa Reliance Indonesia Unit Syariah.</p>
            <p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terimakasih.</p>
            <br />
            <br />
            <p>Hormat kami,<br />
                PT. ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH
            </p>
            <br />
            <br />
            <br />
            <br />
            <br />
            <p>
                <b><u>{{ \Auth::user()->name }}</u></b><br />
                Head of Teknik Syariah
            </p>
        </div>
        <div style="page-break-after: always;"></div>
        <img src="logo.jpg" style="width:150px;margin-left:40px;margin-top:20px;" />
        <div class="container" style="padding-top:0;margin-top:0;" >
            <br />
            <table style="width: 100%">
                <tr>
                    <td style="width:50%">Nomor</td>
                    <td style="width:50%">Jakarta, {{date('d F Y')}}</td>
                </tr>
            </table>
            <p>Kepada Yth,<br />
                <strong>Bapak / Ibu</strong><br />
                Di tempat
            </p>
            <p>Hal. : SURAT PERNYATAAN EXTRA KONTRIBUSI a/n <b>{{$data->nama}}</b></p>
            <p>Dengan hormat,</p>
            <p style="text-align: justify;">Terima kasih atas kepercayaan Bapak/Ibu untuk membeli produk Asuransi Jiwa yang kami tawarkan, namun sebelum proses dilanjutkan, perlu diberitahukan kepada Bapak/Ibu bahwa kami dapat melakukan akseptasi atas penutupan Asuransi Jiwa atas nama Bapak/Ibu dengan status substandard.</p>
            <p>Status substandard ini disebabkan oleh karena :<br />
                <ul>
                    <li>{{$data->status_ek}}</li>
                </ul>
            </p>
            <p>Oleh karena status substandard tersebut, maka Bapak/Ibu dikenakan Extra Kontribusi dari Kontribusi Standar, Perhitungan lengkapnya adalah sebagai berikut :</p>
            <hr />
            <table>
                <tr>
                    <td>Kontribusi Standar</td>
                    <td> = </td>
                    <td>Rp . </td>
                    <td style="text-align: right;">{{format_idr($data->kontribusi)}}</td>
                </tr>
                <tr>
                    <td>Extra Kontribusi</td>
                    <td> = </td>
                    <td>Rp . </td>
                    <td style="text-align: right;">{{format_idr($data->extra_kontribusi)}}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2"><hr/></td>
                </tr>
                <tr>
                    <td>Total Kontribusi</td>
                    <td> = </td>
                    <th>Rp . </th>
                    <th style="text-align: right;">{{format_idr($data->kontribusi + $data->extra_kontribusi)}}</th>
                </tr>
                <tr>
                    <td colspan="4"><strong>sekaligus</strong>/tahun/semester/triwulan/bulanan</td>
                </tr>
            </table>
            <hr />
            <p style="text-align: justify;">Sebelum kami lanjutkan proses berikutnya, mohon agar Bapak/Ibu memberikan konfirmasi pesetujuan atas total pembayaran Kontribusi tersebut hanya sekali untuk Kontribusi sekaligus atau setiap tahun/semester/triwulan/bulanan.</p>
            <p style="text-align: justify;">Kami menunggu konfirmasi Bapak/Ibu dalam waktu 6 (enam) hari kerja sejak tanggal surat ini diterima.</p>
            <p>Demikian kami sampaikan, agar Bapak/Ibu maklum <br/>Hormat kami,</p>
            <br />
            <br />
            <p>Head of Teknik Syariah</p>
            <hr style="padding-bottom:0;margin-bottom:0;" />
            <p style="text-align: center;margin-top:0;padding-top:0;"><strong>PERNYATAAN CALON PEMEGANG POLIS/KEPESERTAAN</strong></strong></p>
            <table>
                <tr>
                    <td colspan="2">Saya yang bertandatangan di bawah ini :</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <th style="text-align:left;"> : 
                        @if($data->jenis_kelamin=='Laki-laki')
                            Tn 
                        @endif
                        @if($data->jenis_kelamin=='Perempuan')
                            Ny 
                        @endif
                        {{$data->nama}}</th>
                </tr>
                <tr>
                    <td>Nomor Polis</td>
                    <td> : {{$data->polis->no_polis}}</td>
                </tr>
                <tr>
                    <td>Pemegang Polis</td>
                    <td> : {{$data->polis->nama}}</td>
                </tr>
            </table>
            <p style="text-align: justify;">
                Menyatakan bahwa saya menyetujui atas pembebanan Extra Kontribusi atas penutupan Asuransi Jiwa Produk {{$data->polis->produk->nama}} sesuai dengan SURAT PERNYATAAN EXTRA KONTRIBSI No. : {{$data->nomor_ek}}
            </p>
            <table style="width:90%;margin:auto;">
                <tr>
                    <td style="width:60%;"></td>
                    <td style="width:40%;text-align:center;">
                        <hr style="border-top: 1px dotted black;" />
                        <br />
                        <br />
                        <br />
                        <br />
                        <hr>
                        <p>(Calon Peserta)</p>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>