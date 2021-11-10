<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Master Data List</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div id="container" style="padding: 15%;">
	<a href="<?php echo base_url().'Welcome/create' ?>" class="btn btn-primary btn-sm">Tambah Data</a>
	<table class="table table-responsive table-hover table-bordered">
		<thead>
			<tr>
				<th>No.</th>
				<th>Nama Master Data</th>
				<th>Created at</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			foreach ($data_masterdata as $key => $value) {
				?>
				<tr>
					<td><?php echo $no++; ?></td>
					<td><?php echo $value->nama_masterdata; ?></td>
					<td><?php echo $value->created_at; ?></td>
					<td><a href="<?php echo base_url().'Welcome/read_masterdata/'.$value->kd_masterdata ?>" class="btn-xs btn btn-primary"><i class="fas fa-eye"></i></a></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>