(function($){

	$(document).ready(function () {
		byt_admin.init();
	});
	
	var byt_admin = {

		init: function () {		
			
			byt_admin.showHideRoomTypes($('#accommodation_is_self_catered').is(':checked'));
			$("#accommodation_is_self_catered").change(function() {
				byt_admin.showHideRoomTypes($(this).is(':checked'));
			});
			
			byt_admin.showHideCountChildrenStayFree($('#accommodation_is_price_per_person').is(':checked'));
			$("#accommodation_is_price_per_person").change(function() {
				byt_admin.showHideCountChildrenStayFree($(this).is(':checked'));
			});
			
			$('#accommodations_select').on('change', function() {
				var accommodationId = $(this).val()
				
				var isSelfCatered = byt_admin.adminAccommodationIsSelfCatered(accommodationId);
				
				if (isSelfCatered) {
					$('#room_types_row').hide();
					$('#room_count_row').hide();
				} else {
				
					var roomTypes = byt_admin.listAccommodationRoomTypes(accommodationId);
					
					$('select#room_types_select').find('option:gt(0)').remove();
					
					var room_type_options = "";

					$.each(roomTypes,function(index){
						room_type_options += '<option value="'+ roomTypes[index].id +'">' + roomTypes[index].name + '</option>'; 
					});

					$('select#room_types_select').append(room_type_options);
					
					$('#room_types_row').show();
					$('#room_count_row').show();
				}
				
				var isPricePerPerson = byt_admin.adminAccommodationIsPricePerPerson(accommodationId);
				if (isPricePerPerson) {
					$('.per_person').show();
				} else {
					$('.per_person').hide();
				}
			});
			
			$('#tours_select').on('change', function() {

				var tourId = $(this).val()
				
				var isPricePerGroup = byt_admin.adminTourIsPricePerGroup(tourId);
				var tourTypeIsRepeated = byt_admin.adminTourTypeIsRepeated(tourId);
				
				if (isPricePerGroup) {
					$('.per_person').hide();
					$('.per_group').show();
					$('#price_child').val(0);
				} else {
					$('.per_person').show();
					$('.per_group').hide();
				}
				
				if (tourTypeIsRepeated > 0) {
					$('.is_repeated').show();		
				} else {
					$('.is_repeated').hide();		
				}
				
			});
			
			$('#cruises_select').on('change', function() {

				var cruiseId = $(this).val()
				
				var isPricePerPerson = byt_admin.adminCruiseIsPricePerPerson(cruiseId);
				var cruiseTypeIsRepeated = byt_admin.adminCruiseTypeIsRepeated(cruiseId);
				
				var cabinTypes = byt_admin.listCruiseCabinTypes(cruiseId);
				
				$('select#cruise_types_select').find('option:gt(0)').remove();
				
				var cabin_type_options = "";

				$.each(cabinTypes,function(index){
					cabin_type_options += '<option value="'+ cabinTypes[index].id +'">' + cabinTypes[index].name + '</option>'; 
				});

				$('select#cabin_types_select').append(cabin_type_options);
				
				$('#cabin_types_row').show();
				$('#cabin_count_row').show();
				
				if (isPricePerPerson) {
					$('.per_person').show();
				} else {
					$('.per_person').hide();
					$('#price_child').val(0);
				}
				
				if (cruiseTypeIsRepeated > 0) {
					$('.is_repeated').show();		
				} else {
					$('.is_repeated').hide();		
				}		
			});
		
		},
		showHideRoomTypes : function (checked) {
			if (checked) {
				$('[name="room_types[]"]').closest('tr').hide();
				$('[name="accommodation_max_count"]').closest('tr').show();
				$('[name="accommodation_max_child_count"]').closest('tr').show();
			} else {
				$('[name="room_types[]"]').closest('tr').show();
				$('[name="accommodation_max_count"]').closest('tr').hide();
				$('[name="accommodation_max_child_count"]').closest('tr').hide();
			}
		},
		showHideCountChildrenStayFree : function (checked) {
			if (checked) {
			accommodation_count_children_stay_free
				$('[name="accommodation_count_children_stay_free"]').closest('tr').show();
			} else {
				$('[name="accommodation_count_children_stay_free"]').closest('tr').hide();
			}
		},
		listAccommodationRoomTypes : function (accommodationId) {
			
			var retVal = null;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'admin_accommodation_list_room_types_ajax_request',
					'accommodationId' : accommodationId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(json) {
					// This outputs the result of the ajax request
					retVal = JSON.parse(json);
				},
				error: function(errorThrown){

				}
			}); 
			
			return retVal;
			
		},
		adminAccommodationIsSelfCatered : function (accommodationId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'admin_accommodation_is_self_catered_ajax_request',
					'accommodationId' : accommodationId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		adminAccommodationIsPricePerPerson : function (accommodationId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'admin_accommodation_is_price_per_person_ajax_request',
					'accommodationId' : accommodationId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		adminTourIsPricePerGroup : function (tourId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'tour_is_price_per_group_ajax_request',
					'tourId' : tourId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		adminTourIsPricePerGroup : function (tourId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'tour_is_price_per_group_ajax_request',
					'tourId' : tourId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		adminTourTypeIsRepeated : function (tourId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'tour_type_is_repeated_ajax_request',
					'tourId' : tourId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		adminCruiseTypeIsRepeated : function (cruiseId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'cruise_type_is_repeated_ajax_request',
					'cruiseId' : cruiseId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		adminCruiseIsPricePerPerson : function (cruiseId) {

			var retVal = 0;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'cruise_is_price_per_person_ajax_request',
					'cruiseId' : cruiseId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){

				}
			}); 
			
			return parseInt(retVal);
		},
		listCruiseCabinTypes : function (cruiseId) {
			
			var retVal = null;
			var _wpnonce = $('#_wpnonce').val();
				
			var dataObj = {
					'action':'cruise_list_cabin_types_ajax_request',
					'cruiseId' : cruiseId,
					'nonce' : _wpnonce }				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(json) {
					// This outputs the result of the ajax request
					retVal = JSON.parse(json);
				},
				error: function(errorThrown){
					
				}
			}); 
			
			return retVal;	
		}
	}	

})(jQuery);

