<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/font-open-sans.css"></link>
<link rel="stylesheet" type="text/css" href="../css/style.css"></link>
<!--<link rel="stylesheet" type="text/css" href="../css/style_theme.css"></link>-->
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css"></link>
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<!--<script type="text/javascript" src="../js/plugin.js"></script>-->
<script type="text/javascript" src="../js/jquery.mixitup.min.js"></script>
<script type="text/javascript" src="../js/notify.min.js"></script>
<script type="text/javascript" src="../js/custom.js"></script>
<script type="text/javascript" src="../js/jquery.dcjqaccordion.2.7.js"></script>

</head>
<body>

<?php
include "include/incluce_sidebar.html";
include "../php/config.php";
//include "../php/function/fungsi.php";
/*
$q1=mysqli_query($con, "select count(kode_buku) as jlh from data_buku");
$f1=mysqli_fetch_array($q1); $total_buku=$f1['jlh'];

$q2=mysqli_query($con, "select count(id_trans) as jlh_pinjam from trans_pinjam where status='pinjam'");
$f2=mysqli_fetch_array($q2); $total_pinjam=$f2['jlh_pinjam'];

$q3=mysqli_query($con, "select count(kode_buku) as jlh_hilangRusak from data_buku where status='rusak' OR status='hilang' ");
$f3=mysqli_fetch_array($q3); $total_hilangRusak=$f3['jlh_hilangRusak'];

$q4=mysqli_query($con, "select count(id_trans) as total_transaksi from trans_pinjam");
$f4=mysqli_fetch_array($q4); $total_transaksi=$f4['total_transaksi'];

?>

<div class="all padding-side">
<div class="dashboard">
				
				<figure class="effect-zoe">
					<div class="head">
						Total Semua Buku
					</div>
					<div class="cont"><h3><?php echo $total_buku; ?></h3>
					</div>
				</figure>
				
				<figure class="effect-zoe">
					<div class="head">
						Jumlah Buku Dipinjam
					</div>
					<div class="cont"><h3><?php echo $total_pinjam; ?></h3>
					</div>
				</figure>
				
				<figure class="effect-zoe">
					<div class="head">
						Jumlah Buku Yang Hilang dan Rusak
					</div>
					<div class="cont"><h3><?php echo $total_hilangRusak; ?></h3>
					</div>
				</figure>
				
				<figure class="effect-zoe">
					<div class="head">
						Total Transaksi
					</div>
					<div class="cont"><h3><?php echo $total_transaksi; ?></h3>
					</div>
				</figure>
				
				<figure class="effect-zoe">
					<div class="head">
						Biaya Pinjam
					</div>
					<div class="cont"><h4>Rp. <?php echo number_format($biaya_pinjam, 2, ',', '.'); ?> / Buku</h4>
					</div>
				</figure>
				
				<figure class="effect-zoe">
					<div class="head">
						Biaya Denda
					</div>
					<div class="cont"><h4>Rp. <?php echo number_format($denda_rp, 2, ',', '.'); ?> / Hari</h4>
					</div>
				</figure>
				
				<figure class="effect-zoe">
					<div class="head">
						Denda Buku Hilang / Rusak
					</div>
					<div class="cont"><h4>- Ganti Sesuai Harga - <br><br>- Ganti Setengah Harga -</h4>
					</div>
				</figure>

				<figure class="effect-zoe">
					<div class="head">
						Maks. Buku / Maks. Hari
					</div>
					<div class="cont"><h4>1 Buku / <?php echo $maks_hari; ?> Hari</h4>
					</div>
				</figure>
								
				
				
		</div>


</div>


<!--
<div id="preload-home">
	<div class="preloader">
		<img src="images/PerpusTI_1.png"></img>
	</div>
</div>
-->
<script>
/*
$(document).ready(function() {
    $('#input_buku').submit(function(e) {
        $.ajax({
            type        : 'POST',
            url         : '../php/admin/adm_input_buku.php',
            data        : $(this).serialize(),
            dataType    : 'html',         
			success : function(pesan){
				if(pesan=='ok'){
					 $.notify("Data Buku Berhasil Ditambahkan", "success");
					 $('.cbp-mc-form')[0].reset();
				}
				else{
					$.notify(pesan);
				}
			},
			beforeSend:function(){				
				$("#loader").show();
				$(".salah").hide();
		   }
		});
	return false;
	});
	
	$("#kategori").attr("disabled");

});
*/
/*
$(".pos-demo").notify(
  "I'm left of the box", 
  { position:"left" }
);
*/
/*</script>
	
		<script src="../js/classie.js"></script>
		<script src="../js/main.js"></script>*/
?>
		</body>
</html>