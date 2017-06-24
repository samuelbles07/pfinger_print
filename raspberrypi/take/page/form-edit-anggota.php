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
include "../php/config.php"; 
include "../php/function/fungsi.php"; 

$id_user = $_GET['id_user'];
//$nim = $_GET['nim'];

$query=mysqli_query($con, "SELECT * FROM data_anggota WHERE id_user='$id_user' ");
$hasil=mysqli_fetch_array($query);



//$query2=mysqli_query($con, "SELECT * FROM data_anggota WHERE nim='$nim' ");
//$hasil2=mysqli_fetch_array($query2);

$id_user  =$hasil['id_user'];
$nim = $hasil['nim'];
$nama = $hasil['nama'];
$jk = $hasil['jk'];
$alamat  = $hasil['alamat'];
$nope  = $hasil['nope'];
$data_sidik = $hasil['data_sidik'];

?>


<div class="all padding-side">
	<div class="title_panel">
		> Edit Anggota
	</div>
<div id="reg" class="full-form colorwh">
				<form class="cbp-mc-form" method="POST" id="input_buku" action="../php/action/act_edit_anggota.php">
					<div class="cbp-mc-column">
	  					<label for="id">ID</label>
	  					<input type="text" id="id_user" name="id_user" value="<?php echo $id_user;?>" onkeypress="return isNumberKey(event)" readonly="readonly">
	  					<label for="id">NIM/NIP</label>
	  					<input type="text" id="id" name="nim" value="<?php echo $nim;?>" onkeypress="return isNumberKey(event)" readonly="readonly">
	  					<label for="nama">Nama</label>
	  					<input type="text" id="nama" name='nama' value="<?php echo $nama ; ?>" onkeypress="return isCharacterKey(event)" required>
	  				</div>
	  				<div class="cbp-mc-column">
						<label for="jk">Jenis Kelamin</label>
	  					<select id="jk" name="jk">
	  						<option>Pilih Jenis Kelamin</option>
	  						<option>Laki-Laki</option>
	  						<option>Perempuan</option>
	  					</select>
						
						<label for="alamat">Alamat</label>
	  					<input type="text" id="alamat" name='alamat' value="<?php echo $alamat; ?>" required>
						<label for="no_hp">No HP</label>
	  					<input type="text" id="nope" name='nope' value="<?php echo $nope;?>" onkeypress="return isNumberKey(event)" required>
						
						
	  				</div>
	  				<div class="cbp-mc-column">
	  					<div class="dashboard">
							<figure class="effect-zoe">							
							<label for="id">Data Sidik Jari</label>
									
								<input type"text" id="data_sidik" name="data_sidik" value="<?php echo $data_sidik; ?>" onkeypress="return isCharacterKey(event)" readonly="readonly">
									<!--<textarea class="cont" type="text" id="data_sidik" name="data_sidik" readonly="readonly" value="<!--?php echo $data_sidik;?>"> </textarea>-->
									
							</figure>
						</div>
	  	
	  				</div>
	  				<div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" value="Edit Anggota" /></div>
				</form>
		
		
</div>


</div>

	
		<script src="../js/classie.js"></script>
		<script src="../js/main.js"></script>
</body>
</html>