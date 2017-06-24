<?php
include "../config.php";

$id_user = $_GET['id_user'];

$query = mysqli_query($con, "DELETE FROM data_anggota WHERE id_user='$id_user'");

If ($query) {
Echo "<script>alert('Data berhasil dihapus')</script>";
echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
} else {
Echo "Data anda gagal dihapus. Ulangi sekali lagi";
echo "<meta http-equiv='refresh' content='0; url=../../page/view-anggota.php'>";
}

?>
