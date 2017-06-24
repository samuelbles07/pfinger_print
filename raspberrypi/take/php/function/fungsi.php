<!-- Javascript  untuk validasi form input hanya ANGKA //-->

<SCRIPT language=Javascript>
<!--
function isNumberKey(evt)
{
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 48 || charCode > 57))

return false;
return true;
}
//-->
</SCRIPT>

<!-- ======================================================= //-->

<!-- Javascript  untuk validasi form input hanya HURUF //-->

<SCRIPT language=Javascript>
<!--
function isCharacterKey(evt) {
   var charCode = (evt.which) ? evt.which : event.keyCode;
   if (charCode > 32 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
   return false;
   return true;
}//-->
</SCRIPT>

<!-- ======================================================= //-->

<!-- Javascript  untuk format form input harga  //-->

<Script language=Javascript>
	function formatCurrency(hrg) {
    hrg = hrg.toString().replace(/\$|\,/g,'');
    if(isNaN(hrg))
    hrg = "0";
    sign = (hrg == (hrg = Math.abs(hrg)));
    hrg = Math.floor(hrg*100+0.50000000001);
    cents = hrg%100;
    hrg = Math.floor(hrg/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((hrg.length-(1+i))/3); i++)
    hrg = hrg.substring(0,hrg.length-(4*i+3))+'.'+
    hrg.substring(hrg.length-(4*i+3));
    return (((sign)?'':'-') + 'Rp' + hrg + ',' + cents);
    }

</script>

<!-- ======================================================= //-->


<script>

// Fungsi untuk disable kategori ketika pilihan jenis selain buku teks

$(document).ready(function() {
$('#jenis').change(function() {
		if($(this).val()=="Buku Teks"){
			$("#kategori").prop("disabled", false).css("opacity", "1");
		}
		else{
			$("#kategori").prop("disabled", true).css("opacity", "0.5");
		}
	});
});
	
//===================================================

// Fungsi untuk membuat nilai input menjadi kapital
// Sedangkan untuk membuat tampilan menjadi kapital ketika diketik ada di CSS --> class = capital
	
$(document).ready(function()
 {
  $('.capital').blur
  (
   function()
   {
    this.value = this.value.toUpperCase();
   }
  )
 }
)

</script>




<?php
/*
$con = mysqli_connect("localhost", "root", "", "");


// Cek BIAYA PINJAM, DENDA/HARI dan HARI
$query = mysqli_query($con, "SELECT * FROM aturan");
$hasil=mysqli_fetch_array($query);

$id = $hasil[0]; // ambil ID agar bisa di EDIT
$biaya_pinjam = $hasil[1]; // ambil nilai BIAYA PINJAM
$denda_rp = $hasil[2]; // ambil nilai DENDA/HARI
$maks_hari = $hasil[3]; // ambil nilai HARI
 
function terlambat($tgl_dateline, $tgl_kembali) {

$tgl_dateline_pcs = explode ("-", $tgl_dateline);
$tgl_dateline_pcs = $tgl_dateline_pcs[2]."-".$tgl_dateline_pcs[1]."-".$tgl_dateline_pcs[0];

$tgl_kembali_pcs = explode ("-", $tgl_kembali);
$tgl_kembali_pcs = $tgl_kembali_pcs[2]."-".$tgl_kembali_pcs[1]."-".$tgl_kembali_pcs[0];

$selisih = strtotime ($tgl_kembali_pcs) - strtotime ($tgl_dateline_pcs);

$selisih = $selisih / 86400;

if ($selisih>=1) {
	$hasil_tgl = floor($selisih);
}
else {
	$hasil_tgl = 0;
}
return $hasil_tgl;
}
*/
?>

