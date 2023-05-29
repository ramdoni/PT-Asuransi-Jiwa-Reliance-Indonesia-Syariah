<?php 

session_start();
$id = isset($_SESSION['userID']) ? $_SESSION['userID'] : ''; 
if(isset($_SESSION["userID"]) ) {
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Perhitungan</title>
    <style type="text/css">
      .pull-left {
        float: left;
      }
      table tr td {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
      }
      * {
        font-size: 12px;
      }
      .box_ {
        border: 1px solid #e0e0e0; 
        border-radius: 3px!important; 
        box-shadow: 0 0 10px 0 rgba(0,0,0,.1)!important;
      }
    </style>
    <!-- Bootstrap core CSS -->
  <link href="https://getbootstrap.com/docs/4.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <body>
    <a href="logout.php" style="position: absolute;right:20px; top: 15px;"> <img src="image/logout.png" style="height: 27px;" /></a>
    <div>
      <p>
        <img src="image/logo.jpg" style="height: 100px;margin-top:-15px;margin-bottom: -20px;" />
      </p>
        <form method="POST" action="" style="padding: 5px;">

          <div class="col-md-4 box_ pt-4 pb-4 float-left" style="min-height: 233px;">
            <div class="form-group">
              <div class="col-md-4 float-left mt-2">
                <label>Umur</label>
              </div>
              <div class="col-md-8 float-left">
                <input type="number" class="form-control" name="umur" />
              </div>
            </div><div class="clearfix"></div>
            <div class="form-group mt-1">
              <div class="col-md-4 float-left mt-2">
                <label>Tinggi Badan</label>
              </div>
              <div class="col-md-8 float-left">
                <input type="number" class="form-control" name="tinggi_badan" />
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group mt-1">
              <div class="col-md-4 float-left mt-2">
                <label>Berat Badan</label>
              </div>
              <div class="col-md-8 float-left">
                <input type="number" class="form-control" name="berat_badan" />
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group mt-1">
              <div class="col-md-4 float-left mt-2">
                <label>BMI</label>
              </div>
              <div class="col-md-8 float-left bmi pt-2"></div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group mt-1">
              <div class="col-md-4 float-left mt-2">
                <label>Kriteria Badan</label>
              </div>
              <div class="col-md-8 float-left kriteria_badan pt-2"></div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-4 float-left mt-2">
                <label>Extra Mortalita (%)</label>
              </div>
              <div class="col-md-8 float-left umur_em pt-2" style="font-weight: bold;"></div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="col-md-4 box_ pt-2 ml-1 mb-4 pb-4 pt-4 float-left" style="min-height: 233px;">
            <h4 class="pl-3">Perokok</h4>
            <div class="form-group">
              <div class="col-md-4 float-left mt-2">
                <label>Jumlah Rokok / Hari</label>
              </div>
              <div class="col-md-4 float-left mt-2">
                <input type="number" class="form-control" name="rokok" />
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-4 float-left mt-2">
                <label>Extra Mortalita (%)</label>
              </div>
              <div class="col-md-4 float-left rokok_em pt-2" style="font-weight: bold;"></div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="col-md-4 box_ pt-4 pb-4 float-left ml-1" style="margin-right: -10px;">
            <h4 class="pl-3">Glukosa Darah </h4>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Gula Darah Puasa (GDP) (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="glukosa_1" />
              </div>
              <div class="col-md-3 float-left glukosa_1"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Gula darah 2 jam setelah makan (GDPP) (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="glukosa_2" />
              </div>
              <div class="col-md-3 float-left glukosa_2"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Gula Darah Sesat (GDS) (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="glukosa_3" />
              </div>
              <div class="col-md-3 float-left glukosa_3"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Tes A1C (%) </label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="glukosa_4" />
              </div>
              <div class="col-md-3 float-left glukosa_4"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Extra Mortalita (%)</label>
              </div>
              <div class="col-md-6 float-left glukosa_5 mt-2" style="font-weight: bold;"></div>
            </div><div class="clearfix"></div>
          </div>

          <div class="col-md-4 box_ float-left pt-4 pb-3" style="min-height: 233px;">
            <h4 class="pl-3">Tekanan Darah</h4>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Tekanan Darah Sistolik (mmHg)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="tekanan_darah_1" />
              </div>
              <div class="col-md-3 float-left tekanan_darah_1"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Tekanan Darah Diastolik (mmHg)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="tekanan_darah_2" />
              </div>
              <div class="col-md-3 float-left tekanan_darah_2"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Extra Mortalita (%)</label>
              </div>
              <div class="col-md-3 float-left tekanan_darah_3 mt-2" style="font-weight: bold;">
              </div>
            </div><div class="clearfix"></div>
            <p class="ml-3 mt-4">Dicetak ulang dari The Seventh Report of the Joint National Committee on Prevention, Detection,Evaluation, and Treatment of High Blood Pressure (JNC‐VII). NIH Publication 03‐5233. Bethesda, 2003.</p>
          </div>

          <div class="col-md-4 box_ pt-4 pb-4 ml-1 float-left" style="min-height: 233px;">
            <h4 class="pl-3">Lipids</h4>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Cholesterol Total (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="lipid_1" />
              </div>
              <div class="col-md-3 float-left lipid_1"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Cholesterol LDL Direk (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="lipid_2" />
              </div>
              <div class="col-md-3 float-left lipid_2"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Cholesterol HDL (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="lipid_3" />
              </div>
              <div class="col-md-3 float-left lipid_3"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Trigliserida (mg/dL)</label>
              </div>
              <div class="col-md-3 float-left">
                <input type="number" class="form-control" name="lipid_4" />
              </div>
              <div class="col-md-3 float-left lipid_4"></div>
            </div><div class="clearfix"></div>
            <div class="form-group">
              <div class="col-md-6 float-left mt-2">
                <label>Extra Mortalita (%)</label>
              </div>
              <div class="col-md-3 float-left mt-2 lipid_5" style="font-weight: bold;">
              </div>
            </div><div class="clearfix"></div>
          </div>

          <div class="col-md-4 box_ float-left ml-1 pt-4 pb-3" style="margin-right: -10px; min-height: 233px;">
            <div class="form-group">
              <div class="col-md-12 mt-1" style="text-align: center;">
                <label><h4>Total Extra Mortalita (%)</h4></label>
              </div>
              <div class="col-md-12 margin-auto" style="text-align: center;">
                <h1 class="total_em" style="color: red;">0</h1>
              </div>
            </div><div class="clearfix"></div><br />
            <p class="ml-4">
              Jumlah kematian (umumnya, atau karena akibat yang spesifik) pada suatu populasi, skala besar suatu populasi, per dikali satuan. Mortalitas khusus mengekspresikan pada jumlah satuan kematian per 1000 individu per tahun, hingga, rata-rata mortalitas sebesar 9.5 berarti pada populasi 100.000 terdapat 950 kematian per tahun
            </p>
          </div>
          <div class="clearfix"></div>
        </form>
    </div>
    <footer>
      <div class="col-md-12">
        <p>Copyright &copy; <?=date('Y')?> <a href="http://www.stalavista.com">PT Stalavista Evolusi Technology</a> All right reserved.</p>
      </div>
    </footer>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="/docs/4.2/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
  <script src="https://getbootstrap.com/docs/4.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-zDnhMsjVZfS3hiP7oCBRmfjkQC4fzxVxFhBx8Hkz2aZX8gEvA/jsP3eXRCvzTofP" crossorigin="anonymous"></script>
  <script type="text/javascript">
  
  var lipid_1 = 0;
  var lipid_2 = 0;
  var lipid_3 = 0;
  var lipid_4 = 0;

  var glukosa_1 = 0;
  var glukosa_2 = 0;
  var glukosa_3 = 0;

  var tekanan_darah_em = 0;
  var glukosa_darah_em = 0;
  var umur_em = 0;
  var rokok_em = 0;

  $("input[name='lipid_1']").on('input', function(){

    var a="";
    if($(this).val() < 200)
    {
      lipid_1 = 1;
      a = 'Normal';
    }
    if(inRange($(this).val(),200,239))
    {
      lipid_1 = 2;
      a = 'Batas Tinggi';
    }
    if($(this).val() >= 240 )
    {
      lipid_1 = 3;
      a = 'Tinggi';
    }

    calculate_lipid();
    
    if(a == 'Normal')
    {
      a = '<label style="color: green">'+ a +'</label>';
    }
    else
    {
      a = '<label style="color: red">'+ a +'</label>';
    }

    $('.lipid_1').html(a);
  });

  $("input[name='lipid_2']").on('input', function(){

    var a = "";
    if($(this).val() < 100)
    {
      a = 'Normal';
      lipid_2 = 1;
    }
    if(inRange($(this).val(),100,159))
    {
      a = 'Batas Tinggi';
      lipid_2 = 2;
    }
    if(inRange($(this).val(),160,189))
    {
      a = 'Tinggi';
      lipid_2 = 3;
    }
    if($(this).val() >= 190 )
    {
      a = 'Sangat Tinggi';
      lipid_2 = 4;
    }
    
    calculate_lipid();
    if(a == 'Normal')
    {
      a = '<label style="color: green">'+ a +'</label>';
    }
    else
    {
      a = '<label style="color: red">'+ a +'</label>';
    }
    $('.lipid_2').html(a);
  });

  $("input[name='lipid_3']").on('input', function(){

    var a = "";
    if($(this).val() < 40)
    {
      a = 'Buruk';
      lipid_3 = 1;
    }
    if(inRange($(this).val(),40,59))
    {
      a = 'Baik';
      lipid_3 = 2;
    }
    if($(this).val() >= 60 )
    {
      a = 'Sangat Baik';
      lipid_3 = 3;
    }
    
    calculate_lipid();

    if(a == 'Buruk')
    {
      a = '<label style="color: red">'+ a +'</label>';
    }
    else
    {
      a = '<label style="color: green">'+ a +'</label>';
    }
    $('.lipid_3').html(a);
  });
  $("input[name='lipid_4']").on('input', function(){

    var a = "";
    if($(this).val() < 150)
    {
      a = 'Normal';
      lipid_4 = 1;
    }
    if(inRange($(this).val(),150,159))
    {
      a = 'Batas Tinggi';
      lipid_4 = 2;
    }
    if(inRange($(this).val(),200,499))
    {
      a = 'Tinggi';
      lipid_4 = 3; 
    }
    if($(this).val() >= 500 )
    {
      a = 'Sangat Tinggi';
      lipid_4 = 4; 
    }
    
    calculate_lipid();
    if(a == 'Normal')
    {
      a = '<label style="color: green">'+ a +'</label>';
    }
    else
    {
      a = '<label style="color: red">'+ a +'</label>';
    }
    $('.lipid_4').html(a);
  });

  $("input[name='glukosa_1']").on('input', function(){

      var glukosa = '';
      if($(this).val() <= 79)
      {
        glukosa = 'Hipoglikemia';
        glukosa_1 = 1;
      }
      if(inRange($(this).val(),80,109))
      {
        glukosa = 'Normal';
        glukosa_1 = 2;
      }
      if(inRange($(this).val(),110,125))
      {
        glukosa = 'Prediabetes';
        glukosa_1 = 3;
      }
      if(parseInt($(this).val()) >= 126)
      {
        glukosa = 'Hiperglikemia';
        glukosa_1 = 4;
      }
      calculate_glukosa_darah();
      
      if(glukosa == 'Normal')
      {
        $('.glukosa_1').html('<label style="color: green;">' + glukosa +'</label>');
      }
      else
      {
        $('.glukosa_1').html('<label style="color: red;">' + glukosa +'</label>');
      }
  });

  $("input[name='glukosa_2']").on('input', function(){

      var glukosa = '';
      if($(this).val() <= 79)
      {
        glukosa = 'Hipoglikemia';
        glukosa_2 = 1;
      }
      if(inRange($(this).val(),80,144))
      {
        glukosa = 'Normal';
        glukosa_2 = 2;
      }
      if(inRange($(this).val(),145,179))
      {
        glukosa = 'Prediabetes';
        glukosa_2 = 3;
      }
      if(parseInt($(this).val()) >= 180)
      {
        glukosa = 'Hiperglikemia';
        glukosa_2 = 4;
      }
      calculate_glukosa_darah();
      
      if(glukosa == 'Normal')
      {
        $('.glukosa_2').html('<label style="color: green;">' + glukosa +'</label>');
      }
      else
      {
        $('.glukosa_2').html('<label style="color: red;">' + glukosa +'</label>');
      }
  });

  $("input[name='glukosa_3']").on('input', function(){

      var glukosa = '';
      if($(this).val() <= 50)
      {
        glukosa = 'Hipoglikemia';
        glukosa_3 = 1;
      }
      if(inRange($(this).val(),51,109)) // <110
      {
        glukosa = 'Normal';
        glukosa_3 = 2;
      }
      if(inRange($(this).val(),110,199))
      {
        glukosa = 'Prediabetes';
        glukosa_3 = 3;
      }
      if(parseInt($(this).val()) >= 200)
      {
        glukosa = 'Hiperglikemia';
        glukosa_3 = 4;
      }

      if(glukosa == 'Normal')
      {
        $('.glukosa_3').html('<label style="color: green;">' + glukosa +'</label>');
      }
      else
      {
        $('.glukosa_3').html('<label style="color: red;">' + glukosa +'</label>');
      }
  });

  $("input[name='glukosa_4']").on('input', function(){

      var glukosa = '';
      if(parseInt($(this).val()) < 2.5)
      {
        glukosa = 'Hipoglikemia';
        glukosa_darah_em = 50;
      }
      if(inRange($(this).val(),2.5,6.0))
      {
        glukosa = 'Normal';
        glukosa_darah_em = 0;
      }
      if(inRange($(this).val(),6.1,8.0))
      {
        glukosa   = 'Prediabetes';
        glukosa_darah_em = 75;
      }
      if(parseInt($(this).val()) >= 8.1)
      {
        glukosa = 'Hiperglikemia';
        glukosa_darah_em = 150;
      }

      if(glukosa == 'Normal')
      {
        $('.glukosa_4').html('<label style="color: green;">' + glukosa +'</label>');
        $('.glukosa_5').html('<label style="color: green;">' + glukosa_darah_em +'</label>');
      }
      else
      {
        $('.glukosa_4').html('<label style="color: red;">' + glukosa +'</label>');
        $('.glukosa_5').html('<label style="color: red;">' + glukosa_darah_em +'</label>');
      }

      calculate_glukosa_darah();
      calculate_total_em();

  });

  function calculate_glukosa_darah()
  {
    var test_ac = $("input[name='glukosa_4']").val();
    if(test_ac == "")
    {
      var max = Math.max(glukosa_1,glukosa_2,glukosa_3);

      if(max == 1)
      {
        glukosa_darah_em = 50;
      }
      if(max == 2)
      {
        glukosa_darah_em = 0;
      }
      if(max == 3)
      {
        glukosa_darah_em = 75;
      }
      if(max == 4)
      {
        glukosa_darah_em = 150;
      }
    }
    
    $('.glukosa_5').html(glukosa_darah_em);

    calculate_total_em();
  }

  var tekanan_darah = "";
  $("input[name='tekanan_darah_1']").on('input', function(){

    if(inRange($(this).val(),80,99))
    {
      tekanan_darah = 'Hipotensi';
    }
    if(inRange($(this).val(),100,119))
    {
      tekanan_darah = 'Normal';
    }
    if(inRange($(this).val(),120,139))
    {
      tekanan_darah = 'Prehipertensi';
    }
    if(inRange($(this).val(),140,159))
    {
      tekanan_darah = 'Hipertensi Stage 1';
    }
    if($(this).val() >= 160)
    {
      tekanan_darah = 'Hipertensi Stage 2';
    }

    if(tekanan_darah == 'Normal')
    {
      $('.tekanan_darah_1').html('<label style="color: green">' + tekanan_darah +'</label>');
    }
    else
    {
      $('.tekanan_darah_1').html('<label style="color: red">' + tekanan_darah +'</label>');
    }
    
    calculate_em_tekanan_darah();

  });
  
  var tekanan_darah2 = "";
  $("input[name='tekanan_darah_2']").on('input', function(){

    if(inRange($(this).val(),40,69))
    {
      tekanan_darah2 = 'Hipotensi';
    }
    if(inRange($(this).val(),70,79))
    {
      tekanan_darah2 = 'Normal';
    }
    if(inRange($(this).val(),80,89))
    {
      tekanan_darah2 = 'Prehipertensi';
    }
    if(inRange($(this).val(),90,99))
    {
      tekanan_darah2 = 'Hipertensi Stage 1';
    }
    if($(this).val() >= 100)
    {
      tekanan_darah2 = 'Hipertensi Stage 2';
    }

    if(tekanan_darah2 == 'Normal')
    {
      $('.tekanan_darah_2').html('<label style="color: green">' + tekanan_darah2 +'</label>');
    }
    else
    {
      $('.tekanan_darah_2').html('<label style="color: red">' + tekanan_darah2 +'</label>');
    }

    calculate_em_tekanan_darah();
  });

  var lipid_em=0;
  function calculate_lipid()
  {
    var max = Math.max(lipid_1,lipid_2,lipid_4);

    if(max ==1 )
    {
      $('.lipid_5').html('<label style="color: green">0</label>');
      lipid_em = 0;
    }
    if(max ==2 )
    {
      $('.lipid_5').html('<label style="color: red">50</label>');
      lipid_em = 50;
    }
    if(max ==3 )
    {
      $('.lipid_5').html('<label style="color: red">75</label>');
      lipid_em = 75;
    }
    if(max ==4 )
    {
      $('.lipid_5').html('<label style="color: red">150</label>');
      lipid_em = 150;
    }
    calculate_total_em();
  }

  function calculate_total_em()
  {
    var total_em = lipid_em + tekanan_darah_em + glukosa_darah_em + umur_em + rokok_em;
    $('.total_em').html(total_em);
  }

  function calculate_em_tekanan_darah()
  {
    if(tekanan_darah == 'Hipotensi' && tekanan_darah2 == 'Hipotensi')
    {
      $('.tekanan_darah_3').html(25);
      tekanan_darah_em = 25;
    }
    if(tekanan_darah == 'Normal' && tekanan_darah2 == 'Normal')
    {
      $('.tekanan_darah_3').html('<label style="color: green">0</label>');
      tekanan_darah_em = 0;
    }
    if(tekanan_darah == 'Prehipertensi' || tekanan_darah2 == 'Prehipertensi')
    {
      $('.tekanan_darah_3').html('<label style="color: green">0</label>');
      tekanan_darah_em = 0;
    }
    if(tekanan_darah == 'Hipertensi Stage 1' || tekanan_darah2 == 'Hipertensi Stage 1')
    {
      if(parseInt($("input[name='umur']").val()) <= 45 || $("input[name='umur']").val() == "")
      {
        $('.tekanan_darah_3').html('<label style="color: red">50</label>');
        tekanan_darah_em = 50;
      }
      else if(inRange($("input[name='umur']").val(),46,64))
      {
        $('.tekanan_darah_3').html(25);
        tekanan_darah_em = 25;
      }
    } 
    if(tekanan_darah == 'Hipertensi Stage 2' || tekanan_darah2 == 'Hipertensi Stage 2')
    {
      if(parseInt($("input[name='umur']").val()) <= 45 || $("input[name='umur']").val() == "")
      {
        $('.tekanan_darah_3').html('<label style="color: red">150 atau Reas</label>');
        tekanan_darah_em = 150;
      }
      else if(inRange($("input[name='umur']").val(),46,64))
      {
        $('.tekanan_darah_3').html('<label style="color: red">75 atau Reas</label>');
        tekanan_darah_em = 75;
      }
    } 

    calculate_total_em();
  }

  $("input[name='umur'], input[name='tinggi_badan'], input[name='berat_badan']").on('input', function(){
    calculate();
  });

  $("input[name='rokok']").on('input', function(){
    calculate_rokok_em();
    calculate_total_em();
  });

  function calculate_rokok_em()
  {
    var rokok         = $("input[name='rokok']").val();

    // EM ROKOK
    if(inRange(rokok,0, 15))
    {
      rokok_em = 0;
    }
    if(inRange(rokok,16, 19))
    {
      rokok_em = 25;
    }
    if(inRange(rokok,20, 99))
    {
      rokok_em = 50;
    }

    if(rokok_em == 0)
    {
      $('.rokok_em').html('<label style="color:green;">0</label>');      
    }
    else
    {
      $('.rokok_em').html('<label style="color:red;">'+ rokok_em +'</label>');      
    }

  }

  function calculate()
  {
    var umur          = $("input[name='umur']").val(); 
    var tinggi_badan  = $("input[name='tinggi_badan']").val(); 
    var berat_badan   = $("input[name='berat_badan']").val(); 

    if(tinggi_badan !="" && berat_badan !="")
    {
      tinggi_badan  = parseInt(tinggi_badan) / 100;
      tinggi_badan  = tinggi_badan * tinggi_badan;
      berat_badan   = parseInt(berat_badan);

      var kriteria_badan = "";
      var bmi_ = berat_badan / tinggi_badan;
      bmi_ = bmi_.toFixed(2);

      if(inRange(bmi_,0,18.49))
      {
        kriteria_badan = 'Underweight';
      }
      if(inRange(bmi_,18.5, 24.99))
      {
        kriteria_badan = 'ideal';
      }
      if(inRange(bmi_,25, 29.99))
      {
        kriteria_badan = 'Overweight';
      }
      if(inRange(bmi_,30, 34.99))
      {
        kriteria_badan = 'Ob. Kelas1';
      }
      if(inRange(bmi_,35, 39.99))
      {
        kriteria_badan = 'Ob. Kelas2';
      }
      if(inRange(bmi_,40, 99))
      {
        kriteria_badan = 'Ob. Kelas3';
      }

      $('.bmi').html(bmi_);
      if(kriteria_badan == 'ideal')
      {
        $('.kriteria_badan').html('<label style="color: green">' + kriteria_badan +'</label>');
      }
      else
      {
        $('.kriteria_badan').html('<label style="color: red">' + kriteria_badan +'</label>');
      }

      if(inRange(bmi_,0, 27.99))
      {
        umur_em = 0;
      }
      if(inRange(bmi_,28, 31.99))
      {
        umur_em = 25;
      }
      if(inRange(bmi_,32, 35.99))
      {
        umur_em = 50;
      }
      if(inRange(bmi_,36, 99))
      {
        $('.umur_em').html('<label style="color: red">Decline</label>');
        return false;
      }
      
      if(umur_em == 0)
      {
        $('.umur_em').html('<label style="color: green;">' + umur_em + '</label>');
      }
      else
      {
        $('.umur_em').html('<label style="color: red;">' + umur_em + '</label>');
      }
    }
    
    calculate_em_tekanan_darah();
  }

  var truncateDecimals = function (number, digits) {
    var multiplier = Math.pow(10, digits),
        adjustedNum = number * multiplier,
        truncatedNum = Math[adjustedNum < 0 ? 'ceil' : 'floor'](adjustedNum);

    return truncatedNum / multiplier;
  };
  function inRange(x, min, max) {
    return ((x-min)*(x-max) <= 0);
  }

  </script>
</body>
</html>
<?php } else { ?>
  <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>EM Calculation Tools</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<style type="text/css">
  .login-form {
    width: 340px;
      margin: 50px auto;
  }
    .login-form form {
      margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .btn {        
        font-size: 15px;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="login-form">
    <form action="" method="post">
        <h4 class="text-center">EM Calculation Tools</h4>    
        <br />   
        <?php 
          if(isset($_POST['username']))
          {
            if($_POST['username'] == 'ajri' and $_POST['password'] =='12345678')
            {
              $_SESSION['userID'] = 'huhuy';
              header("Location:index.php");
            }
            else
            {
              echo '<p style="color: red;">Username / Password anda salah, silahkan di coba kembali.</p>';
            }
          }
        ?>
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username" required="required">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Log in</button>
        </div>        
    </form>
</div>
</body>
</html>                                                               
<?php } ?>
