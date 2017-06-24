<!DOCTYPE HTML>
<html>
<head>
	<link rel='stylesheet' type='text/css' href='css/style-user.css'></link>
	<link rel='stylesheet' type='text/css' href='css/jquery.remodal.css'></link>
	<link rel='stylesheet' type='text/css' href='fonts/font-awesome-4.2.0/css/font-awesome.min.css' />
	<script type='text/javascript' src='js/jquery-1.8.0.min.js'></script>
</head>
<body>
<div class='menuBar'>
	<div class='header'>
		<div id="logo"></div>
	</div>
</div>
<div class='menuBar'>
	<div class='header'>
		<form type='POST'>
			<input type='text' placeholder='Cari Buku...' class='bigsearch'></input>
			<button type='submit' class='bigbutton' id='search'><i class="fa fa-compass"></i> CARI</button>
		</form>
		<div class='about'>
			&copy Copyright 2015 Teknologi Informasi USU | <a href='aboutus.html'><i class="fa fa-user"></i> <b>About Developer</a>
		</div>
	</div>
</div>
<div class='wrap'>
<div class="container">

  <div class="gap"></div>
  <div class="gap"></div>
</div>
</div>
<div class="remodal" data-remodal-id="modal"></div>
<div id="load"></div>
		<script type="text/javascript">
		$(document).ready(function() { 
				$('a#detail').click(function(e){
				var detailbuku=$(this).attr('class');				
					$.post('php/user/show_book_detail.php', {'judul':detailbuku}, function(datas) {
					  $(".remodal").html(datas);
					});			
			});
			});
		</script>
		<script type="text/javascript">
		$(document).ready(function() { 
				$(".bigbutton").click(function() { 
				var searchid = $('.bigsearch').val();
					if(searchid==""){
						$(".container").html('');
					}
				 else if(searchid.length >= 3){
					$("#load").html('<img src="images/library/tail-spin-y.svg" width="100px" height="100px" />');
					$.post('php/user/show_book_search.php', {'cari':searchid}, function(data) {
					  $(".container").html(data);
					  $("#load").html('');
					});
			}return false;
			});
			});
		</script>
		<script src="js/jquery.remodal.min.js"></script>
		<script>$(document).on('open', '.remodal', function () {console.log('open');});$(document).on('opened', '.remodal', function () {console.log('opened');});$(document).on('close', '.remodal', function () {console.log('close');});$(document).on('closed', '.remodal', function () {console.log('closed');});$(document).on('confirm', '.remodal', function () {console.log('confirm');});$(document).on('cancel', '.remodal', function () {console.log('cancel');});
			var inst = $('[data-remodal-id=modal2]').remodal();//  inst.open();
		</script>
</body>
</html>