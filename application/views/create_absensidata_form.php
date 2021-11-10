<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>upload excel</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<div id="body" class="container" style="padding: 5% 17%;">
	<form id="form_create_action" method="post" enctype="multipart/form-data">
		<div class="mb-3">
			<label for="nama_absensi" class="form-label">Nama Absensi</label>
			<input type="text" name="nama_absensi" id="nama_absensi" class="form-control" placeholder="Masukan Nama Tabel" required>
		</div>

		<div class="mb-3">
			<label for="jumlah_event_hari" class="form-label">Total Days</label>
			<input type="number" name="jumlah_event_hari" id="jumlah_event_hari" class="form-control" placeholder="Masukan jumlah hari" required>
		</div>

		<div class="mb-3">
			<label for="file_excel" class="form-label">Data Reference</label>
			<input type="file" name="file_excel" id="file_excel" class="form-control" required>
		</div>
		<div class="container">
			<div id="panel-body">
				
			</div>
		</div>
	    <div>
	    	<button type="submit" class="btn btn-danger">Create</button>
	    </div>
	</form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">

    $(document).on('change','#file_excel', function() {

    	var inputexcelfile = $(this).get(0)

    	var myFormData = new FormData();
		myFormData.append('file_excel', inputexcelfile.files[0]);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>Welcome/preview_excel_data",
            data: myFormData, //penggunaan FormData
            processData:false,
            contentType:false,
            cache:false,
            async:false,
            success: function(data){
                $('#panel-body').html(data);
            },
            error: function(error) {
                Swal.fire({
                  icon: 'error',
                  title: "Oops!",
                  text: 'Tidak dapat tersambung dengan server, pastikan koneksi anda aktif, jika masih terjadi hubungi admin IT'
                })
            }
        });
    });

    $(document).on('submit','#form_create_action', function(e) {

        e.preventDefault()
        
        if ($(this).valid) return false;

        var a = this

        var btnselected = $(document.activeElement)

        Swal.fire({
          title: 'Konfirmasi Tindakan',
          text: "Yakin disimpan?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {

            btnselected.html('<i class="fas fa-sync fa-spin"></i>').addClass('disabled').attr('disabled')

            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>Welcome/create_action",
                data:new FormData(a), //penggunaan FormData
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                
                    Swal.fire({
                      icon: 'success',
                      title: "Sukses",
                      text: 'Data berhasil tercatat'
                    })
                    $('#body').html(data);
                },
                error: function(error) {
                    Swal.fire({
                      icon: 'error',
                      title: "Oops!",
                      text: 'Tidak dapat tersambung dengan server, pastikan koneksi anda aktif, jika masih terjadi hubungi admin IT'
                    })
                    btnselected.html('Create').removeClass('disabled').removeAttr('disabled')
                }
            });

          }
        })

    })
</script>

</body>
</html>