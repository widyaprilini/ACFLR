<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda Tugas Akhir</title>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
  <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
  <style type="text/css">
    table.table-bordered{
    border:1px solid black;
  }
  table.table-bordered > thead > tr > th{
    border:1px solid black;
  }
  table.table-bordered > tbody > tr > td{
    border:1px solid black;
  }
  
  </style>
</head>
<!-- style="background-color: rgba(135,206,235,0.1);" -->
<!-- style="background-image: linear-gradient(to top, rgba(135,206,235,0), rgba(135,206,235,0.1));" -->
<body style="background-image: linear-gradient(to top, rgba(135,206,235,0), rgba(135,206,235,0.3));">
  <a href="/"><button title="Kembali" class="btn btn-outline-secondary float-start m-4" style="font-size: 15px;">← </button></a>
  <div class="container">
    <nav>
      <h3 class="mt-3">Perhitungan Prediksi</h3>
      <h5>Automatic Clustering dan Fuzzy Logical Relationship untuk Prediksi Volume Karet Alam Indonesia</h5>
      <hr>
    </nav>
    <div class="row first">
      <div class="col-md-4">
        <h6 class="text-center pb-1">Pengurutan Data & Perhitungan average_diff</h6>
        <hr>
        <div style=" height: 360px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;">
                <th>No</th>
                <th>Volume Ekspor (ton)</th>
                <th>di+1 - di</th>
              </tr>
            </thead>
            <tbody>
            <?php $i = 1;
              foreach ($asc_value as $index => $value) : ?>
                <tr style="text-align: center;">
                  <td><?= $i++ ?></td>
                  <td style="text-align: right;"><?= number_format($value['actual_vol'],2,',','.') ?></td>
                  <td style="text-align: right;"><?= number_format($forecast->acflr->first_init->avg[$index],0,',','.') ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
        <hr>
        <h6>Average Difference</h6>
        <h3 class="text-center"><?= number_format($forecast->acflr->first_init->average_diff, 3,',','.') ?></h3>

      </div>
      <div class="col-md-3">
        <h6 class="text-center pb-1">Klasterisasi</h6>
        <hr>
        <div style=" height: 440px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;">
                <th>Klaster</th>
                <th>Anggota Klaster</th>

              </tr>
            </thead>
            <tbody>
              <?php $j = 1;
              foreach ($forecast->acflr->cluster_adjust as $key => $value) : ?>
                <?php foreach ($forecast->acflr->cluster_adjust[$key] as $key2 => $value2) : ?>
                  <tr style="text-align: center;">
                    <?php if ($key2 == 0) : ?>
                      <th rowspan="<?php echo count($value) ?>">
                        <?= $key + 1 ?>
                      </th>
                    <?php endif ?>
                    <td style="text-align: right;"><?= number_format($value2, 3,',','.') ?></td>
                  </tr>
                <?php endforeach ?>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-md-5">
        <h6 class="text-center pb-1">Pembentukan Interval</h6>
        <hr>
        <div style=" height: 440px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;">
                <th>No</th>
                <th>Batas Bawah</th>
                <th>Batas Atas</th>
                <th>Nilai Tengah</th>

              </tr>
            </thead>
            <tbody>
              <?php $j = 1;
              foreach ($forecast->acflr->sub_interval as $value) : ?>
                <tr style="text-align: center;">
                  <td><?= $j++ ?></td>
                  <td style="text-align: right;"><?= number_format($value['lBound'], 2,',','.') ?></td>
                  <td style="text-align: right;"><?= number_format($value['uBound'], 2,',','.') ?></td>
                  <td style="text-align: right;"><?= number_format($value['midPoint'], 2,',','.') ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
      <hr>
    </div>

    <div class="row second mt-3">
      <hr>
      <div class="col-md-4">
        <h6 class="text-center pb-1">Fuzzifikasi</h6>
        <hr>
        <div style=" height: 440px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;">
                <th>No</th>
                <th>Tahun</th>
                <th>Volume Ekspor (ton)</th>
                <th>Nilai Linguistik</th>
              </tr>
            </thead>
            <tbody>
              <?php $j = 1;
              foreach ($forecast->acflr->flr_process as $value) : ?>
                <tr style="text-align: center;">
                  <td><?= $j++ ?></td>
                  <td><?= $value['year'] ?></td>
                  <td style="text-align: right;"><?= number_format($value['vol'],2,',','.') ?></td>
                  <td style="text-align: center;">A<?= $value['lh'] ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <h6 class="text-center pb-1">Fuzzy Logic Relationship (FLR)</h6>
        <hr>
        <div style=" height: 440px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;">
                <th>No</th>
                <th>FLR</th>

              </tr>
            </thead>
            <tbody>
              <?php $j = 1;
              foreach ($forecast->acflr->flr_process as $value) : ?>
                <tr style="text-align: center;">
                  <td><?= $j++ ?></td>
                  <td>A<?= $value['lh'] ?>→A<?= $value['rh'] ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-md-4 mb-2">
        <h6 class="text-center pb-1">Fuzzy Logic Relationship Group (FLRG)</h6>
        <hr>
        <div style=" height: 440px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;">
                <th>No</th>
                <th>Nilai Linguistik</th>
                <th>FLRG</th>


              </tr>
            </thead>
            <tbody>
              <?php $j = 1;
              foreach ($forecast->acflr->flrg_process as $key => $value) : ?>

                <tr style="text-align: center;">
                  <td><?= $j++ ?></td>
                  <td>A<?= $key; ?></td>
                  <td style="text-align: left;">
                    <?php $temp = null;
                    foreach ($forecast->acflr->flrg_process[$key] as $key2 => $value2) :
                      $temp[] = 'A' . $value2['rh_fuzzy'] . '(' . $value2['count'] . ')';
                      $implode[$key] = implode(", ", $temp);
                    endforeach;
                    echo $implode[$key]; ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
      <hr>
    </div>

    <div class="row third mt-3 mb-5">
      <hr>
      <div class="col-md-8">
        <h6 class="text-center pb-1">Hasil Prediksi</h6>
        <hr>
        <div style=" height: 480px; overflow-y: scroll;">
          <table class="table table-bordered" style="font-size: 15px;">
            <thead>
              <tr style="text-align: center;" class="align-middle">
                <th rowspan="2">No</th>
                <th rowspan="2">Tahun</th>
                <th rowspan="2">Data Aktual</th>
                <th colspan="2">Data Prediksi</th>
                <th colspan="2">Galat</th>

              </tr>
              <tr class="text-center">
                <th>FTS</th>
                <th>ACFLR</th>
                <th>FTS</th>
                <th>ACFLR</th>
              </tr>
            </thead>
            <tbody>
              <?php $j = 1;
              for ($i = 0; $i < count($forecast->fts->defuzz); $i++) : ?>
                <tr>
                  <th style="text-align: center;"><?= $j++ ?></th>
                  <td style="text-align: center;"><?= $first_year['year']++ ?></td>
                  <td style="text-align: right;"><?= number_format($forecast->fts->defuzz[$i]['actual'],2,',','.') ?></td>
                  <td style="text-align: right;"><?= number_format($forecast->fts->defuzz[$i]['result'],2,',','.') ?></td>
                  <td style="text-align: right;"><?= number_format($forecast->acflr->result_acflr->defuzz[$i]['result'],2,',','.') ?></td>
                  <td style="text-align: right;"><?= number_format($forecast->fts->defuzz[$i]['mape'],2,',','.') ?>%</td>
                  <td style="text-align: right;"><?= number_format($forecast->acflr->result_acflr->defuzz[$i]['mape'],2,',','.') ?>%</td>
                </tr>
              <?php endfor ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-md-4 text-center">
        <div class="top">
          <h6 class="pb-1">Hasil Prediksi Tahun <?= $latest_year['year'] + 1 ?></h6>
          <hr style="width: 300px; margin-left:auto;margin-right:auto">
          <table style="margin-left:auto;margin-right:auto" ;>
            <tr>
              <td style="text-align: left; width:100px;">
                <h5>FTS</h5>
              </td>
              <td style="text-align: right;">
                <h5>: <?= number_format($forecast->fts->upcoming, 2,',','.') ?> Ton</h5>
              </td>
            </tr>
            <tr>
              <td style="text-align: left; width:100px;">
                <h5>ACFLR</h5>
              </td>
              <td style="text-align: right;">
                <h5>: <?= number_format($forecast->acflr->result_acflr->upcoming,2,',','.') ?> Ton</h5>
              </td>
            </tr>
          </table>

        </div>
        <hr>
        <div class="middle mt-3">
          <h6 class=" pb-1">Nilai MAPE</h6>
          <hr style="width: 300px; margin-left:auto;margin-right:auto">
          <table style="margin-left:auto;margin-right:auto" ;>
            <tr>
              <td style="text-align: left; width:100px;">
                <h5>FTS</h5>
              </td>
              <td style="text-align: right;">
                <h5>: <?= number_format($forecast->fts->sum_error, 4,',','.') ?>%</h5>
              </td>
            </tr>
            <tr>
              <td style="text-align: left; width:100px;">
                <h5>ACFLR</h5>
              </td>
              <td style="text-align: right;">
                <h5>: <?= number_format($forecast->acflr->result_acflr->sum_error, 4,',','.') ?>%</h5>
              </td>
            </tr>
          </table>
        </div>

      </div>
    </div>
    <div class="row fourth">
      <hr>
      <div class="col-md-12 mt-3 text-center">
        <h6 class="pb-1">Visualisasi Hasil Prediksi Volume Ekspor Karet Alam Indonesia</h6>
        <hr style="width: 800px; margin-left:auto;margin-right:auto">
        <div class="bottom-content mt-2 p-2 border border-2" style="border-color: black;">
          <a href="/zoomin_forecasted" title="Perbesar Grafik" class="float-end" onclick="window.open(this.href, 'mywin', width=400, height=200);" target="_blank">
            <button class="btn btn-sm"><img src="/img/zoomin_icon_black.png" alt="" style="width: 25px;"></button>
          </a>
          <canvas id="myChart" class=" p-1"></canvas>
        </div>
      </div>
    </div>
    <footer class="text-center" style="height:50px;">
  <hr>
  <small>©Copyright Widya Aprilini 2022</small>
  </footer>
  </div>

