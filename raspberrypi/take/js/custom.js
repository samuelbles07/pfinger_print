/*******PRELOADER**/
jQuery(window).load(function() {
		jQuery('#preload-home').delay(350).fadeOut('slow');
		jQuery('body').delay(350).css({'overflow':'visible'});
	});

	/*******SCROLL**/
$(document).ready(function() {
    $(".scroll").click(function() {
        var ScrollOffset = $(this).attr('data-soffset');
        $("html, body").animate({
            scrollTop: $($(this).attr("href")).offset().top-ScrollOffset + "px"
        }, {
            duration: 600,
            easing: "swing"
        });
        return false;
    });
	
	// Portfolio
	$(function(){
		$('ul.portfolio').mixitup({
			targetSelector: '.item',
			filterSelector: '.filter',
			easing: 'smooth',
			effects: ['fade'],
			layoutMode: 'grid',
			targetDisplayGrid: 'inline-block'
		});
	});
	
});
/*******UPLOAD PHOTO PREVIEW**/
$(document).ready(function() {
	$(function() {
		$("#fupload").on("change", function(){
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file

				reader.onloadend = function(){ // set image data as background of div
					$(".photo-view").css("background-image", "url("+this.result+")");
				}
			}
    });
});
});