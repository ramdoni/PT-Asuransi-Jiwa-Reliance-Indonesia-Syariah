<html>
    <head>
        <title>Sertifikat {{$data->no_peserta}}</title>
        <style>
            @page { 
                margin: 0px;
                size: 600px 21cm landscape;
            }
            body { margin: 0px;  }
            * {
                font-family:"Calibri", sans-serif;
                font-size: 12px;
            }
            .container {
                background-size: content;
                width: 21cm;
                height: 600px;
                position: absolute;
                top: 0;
                width: 80%;
                margin: auto;
                left: 0;
                right: 0;
            }
            table.style1 tr td {
                padding-top: 0px;
                padding-bottom: 0px;
            }
            .bg_sertifikat {
                background: url('assets/img/bg-sertifikat.jpeg');
                background-size: 100% 100%;
                width: 100%;
                height: 560px;
            }
            .table-no-padding tr td,
            .table-no-padding tr th{
                padding-top: 0;
                padding-bottom: 0;
            }
            .page_break { page-break-before: always; }
            .text-center { text-align: center; }
            .text-left { text-align: left; }
            .text-right { text-align: left; }
            .text-justify { text-align: justify; }
            table.style2 tr th {
                padding-top: 5px;
            }
            table.style2 tr td {
                padding-top: 2px;
            }
            .pl-20 {
                padding-left: 20px;
            }
        </style>
    </head>
    <body>
        <img src="assets/img/bg-sertifikat.jpeg" style="width: 21cm;height: 600px;position: absolute; top:0;right:0;z-index:1" />
        <div class="container" style="z-index:2;padding-top: 70px;height: 600px;">
            <p  style="text-align: center;margin-bottom:0;">Bismillahirrahmanirrahim</p>
            <h5 style="text-align: center;margin-top:0;padding-top:0;">
                SERTIFIKAT KEPESERTAAN ASURANSI JIWA SYARIAH<br />
                No Polis : {{isset($data->polis->no_polis) ? $data->polis->no_polis : '-'}}
            </h5>
            <table style="width: 100%;" class="style1">
                <tr>
                    <td style="width: 100px;">Nama</td>
                    <td style="width: 5px;"> : </td>
                    <td>{{$data->nama}}</td>
                </tr>
                <tr>
                    <td>Nomor Peserta</td>
                    <td> : </td>
                    <td>{{$data->no_peserta}}</td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td> : </td>
                    <td>{{date('d M Y',strtotime($data->tanggal_lahir))}}</td>
                </tr>
            </table>
            <p style="margin-top: 2px;margin-bottom: 2px;">Adalah Peserta dari Pemegang Polis Asuransi Jiwa Syariah:</p>
            <h5 style="text-align: center;margin-top: 0;margin-bottom: 8px;">{{isset($data->polis->nama) ? $data->polis->nama : '-'}}</h5>
            <p style="margin-top:0;margin-bottom:0;">Dengan ketentuan Asuransi sebagai berikut:</p>
            <div style="width:55%; float:left">
                <table  class="style1">
                    <tr>
                        <td style="width: 100px;">Produk Asuransi </td>
                        <td> : </td>
                        <td>{{isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Manfaat Asuransi </td>
                        <td> : </td>
                        <td>Menurun</td>
                    </tr>
                    <tr>
                        <td>Masa Asuransi </td>
                        <td> : </td>
                        <td>{{$data->masa_bulan}} Bulan</td>
                    </tr>
                    <tr>
                        <td>Periode Asuransi </td>
                        <td> : </td>
                        <td>{{date('d M Y',strtotime($data->tanggal_mulai))}} s/d {{date('d M Y',strtotime($data->tanggal_akhir))}}</td>
                    </tr>
                    <tr>
                        <td>Uang Asuransi </td>
                        <td> : </td>
                        <td>{{format_idr($data->basic)}}</td>
                    </tr>
                </table>
            </div>
            <div style="width:45%; float:left">
                <table class="style1">
                    <!-- <tr>
                        <td style="width:120px;">Dana Tabbaru </td>
                        <td> : </td>
                        <td>{{isset($data->dana_tabarru) ? format_idr($data->dana_tabarru) : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Dana Ujrah </td>
                        <td> : </td>
                        <td>{{isset($data->dana_ujrah) ? format_idr($data->dana_ujrah) : '-'}}</td>
                    </tr> -->
                    <tr>
                        <td>Kontribusi Gross </td>
                        <td> : </td>
                        <td>{{isset($data->kontribusi) ? format_idr($data->kontribusi) : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Extra Kontribusi </td>
                        <td> : </td>
                        <td>{{isset($data->extra_kontribusi) ? format_idr($data->extra_kontribusi) : '-'}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Total Kontribusi </th>
                        <th> : </th>
                        <th>{{format_idr($data->extra_mortalita+$data->kontribusi+$data->extra_kontribusi)}}</th>
                    </tr>
                </table>
            </div>
            <div style="clear:both;"></div>
            <div style="width: 55%;float:left">
                <div style="font-size: 8px;padding:10px; border: 1px solid;width: 240px;margin-top:80px;">
                    Sertifikat ini tunduk pada Ketentuan Polis Asuransi serta ketentuan lain yang tercantum di dalam atau melekat pada Polis dan merupakan bagian yang tidak terpisahkan dari Perjanjian Asuransi.
                </div>
            </div>
            <div style="width: 45%;float:left">
                <div style="padding-top: 5px;position: relative;">
                    Jakarta, {{date('d M Y',strtotime($data->created_at))}}<br />
                    <strong>PT Asuransi Jiwa Reliance Indonesia Unit Syariah</strong><br />
                    <img src="{{public_path('logo-small.jpeg')}}" style="height: 50px;margin-left: 20px;position: absolute;right:70px;top:70px;" />
                    <img src="{{public_path('ttd.png')}}" style="height: 110px;z-index: 10" />
                    <p style="margin-bottom:0;margin-top:0;padding-top:0;"><strong><u>Gideon Heru Prasetya</u></strong></p>
                    Direktur Utama
                </div>
            </div>
            <div style="clear:both;"></div>
            <p><i style="font-size: 8px;">*Sertifikat Asuransi ini berlaku apabila pembayaran sudah dilakukan dan efektif masuk ke dalam rekening PT Asuransi jiwa Reliance Indonesia Unit Syariah</i></p>
        </div>

        <div style="position: relative;z-index:1;margin-top: 610px;padding-top: 60px;height: 600px;">
            <div style="width: 80%;margin:auto;">
                <h5 style="text-align: center;"><u>SYARAT-SYARAT DAN KETENTUAN ASURANSI JIWA RELIANCE PEMBIAYAAN SYARIAH</u></h5>
                <p style="padding-bottom:0;margin-bottom:0;margin-top:0px;text-align:justify">Asuransi Jiwa Reliance Pembiayaan Syariah adalah Produk Asuransi jiwa syariah yang memberikan manfaat Asuransi kepada Penerima Manfaat berupa pelunasan sisa pokok pembiayaan tidak termasuk tunggakan angsuran (baik pokok angsuran maupun margin pembiayaan) dan denda keterlambatan (bila ada) apabila Peserta mengalami Musibah (meninggal dunia karena sakit maupun kecelakaan) dalam Masa Asuransi.</p>
                <table class="table-no-padding">
                    <tr>
                        <th style="text-align:left;">1.</th>
                        <th colspan="2" style="text-align:left;">Manfaat Asuransi</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">Asuransi Jiwa untuk meninggal dunia</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Manfaat Asuransi mengacu pada Nilai Manfaat Asuransi seperti dinyatakan dalam Polis Induk dan Sertifikat Kepesertaan </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px">-</td>
                        <td>Manfaat Asuransi ini berlaku 24 jam, diseluruh dunia.</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">2.</th>
                        <th style="text-align:left" colspan="2">Pengajuan Bukti Klaim</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            Dalam hal terjadi kematian, harap hubungi Pemegang Polis atau PT Asuransi Jiwa Reliance Indonesia Unit Syariah.
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align:left">3.</th>
                        <th style="text-align:left" colspan="2">Berakhir Manfaat Asuransi </th>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Peserta meninggal dunia</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Fotocopy Identitas Diri Kepesertaan (ktp/sim/passport)</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Akta Kematian/Surat keterangan meninggal dari instansi pemerintahan yang berwenang</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Bila Meninggal di Rumah Sakit, lampirkan surat keterangan meninggal dan resume dari rumah sakit</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Bila meninggal karena kecelakaan, lampirkan surat keterangan kecelakaan dari kepolisian</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Surat keterangan atau tanda bukti ahli waris (ktp dan kartu keluarga atau surat kuasa ahli waris)</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 30px"> - </td>
                        <td>Dokumen dan syarat lain apabila diperlukan</td>
                    </tr>
                </table>
                <p style="text-align:center"><strong>CATATAN: SYARAT-SYARAT DAN KETENTUAN ASURANSI YANG LENGKAP TERDAPAT DALAM POLIS</strong></p>
            </div>
        </div>
        <img src="assets/img/bg-sertifikat.jpeg" style="width: 21cm;height: 700px;position: absolute; bottom:0;right:0;z-index:0" />
        @if($data->ari_data)
            @php($data_ari = json_decode($data->ari_data))
            <div class="page_break"></div> 
            <div class="x">
                <img src="assets/img/logo-syariah.png" width="100" style="margin-top: 20px;margin-left: 60px;" />
                <div style="padding-left: 50px;padding-right: 50px;">
                    <hr />
                    <h2 class="text-center" style="padding-bottom:0;margin-bottom:0;">SERTIFIKAT ASURANSI PEMBIAYAAN MULTIGUNA SYARIAH</h2>
                    <h2 class="text-center" style="margin-top:0;padding-top:0;">NO: {{$data_ari->PolicyNo}}</h2>
                    <p class="text-justify">   
                        Sertifikat Asuransi Pembiayaan Multiguna Syariah ini merupakan bagian tak terpisahkan dari Polis Induk dan merupakan ringkasan dari Obyek yang diasuransikan.<br />
                        Sertifikat Asuransi Pembiayaan Multiguna Syariah ini berlaku untuk obyek asuransi dibawah ini :
                    </p>
                    <table class="style2">
                        <tr>
                            <th class="text-left" colspan="2">PEMEGANG POLIS</th>
                        </tr>
                        <tr>
                            <td class="pl-20">NOMOR POLIS INDUK</td>
                            <td> : {{$data_ari->MasterPolicyNo}}</td>
                        </tr>
                        <tr>
                            <td class="pl-20">NAMA PEMEGANG POLIS</td>
                            <td> : {{isset($data->polis->nama) ? $data->polis->nama : '-'}}</td>
                        </tr>
                        <tr>
                            <td class="pl-20">ALAMAT PEMEGANG POLIS</td>
                            <td> : </td>
                        </tr>
                        <tr>
                            <th class="text-left" colspan="2">PEMEGANG POLIS</th>
                        </tr>
                        <tr>
                            <td class="pl-20">NAMA</td>
                            <td> : {{$data->nama}}</td>
                        </tr>
                        <tr>
                            <td class="pl-20">ALAMAT</td>
                            <td> : {{$data->alamat}}</td>
                        </tr>
                        <tr>
                            <td class="pl-20">NIK</td>
                            <td> : {{$data->no_ktp}}</td>
                        </tr>
                        <tr>
                            <td class="pl-20">NO KONTRAK</td>
                            <td> : {{$data_ari->ContractNo}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">PERIODE ASURANSI</th>
                            <td> : Mulai dari tanggal {{date('d F Y',strtotime($data->tanggal_mulai))}}  sampai dengan tanggal  {{date('d F Y',strtotime($data->tanggal_akhir))}}</td>
                        </tr>
                        -<tr>
                            <th class="text-left">NILAI ASURANSI</th>
                            <td> : Rp. {{format_idr($data->basic)}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">SUKU KONTRIBUSI</th>
                            <td> : {{$data->rate}} s%</td>
                        </tr>
                        <tr>
                            <th class="text-left">PERHITUNGAN KONTRIBUSI</th>
                            <td> : Rp. {{format_idr($data->basic)}} x {{$data->rate}} % = Rp. {{format_idr($data_ari->PremiumAmount)}}</td>
                        </tr>
                    </table>
                    <div style="width: 40%;float:right;margin-top: 10px;">
                        <p>JAKARTA, {{date('d F Y',strtotime($data->created_at))}}</p>
                        <p>PT ASURANSI RELIANCE INDONESIA</p>
                        <!-- <br />
                        <img src="assets/img/TTD-Sukarman-untuk-Polis-General.png" style="width: 100px;" />
                        <p>TTD (JPG/PNG)</p> -->
                    </p>
                </div>
            </div>
        @endif
    </body>
</html>