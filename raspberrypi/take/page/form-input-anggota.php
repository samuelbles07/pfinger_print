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
include "../php/function/fungsi.php"; 

include "../php/config.php";

//$query=mysqli_query($con, "SELECT data_sidik as data FROM data_anggota where nim='NULL'");

$q1=mysqli_query($con, "SELECT data_sidik  FROM data_anggota where nim='NULL' OR nama='NULL'");
$f1=mysqli_fetch_array($q1); $data=$f1['data_sidik'];

//fungsi cetak ID anggota

$q2=mysqli_query($con, "SELECT id_user as no_urut FROM data_anggota where nim='NULL' OR nama='NULL' ");
$f2=mysqli_fetch_array($q2); $id_ang=$f2['no_urut'];



?>

<div class="all padding-side">
	<div class="title_panel">
		> Tambah Anggota
	</div>
<div id="reg" class="full-form colorwh">
				<form class="cbp-mc-form" method="POST" id="input_anggota" action="../php/action/act_input_anggota.php">
					<div class="cbp-mc-column">
	  					<label for="id">ID Anggota</label>
	  					<input type="text" id="id_user" name="id_user" value="<?php echo $id_ang ?>" readonly="readonly">
	  					<label for="id">NIM/NIP</label>
	  					<input type="text" id="nim" name="nim" placeholder="Masukkan NIM" onkeypress="return isNumberKey(event)" maxlength="25" required>						
						<label for="nama">Nama</label>
	  					<input type="text" id="nama" name="nama" placeholder="Masukkan Nama" onkeypress="return isCharacterKey(event)" required>
	  				</div>
					
	  				<div class="cbp-mc-column">
						
						<label for="jk">Jenis Kelamin</label>
	  					<select id="jk" name="jk">
	  						<option>Pilih Jenis Kelamin</option>
	  						<option>Laki-Laki</option>
	  						<option>Perempuan</option>
	  					</select>
						<label for="alamat">Alamat</label>
	  					<input type="text" id="alamat" name="alamat" placeholder="Masukkan Alamat" required>
						<label for="no_hp">No HP</label>
	  					<input type="text" id="nope" name="nope" placeholder="Masukkan No Handphone" onkeypress="return isNumberKey(event)" required>
	  	
	  				</div>
					
					
	  				<div class="cbp-mc-column">
						<div class="dashboard">
							<figure class="effect-zoe">							
							<label for="id">Data Sidik Jari</label>
									<textarea class="cont" type="text" id="data" name="data" readonly="readonly" > <?php echo $data ?></textarea>
									
							</figure>
						</div>
	  				</div>
					
				
					
				
			
	  				<div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" value="Tambah Anggota" /></div>
				</form>
				
				
		
		
</div>


</div>

		<script src="../js/classie.js"></script>
		<script src="../js/main.js"></script>
</body>
</html>