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
            <h3 class="text-center mt-3 pb-1">Grafik Volume Ekspor Karet Alam <?= $first_year['year'].' - '.$latest_year['year'] ?></h3>
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
            if (count($actual_value)>0) {
              foreach ($actual_value as $data) {
                echo "'" .$data['year'] ."',";
              }
            }
          ?>
        ],
        datasets: [{
            label: 'Volume Ekspor (ton)',
            fill: false,
            borderColor: 'black',
            data: [
              <?php
                if (count($actual_value)>0) {
                   foreach ($actual_value as $data) {
                    echo $data['actual_vol'] . ", ";
                  }
                }
              ?>
            ]
        }]
    },
});

</script>
</html>