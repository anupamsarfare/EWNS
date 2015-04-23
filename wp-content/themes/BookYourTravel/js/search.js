(function($){

	$(document).ready(function () {
		search.init();
	});

	var search = {

		init: function () {
		
			var spinner = $('.spinner input').spinner({ min: 0 });
			
			$('#search_date_from').datepicker({
				showOn: 'button',
				buttonImage: window.themePath + '/images/ico/calendar.png',
				buttonImageOnly: true,
				minDate: 0,
				onClose: function (selectedDate) {
					var d = new Date(selectedDate);
					d = new Date(d.getFullYear(), d.getMonth(), d.getDate()+1);
					$("#search_date_to").datepicker("option", "minDate", d);
				}
			});
			
			$('#search_date_to').datepicker({
				showOn: 'button',
				buttonImage: window.themePath + '/images/ico/calendar.png',
				buttonImageOnly: true,
				minDate: 0,
				onClose: function (selectedDate) {
					var d = new Date(selectedDate);
					d = new Date(d.getFullYear(), d.getMonth(), d.getDate()-1);
					$("#search_date_from").datepicker("option", "maxDate", d);
				}
			});
			
			$( "#slider" ).slider({
				range: "min",
				value:0,
				min: 0,
				max: 10,
				step: 1
			});
			
			$("input[type=radio], select, input[type=checkbox]").uniform();
	
			$('.main-search input[name=what]').on('click', function() {
				window.activeSearchableNumber = parseInt($(this).val());
			
				search.setupWhereControls();
				search.setupWhenControls();
				search.setupWhoControls();
				
				$('.main-search input[name=what]').prop('checked', false);
				$(this).prop('checked', 'checked');
				$.uniform.update(); 
			});
			
			search.setupWhereControls();
			search.setupWhenControls();
			search.setupWhoControls();
					
		}, 
		setupWhereControls : function () {
			$("#search_term").parent().parent().show();
			$("#search_term").prop('disabled', '');
			
			switch(window.activeSearchableNumber) {
				case 1:
					$("label[for='search_term']").html(window.searchAccommodationLocationLabel);
					$("#search_term").prop('placeholder', window.searchAccommodationLocationPlaceholder);
					search.adjustCounts();
					search.configureSuggest('hotel_term_search_request');
					break;
				case 2:
					$("label[for='search_term']").html(window.searchAccommodationLocationLabel);
					$("#search_term").prop('placeholder', window.searchAccommodationLocationPlaceholder);	
					search.adjustCounts();	
					search.configureSuggest('self_catered_term_search_request');					
					break;
				case 3:
					$("label[for='search_term']").html(window.searchCarRentalLocationLabel);
					$("#search_term").prop('placeholder', window.searchCarRentalLocationPlaceholder);
					search.adjustCounts();
					search.configureSuggest('car_rental_term_search_request');
					break;
				case 4:
					$("label[for='search_term']").html(window.searchTourLocationLabel);
					$("#search_term").prop('placeholder', window.searchTourLocationPlaceholder);
					search.adjustCounts();
					search.configureSuggest('tour_term_search_request');
					break;
				case 5:
					$("#search_term").parent().parent().hide();
					$("#search_term").prop('disabled', true);
					search.adjustCounts();
					break;
				default:
					$("label[for='search_term']").html(window.searchAccommodationLocationLabel);
					$("#search_term").prop('placeholder', window.searchAccommodationLocationPlaceholder);				
					break;
			}
		},
		configureSuggest: function (ajax_method) {
			$('ul.suggest-results').remove();
			$('#search_term').suggest(BYTAjax.ajaxurl + '?action=' + ajax_method + '&nonce=' + BYTAjax.nonce, {
				multiple     	: false,
				delimiter		: ';',
				multipleSep		: '',
				resultsClass 	: 'suggest-results',
				selectClass  	: 'suggest-over',
				matchClass   	: 'suggest-match'
			});
		},
		adjustCounts : function() {
			if (window.activeSearchableNumber == 5) {
				var when_count = parseInt($('.when_count').html());
				$('.when_count').html('0' + (when_count - 1));
				$('.who_count').html('0' + (when_count));
			} else {
				var when_count = parseInt($('.when_count').html());
				$('.what_count').html('0' + window.whatCount);
				$('.where_count').html('0' + window.whereCount);
				$('.when_count').html('0' + window.whenCount);
				$('.who_count').html('0' + window.whoCount);
			}
		},
		setupWhenControls : function () {
			switch(window.activeSearchableNumber) {
				case 1:
					$("label[for='search_date_from']").html(window.searchAccommodationDateFromLabel);				
					$("label[for='search_date_to']").html(window.searchAccommodationDateToLabel);
					search.toggleDateToVisibility(true);
					break;
				case 2:
					$("label[for='search_date_from']").html(window.searchAccommodationDateFromLabel);				
					$("label[for='search_date_to']").html(window.searchAccommodationDateToLabel);
					search.toggleDateToVisibility(true);
					break;
				case 3:
					$("label[for='search_date_from']").html(window.searchCarRentalDateFromLabel);				
					$("label[for='search_date_to']").html(window.searchCarRentalDateToLabel);
					search.toggleDateToVisibility(true);
					break;
				case 4:
					$("label[for='search_date_from']").html(window.searchTourDateFromLabel);				
					search.toggleDateToVisibility(false);
					break;
				case 5:
					$("label[for='search_date_from']").html(window.searchCruiseDateFromLabel);			
					search.toggleDateToVisibility(false);
					break;
				default:
					$("label[for='search_date_from']").html(window.searchAccommodationDateFromLabel);				
					$("label[for='search_date_to']").html(window.searchAccommodationDateToLabel);
					search.toggleDateToVisibility(true);
					break;
			}		
		},
		setupWhoControls : function() {
			switch(window.activeSearchableNumber) {
				case 1:
					search.toggleRoomsVisibility(true);
					search.toggleGuestsVisibility(false);
					search.toggleCabinsVisibility(false);
					search.toggleDriverAgeVisibility(false);
					search.toggleCarTypeVisibility(false);
					break;
				case 2:
					search.toggleRoomsVisibility(false);
					search.toggleGuestsVisibility(true);
					search.toggleCabinsVisibility(false);
					search.toggleDriverAgeVisibility(false);
					search.toggleCarTypeVisibility(false);
					break;
				case 3:
					search.toggleRoomsVisibility(false);
					search.toggleGuestsVisibility(false);
					search.toggleCabinsVisibility(false);
					search.toggleDriverAgeVisibility(true);
					search.toggleCarTypeVisibility(true);
					break;
				case 4:
					search.toggleRoomsVisibility(false);
					search.toggleGuestsVisibility(true);
					search.toggleCabinsVisibility(false);
					search.toggleDriverAgeVisibility(false);
					search.toggleCarTypeVisibility(false);
					break;
				case 5:
					search.toggleRoomsVisibility(false);
					search.toggleGuestsVisibility(false);
					search.toggleCabinsVisibility(true);
					search.toggleDriverAgeVisibility(false);
					search.toggleCarTypeVisibility(false);
					break;
				default:
					search.toggleRoomsVisibility(true);
					search.toggleGuestsVisibility(false);
					search.toggleCabinsVisibility(false);
					search.toggleDriverAgeVisibility(false);
					search.toggleCarTypeVisibility(false);
					break;
			}	
		},
		toggleDateToVisibility : function(show) {
			if (show) {
				$("#search_date_to").parent().parent().show();
				$("#search_date_to").prop('disabled', '');
			} else {
				$("#search_date_to").parent().parent().hide();
				$("#search_date_to").prop('disabled', true);
			}
		},
		toggleRoomsVisibility : function(show) {
			if (show) {
				$("#search_rooms").parent().parent().show();
				$("#search_rooms").prop('disabled', '');
			} else {
				$("#search_rooms").parent().parent().hide();
				$("#search_rooms").prop('disabled', true);
			}
		},
		toggleGuestsVisibility : function(show) {
			if (show) {
				$("#search_guests").parent().parent().show();
				$("#search_guests").prop('disabled', '');
			} else {
				$("#search_guests").parent().parent().hide();
				$("#search_guests").prop('disabled', true);
			}
		},
		toggleCabinsVisibility : function(show) {
			if (show) {
				$("#search_cabins").parent().parent().show();
				$("#search_cabins").prop('disabled', '');
			} else {
				$("#search_cabins").parent().parent().hide();
				$("#search_cabins").prop('disabled', true);
			}
		},
		toggleDriverAgeVisibility : function(show) {
			if (show) {
				$("#search_age").parent().parent().show();
				$("#search_age").prop('disabled', '');
			} else {
				$("#search_age").parent().parent().hide();
				$("#search_age").prop('disabled', true);
			}
		},
		toggleCarTypeVisibility : function(show) {
			if (show) {
				$("#search_car_type").parent().parent().show();
				$("#search_car_type").prop('disabled', '');
			} else {
				$("#search_car_type").parent().parent().hide();
				$("#search_car_type").prop('disabled', true);
			}
		}
	}

})(jQuery);