function accommodationFilterRedirect (accommodationId, roomTypeId, year, month) {
	document.location = 'edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php&accommodation_id=' + accommodationId + '&room_type_id=' + roomTypeId + '&year=' + year + '&month=' + month;
}

function tourFilterRedirect (id, year, month) {
	document.location = 'edit.php?post_type=tour&page=theme_tour_schedule_admin.php&tour_id=' + id + '&year=' + year + '&month=' + month;
}

function cruiseFilterRedirect (cruiseId, cabinTypeId, year, month) {
	document.location = 'edit.php?post_type=cruise&page=theme_cruise_schedule_admin.php&cruise_id=' + cruiseId + '&cabin_type_id=' + cabinTypeId + '&year=' + year + '&month=' + month;
}

function tourBookingTourFilterRedirect (bookingId, tourId) {
	document.location = 'edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&tour_id=' + tourId;
}

function cruiseBookingCruiseFilterRedirect (bookingId, cruiseId) {
	document.location = 'edit.php?post_type=cruise&page=theme_cruise_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&cruise_id=' + cruiseId;
}

function carRentalBookingCarRentalFilterRedirect (bookingId, carRentalId) {
	document.location = 'edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php&sub=manage&edit=' + bookingId + '&car_rental_id=' + carRentalId;
}

function tourBookingTourScheduleFilterRedirect (bookingId, tourId, tourScheduleId) {
	document.location = 'edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&tour_id=' + tourId + '&tour_schedule_id=' + tourScheduleId;
}

function cruiseBookingCruiseScheduleFilterRedirect (bookingId, cruiseId, cruiseScheduleId) {
	document.location = 'edit.php?post_type=cruise&page=theme_cruise_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&cruise_id=' + cruiseId + '&cruise_schedule_id=' + cruiseScheduleId;
}

function accommodationBookingAccommodationFilterRedirect (bookingId, accommodationId) {
	document.location = 'edit.php?post_type=accommodation&page=theme_accommodation_booking_admin.php&sub=manage&edit=' + bookingId + '&accommodation_id=' + accommodationId;
}

function isTourTypeRepeatedChanged (display){
	var sel = document.getElementById("term_meta[tour_type_is_repeated]");
	var val = parseInt(sel.options[sel.selectedIndex].value);
	if (val == 3) 
		document.getElementById("tr_tour_type_day_of_week").style.display = display;
	else
		document.getElementById("tr_tour_type_day_of_week").style.display = 'none';
}

function isCruiseTypeRepeatedChanged (display){
	var sel = document.getElementById("term_meta[cruise_type_is_repeated]");
	var val = parseInt(sel.options[sel.selectedIndex].value);
	if (val == 3) 
		document.getElementById("tr_cruise_type_day_of_week").style.display = display;
	else
		document.getElementById("tr_cruise_type_day_of_week").style.display = 'none';
}


function confirmDelete (form_id, message) {
	var answer = confirm(message);
	if (answer){
		document.getElementById(form_id.replace('#', '')).submit();
		return true;
	}
	return false;  
}