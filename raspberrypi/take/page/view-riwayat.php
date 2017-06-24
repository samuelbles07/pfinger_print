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
<?php
include "include/incluce_sidebar.html"; 
?>
<div class="all padding-side">
	<div class="title_panel">
		> Riwayat
	</div>
	
	<?php
	include "../php/config.php";
include "../php/function/fungsi.php";
	
	$id_user = $_GET['id_user'];
	
	//$jam = $_POST['jam'];
	
	$data = mysqli_query($con,"SELECT jam FROM log_anggota WHERE id_user ='$id_user'");
	$jam = mysqli_fetch_array($data);
	
		//membuat keterangan hadir
		
		echo $jam[0];
		
		if(($jam[0] >= '08:00:00') AND ($jam[0] <= '17:00' ))
			{
				$keterangan = "H";
				$data1 = mysqli_query($con,"UPDATE log_anggota SET keterangan = '$keterangan' WHERE id_user = '$id_user'");
			}
		else
			{
				$keterangan ="A";
				$data2 = mysqli_query($con,"UPDATE log_anggota SET keterangan = '$keterangan' WHERE id_user = '$id_user'");

			}

	//$sql="SELECT * FROM log_anggota where id_user='$id_user'"; 
	$sql="select * from log_anggota NATURAL LEFT JOIN tbl_hari where id_user='$id_user'"; 
		$query = $con->query($sql);
		
	?>

<table border="1" width=100% class="table-data">
<tr>
<td class="head-data">No</td>
<td class="head-data">Hari</td>
<td class="head-data">Tanggal</td>
<td class="head-data">Jam</td>
<td class="head-data">Keterangan</td>
</tr>

<?php

		//$no=0;
		
		
		while ($hasil = $query->fetch_assoc()) 
		{	
		
		if($hasil['keterangan'] == 'H')
			$has = 'Hadir';
		else
			$has = 'Tidak Hadir';
		
		echo "<tr>
			  <td class='td-data'>$hasil[id_user]</td>
			  <td class='td-data'>$hasil[nama_hari]</td>
			  <td class='td-data'>$hasil[tgl]</td>
			  <td class='td-data'>$hasil[jam]</td>
			  <td class='td-data'>$has</td>
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