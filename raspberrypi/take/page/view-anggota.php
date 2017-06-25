<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/font-open-sans.css"></link>
<link rel="stylesheet" type="text/css" href="../css/style.css"></link>
<link rel="stylesheet" type="text/css" href="../css/style_theme.css"></link>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css"></link>

<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.2.0/css/font-awesome.min.css" />

<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/plugin.js"></script>
<script type="text/javascript" src="../js/jquery.mixitup.min.js"></script>
<script type="text/javascript" src="../js/notify.min.js"></script>
<script type="text/javascript" src="../js/custom.js"></script>
<script type="text/javascript" src="../js/jquery.dcjqaccordion.2.7.js"></script>

</head>
<body>

<?php include "../php/config.php"; include "include/incluce_sidebar.html"; ?>

<div class="all padding-side">
	<div class="title_panel">
		> Data Anggota
	</div>
<div id="reg" class="full-form colorwh">
		
		<?php
		
		echo "<center><form action='' method='post'><input type='text' name='cari' value='Pencarian...' onfocus=\"this.value='';\" onblur=\"if(this.value=='') this.value='Pencarian...';\">&nbsp;&nbsp;
		<input type='submit' value='go' name='go'>&nbsp;&nbsp;&nbsp;*) nama, nim/nip</form></scenter>";

		//variabel _GET /
		$hal	= isset($_GET['hal']) ? $_GET['hal'] : "";


		//variabel _POST 
		$cari	= isset($_POST['cari']) ? $_POST['cari'] : "";
		$go		= isset($_POST['go']) ? $_POST['go'] : "";


		$per_halaman	= 10;   // jumlah record data per halaman

		if ($hal==""||$hal==1) {
			$awal=0;
		} else {
			$awal=($hal-1) * $per_halaman;
		}
		$batas=$per_halaman;



		if ($go == "go" && $cari != "Pencarian...") {
			$query		= mysqli_query($con, "SELECT * FROM data_anggota WHERE nim LIKE '%$cari%' OR nama LIKE '%$cari%' LIMIT $awal,$batas");
			$j_cari		= mysqli_num_rows($query);
			$jm_cari	= ceil($j_cari/$per_halaman);
		} else if ($go == "" || $cari == "Pencarian...") {
			$query		=mysqli_query($con, "SELECT * FROM data_anggota ORDER BY id LIMIT $awal,$batas");
			$j_cari		= mysqli_num_rows($query);
			$jm_cari	= ceil($j_cari/$per_halaman);
		}

		$query2=mysqli_query($con, "SELECT * FROM data_anggota");
		$jumlah_anggota=mysqli_num_rows($query2);
		$jum_halaman=ceil($jumlah_anggota/$per_halaman);
		//echo $jum_halaman;


		if ($jum_halaman==1) { // ||$jm_cari<=10
		echo "";
		} else {
		echo "<center><font size='3px'>Halaman : </font>";
		for ($i=1; $i<=$jum_halaman; $i++) {
			if ($i==$hal) {
			echo "<font size='4px' color='green'>[<a href='$_SERVER[PHP_SELF]?hal=$i'><b>$i</b></a>]</font>";
			} else {
			echo "<font size='2px' color='red'>[<a href='$_SERVER[PHP_SELF]?hal=$i'><b>$i</b></a>]</font>";
			}
		}
		echo "</center><hr>";
		}
		?>



		<table class="table-data" border=1 width=90% border=0 >

		<tr>
		<td class="td-data" colspan="8"><b>Jumlah Pencarian : <?php if ($j_cari==0) {echo "0";} else { echo $j_cari; } ?>| Jumlah Keseluruhan Orang : <?php echo $jumlah_anggota; ?> eks.</b></td></tr>
		<tr>
		<td class="head-data">ID</td>
		<td class="head-data">Nim</td>
		<td class="head-data">Nama</td>
		<td class="head-data">JK</td>
		<td class="head-data">Alamat</td>
		<td class="head-data">No HP</td>
		<td class="head-data">Edit</td>
		<td class="head-data">Hapus</td>
		</tr>

		<?php
		//$no=0;
		while ($hasil=mysqli_fetch_array($query)) {
		//$no++;
		echo "<tr>
			  <td class='td-data'><a href='view-riwayat.php?id_user=$hasil[id]'>$hasil[id]</td>
			  <td class='td-data'>$hasil[nim]</td>
			  <td class='td-data'>$hasil[nama]</td>
			  <td class='td-data'>$hasil[jk]</td>
			  <td class='td-data'>$hasil[alamat]</td>
			  <td class='td-data'>$hasil[nope]</td>
			  <td class='td-data'><a href='form-edit-anggota.php?id_user=$hasil[id]'><i class='fa fa-gear'></i> EDIT</a></td>
			  <td class='td-data'><a href='../php/action/act_hapus_anggota.php?id_user=$hasil[id]' onclick='return confirm(\"Anda yakin ingin menghapus data ini ?\")'><i class='fa fa-remove'></i> HAPUS</a></td>
			  </tr>";
		}
		?>
		</table>
		
</div>


</div>

	
		<script src="../js/classie.js"></script>
		<script src="../js/main.js"></script>
</body>
</html>