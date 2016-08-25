(function($){

	$(window).load(function(){
		// ON WINDOW LOAD 
	});

	$(document).ready(function (){
	    if($('.plyr').length){
	        plyr.setup(); 
	    }
		$("img.unveil").unveil();
		$( '.swipebox' ).swipebox();

	});

})(jQuery); 