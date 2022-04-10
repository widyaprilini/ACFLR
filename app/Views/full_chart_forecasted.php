<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <title>Zoom Chart</title>
</head>
<body style="background-image: linear-gradient(to top, rgba(135,206,235,0), rgba(135,206,235,0.3));">
    <div class="container">
        <div class="col-md-12">
            <h3 class="text-center mt-3 pb-1">Grafik Prediksi Volume Ekspor Karet Alam <?= 1+$first_year['year'].' - '.$latest_year['year'] ?></h3>
            <hr>
            <canvas id="myChart" class="mt-5"></canvas>            
        </div>
    </div>
</body>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
          <?php
          $data=$actual_value;
          $count=count($actual_value);
            if ($count>0) {
              for($i=1;$i<$count;$i++){
                echo "'" .$data[$i]['year'] ."',";
              }
            }
          ?>
        ],
        datasets: [
          {
            label: 'Volume Ekspor (ton) - ACFLR',
            fill: false,
            borderColor: 'red',
            data: [
              <?php
              $data=$forecast->acflr->result_acflr->defuzz;
              $count=count($forecast->acflr->result_acflr->defuzz);
                if ($count>0) {
                  for($i=1;$i<$count;$i++){
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
              $data=$actual_value;
              $count=count($actual_value);
                if ($count>0) {
                  for($i=1;$i<$count;$i++){
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
              $data=$forecast->fts->defuzz;
              $count=count($forecast->fts->defuzz);
                if ($count>0) {
                  for($i=1;$i<$count;$i++){
                    echo $data[$i]['result'] . ", ";
                  }
                }
              ?>
            ]
        }]
    }, 
});

</script>
</html>