</body>
<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        <?php
        $data = $actual_value;
        $count = count($actual_value);
        if ($count > 0) {
          for ($i = 1; $i < $count; $i++) {
            // foreach ($actual_value as $data) {
            echo "'" . $data[$i]['year'] . "',";
          }
        }
        ?>
      
      ],
      datasets: [{
          label: 'Volume Ekspor (ton) - ACFLR',
          fill: false,
          borderColor: 'red',
          data: [
            <?php
            $data = $forecast->acflr->result_acflr->defuzz;
            $count = count($forecast->acflr->result_acflr->defuzz);
            if ($count > 0) {
              for ($i = 1; $i < $count; $i++) {
                //  foreach ($forecast->acflr->result_acflr->defuzz as $data) {
                echo $data[$i]['result'] . ", ";
              }
            }
            ?>
          ]
        },
        {
          label: 'Volume Ekspor (ton) - Aktual',
          fill: false,
          borderColor: 'black',
          data: [
            <?php
            $data = $actual_value;
            $count = count($actual_value);
            if ($count > 0) {
              for ($i = 1; $i < $count; $i++) {
                echo $data[$i]['actual_vol'] . ", ";
              }
            }
            ?>
          ]
        },
        {
          label: 'Volume Ekspor (ton) - FTS',
          fill: false,
          borderColor: 'blue',
          data: [
            <?php
            $data = $forecast->fts->defuzz;
            $count = count($forecast->fts->defuzz);
            if ($count > 0) {
              for ($i = 1; $i < $count; $i++) {
                echo $data[$i]['result'] . ", ";
              }
            }
            ?>
          ]
        }
      ]
    },
  });
</script>

</html>