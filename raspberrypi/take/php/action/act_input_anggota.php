<?php
include "../config.php";

$id_user = $_POST['id_user'];
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$jk = $_POST['jk'];
$alamat  = $_POST['alamat'];
$nope = $_POST['nope'];
$data  = $_POST['data'];



		
	
	
	if ($id_user == "" || $nim == "" || $nama == "" || $jk == "" || $alamat == "" || $nope == "" || $data =="") {
		echo "<script>alert('Pengisian form belum benar. Ulangi lagi');</script>";
		echo "<meta http-equiv='refresh' content='0; url=../../page/form-input-anggota.php'>";
	}
else{
		$cek = mysqli_query($con, "SELECT * FROM data_anggota WHERE nim ='$nim'");
		$hasil_cek = mysqli_num_rows($cek);
		if ($hasil_cek > 0){
			echo "<script>alert('Data anggota dengan NIM/NIP $id sudah ada !')</script>";
			echo "<meta http-equiv='refresh' content='0; url=../../page/form-input-anggota.php'>";
		} 
		else  {
			//$query = mysqli_query($con, "INSERT INTO data_anggota VALUES ('$id', '$nama', '$jk', '$status', '$alamat', '$no_hp', '0')");
			$query = mysqli_query($con,"UPDATE data_anggota SET nim ='$nim', nama='$nama', jk='$jk', alamat='$alamat', nope='$nope' WHERE id_user='$id_user'");
			//$query = mysqli_query($con, "INSERT INTO data _anggota VALUES ('$nim', '$nama', '$jk', '$alamat', '$nope')");
			if($query){
				echo "<script>alert('Data berhasil ditambahkan. Terima Kasih');</script>";
				echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
			} 
			else{
				echo "<script>alert('Data anda gagal dimasukkan.+mysql_error();+Ulangi sekali lagi')</script>";
				echo mysql_error();
				echo "<meta http-equiv='refresh' content='0; url=../../page/form-input-anggota.php'>";
			}
		}
	}
	
		
?>