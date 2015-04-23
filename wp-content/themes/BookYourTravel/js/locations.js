(function($){

	$(document).ready(function () {
		locations.init();
	});
	
	var locations = {

		init: function () {

			$("#gallery").lightSlider({
				slideWidth:850,
				gallery:true,
				thumbWidth:100,
				thumbMargin:6.25,
				minSlide:1,
				maxSlide:1,
				auto:true,
				mode:'slide',
				proportion:'100%',
				onSliderLoad: function() {
					$('#gallery').removeClass('cS-hidden');
				}  
			});
		}
	}
	
})(jQuery);