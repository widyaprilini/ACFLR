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
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
  <style type="text/css">
    a button:hover{
      opacity: 0.6;
    }
  </style>
</head>
<body style="background-image: linear-gradient(to top, rgba(135,206,235,0), rgba(135,206,235,0.3));">
  
<div class="container">
  <nav>
    <h3 class="mt-3">Beranda</h3>
    <h5>Automatic Clustering dan Fuzzy Logical Relationship untuk Prediksi Volume Karet Alam Indonesia</h5>
    <hr>
  </nav>
  <div class="row">
    <div class="col-md-6 pb-5">
      <h6 class="text-center pb-1">Data Volume Ekspor Karet Alam <?= $first_year['year'].' - '.$latest_year['year'] ?></h6>
      <hr>
      <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputData" style="margin-bottom: -35px;">Tambah Data</button>
		<table class="table table-striped table-bordered data" id="example">
			<thead>
				<tr style="text-align: center;">			
					<th>No</th>
					<th>Tahun</th>
					<th>Volume Ekspor (ton)</th>
				</tr>
			</thead>
			<tbody>  
      <?php $i = 1; foreach($actual_value as $value) :?>
				<tr style="text-align: center;">				
					<td><?= $i++?></td>
					<td><?= $value['year']?></td>
					<td style="text-align: right;"><?= number_format($value['actual_vol'],2,',','.')?></td>
				</tr>
				<?php endforeach?>
            </tbody>
        </table>
        
        
        </div>
        <div class="col-md-6">
          <div class="top">
          <h6 class="text-center pb-1">Visualisasi Data Volume Ekspor Karet Alam <?= $first_year['year'].' - '.$latest_year['year'] ?></h6>
          <hr>
          <div class="top-content mt-2 p-2 border border-2" >
            <a href="/zoomin" title="Perbesar Grafik" class="float-end" onclick="window.open(this.href, 'mywin', width=400, height=200);" target="_blank">
              <button class="btn btn-sm"><img src="/img/zoomin_icon_black.png" alt="" style="width: 25px;"></button>
            </a>
            <canvas id="myChart" class=" p-1"></canvas>   
          </div>
          </div>
          <hr>
          <div class="bottom text-center">
          <h6 class="pb-1">Hitung Prediksi Volume Ekspor Karet Alam</h6>
          <hr>
          <form action="/forecast-acflr" method="POST"><button type="submit" name="forecast-acflr" class="btn btn-secondary w-75">Hitung Prediksi</button></form>
          <!-- <a href="/forecast-acflr"><button type="button" class="btn btn-secondary w-75">Hitung Prediksi</button></a> -->
          <hr>
          </div>
        </div>
    </div>
    <footer class="text-center" style="height:50px;">
  <hr>
  <small>©Copyright Widya Aprilini 2022</small>
  </footer>
  </div>
<div class="modal fade" id="ModalInputData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Volume Ekspor Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/new_value" method="post">
          <div class="mb-3">
            <label for="year" class="col-form-label">Tahun:</label>
            <input type="text" class="form-control" readonly name="year" id="year" value="<?= $latest_year['year']+1 ?>">
          </div>
          <div class="mb-3">
            <label for="ev" class="col-form-label">Volume Ekspor (Ton):</label>
            <input type="number"class="form-control" id="ev"  step=".001" name="actual_vol" placeholder="Gunakan titik untuk bilangan desimal"></input>
            <small style="color: red;">*Gunakan titik sebagai koma pada bilangan desimal. Contoh : 2000000.5 (Dua Juta Koma Lima Ton)</small>
          </div>
          <div class="input-group mb-3">
          <div class="input-group-text">
            <input class="form-check mt-0" id="checkbox" type="checkbox" required>
            <small><label for="checkbox" style="margin-bottom: 4px; margin-left:6px;">Konfirmasi masukan benar <b><span style="color: red;">(data tidak dapat dihapus/diperbarui)</span></b></label></small>
          </div>
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
    </div>
  </div>
</div>
</body>
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "scrollY":"360px",
        "scrollCollapse": true,
        "paging":false,
        "bInfo" : false,
        "ordering": false,

        "language": {
          "search": "Cari:"
  }
    } );
} );
</script>
<!-- <script>
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script> -->
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