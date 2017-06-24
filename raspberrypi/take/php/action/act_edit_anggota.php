<?php
include "../config.php";

$id_user = $_POST['id_user'];
$nim =$_POST['nim'];
$nama = $_POST['nama'];
$jk = $_POST['jk'];
$alamat  = $_POST['alamat'];
$nope = $_POST['nope'];
$data_sidik = $_POST['data_sidik'];

if ($id_user=="") {
echo "<script>alert('Pilih dulu data yang akan di-update');</script>";
echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
} else {

If ($nim==""&&$nama==""&&$jk==""&&$alamat==""&&$nope==""&&$data_sidik=="") {
Echo "Pengisian form belum benar. Ulangi lagi";
echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
} else {
$query = mysqli_query($con, "UPDATE data_anggota SET nim='$nim', nama='$nama', jk='$jk', alamat='$alamat', nope='$nope', data_sidik='$data_sidik' WHERE id_user='$id_user' ");

If ($query) {
Echo "Data Anda berhasil diupdate<br>";
Echo "Nim = <b>$nim</b></br>";
Echo "Nama = <b>$nama</b><br>";
Echo "Jenis Kelamin = <b>$jk</b><br>";
Echo "Alamat = <b>$alamat</b><br>";
Echo "No HP = <b>$nope</b><br><br>";
Echo "Sidik Jari = <b>$data_sidik</b><br><br>";
Echo "Terima kasih,";
echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
} else {
Echo "Data anda gagal diupdate. Ulangi sekali lagi";
echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
}
}
}
?>
