(function($){

	$(document).ready(function () {
		cruises.init();
	});
	
	var cruises = {

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
			
			$('.radio').bind('click.uniform',
				function (e) {
					if ($(this).find("span").hasClass('checked')) 
						$(this).find("input").attr('checked', true);
					else
						$(this).find("input").attr('checked', false);
				}
			);
			
			
			$('.book-cruise').on('click', function(event) {
			
				$('#wait_loading').show();
				
				var buttonId = $(this).attr('id');
				window.cabinTypeId = buttonId.replace('book-cruise-', '');

				$('#cruise_name').html(window.cruiseTitle);
				window.cruiseScheduleEntries = cruises.getCruiseScheduleEntries(window.cruiseId, window.cabinTypeId, window.currentDay, window.currentMonth, window.currentYear);
				cruises.bindCruiseDatePicker();						
				cruises.bindCruiseControls(window.cruiseId, window.cabinTypeId);
				
				cruises.showCruiseBookingForm();
				$('#wait_loading').hide();

				event.preventDefault();
			});
			
			$('#cruise-booking-form').validate({
				onkeyup: false,
				ignore: [],
				rules: {
					first_name: {
						required: true
					},
					last_name: "required",
					email: {
						required: true,
						email: true
					},
					confirm_email: {
						required: true,
						equalTo: "#email"
					},
					phone: "required",
					address: "required",
					town: "required",
					zip: "required",
					country: "required",
					start_date: "required"
				},
				invalidHandler: function(e, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						var message = errors == 1
							? window.formSingleError
							: window.formMultipleError.format(errors);
						$("div.error div p").html(message);
						$("div.error").show();
					} else {
						$("div.error").hide();
					}
				},
				messages: {
					first_name: window.bookingFormFirstNameError,
					last_name: window.bookingFormLastNameError,
					email: window.bookingFormEmailError,
					confirm_email: {
						required: window.bookingFormConfirmEmailError1,
						equalTo: window.bookingFormConfirmEmailError2
					},
					phone: window.bookingFormPhoneError,
					address: window.bookingFormAddressError,
					town: window.bookingFormCityError,
					zip: window.bookingFormZipError,
					country: window.bookingFormCountryError,
					start_date: window.bookingFormStartDateError
				},
				submitHandler: function() { cruises.processCruiseBooking(); }
			});
						
			$('#cancel-cruise-booking').on('click', function(event) {
				cruises.hideCruiseBookingForm();
				cruises.showCruiseInfo();
				event.preventDefault();
			});	
		}, 
		processCruiseBooking : function () {

			$('#wait_loading').show();
			
			var firstName = $('#first_name').val();
			var lastName = $('#last_name').val();
			var email = $('#email').val();
			var phone = $('#phone').val();
			var address = $('#address').val();
			var town = $('#town').val();
			var zip = $('#zip').val();
			var country = $('#country').val();
			var requirements = $('#requirements').val();
			var cruiseScheduleId = cruises.getCruiseScheduleId(window.cruiseId, window.cabinTypeId, $("#start_date").val());
			var cruiseStartDate = $("#start_date").val();
			var adults = $("#booking_form_adults").val();
			var children = $("#booking_form_children").val();
			
			var cValS = $('#c_val_s_cru').val();
			var cVal1 = $('#c_val_1_cru').val();
			var cVal2 = $('#c_val_2_cru').val();
			
			$("#confirm_first_name").html(firstName);
			$("#confirm_last_name").html(lastName);
			$("#confirm_email_address").html(email);
			$("#confirm_phone").html(phone);
			$("#confirm_street").html(address);
			$("#confirm_town").html(town);
			$("#confirm_zip").html(zip);
			$("#confirm_country").html(country);
			$("#confirm_requirements").html(requirements);
			$("#confirm_cruise_start_date").html(cruiseStartDate);
			$("#confirm_cruise_title").html(window.cruiseTitle);
			$("#confirm_cruise_adults").html(adults);
			$("#confirm_cruise_children").html(children);
			$("#confirm_cruise_total").html(window.currencySymbol + window.rateTableTotalPrice);
			
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: {
					'action':'book_cruise_ajax_request',
					'first_name' : firstName,
					'last_name' : lastName,
					'email' : email,
					'phone' : phone,
					'address' : address,
					'town' : town,
					'zip' : zip,
					'country' : country,
					'requirements' : requirements,
					'cruise_schedule_id' : cruiseScheduleId,
					'cruise_start_date' : cruiseStartDate,
					'adults' : adults,
					'children' : children,				
					'c_val_s' : cValS,
					'c_val_1' : cVal1,
					'c_val_2' : cVal2,
					'nonce' : BYTAjax.nonce
				},
				success:function(data) {
					// This outputs the result of the ajax request
					if (data == 'captcha_error') {
						$("div.error div p").html(window.InvalidCaptchaMessage);
						$("div.error").show();
					} else {
						var returned_id = data;
						$("div.error div p").html('');
						$("div.error").hide();
						
						var isReservationOnly = cruises.getCruiseIsReservationOnly(window.cruiseId);
						
						if (window.useWoocommerceForCheckout && window.wooCartPageUri.length > 0 && !isReservationOnly) {
							cruises.addTrProdToCart(returned_id);
						} else {
							cruises.hideCruiseBookingForm();
							cruises.showCruiseConfirmationForm();
							$('#wait_loading').hide();
						}
					}
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
		},
		getCruiseIsReservationOnly: function (cruiseId) {
			var isReservationOnly = 0;

			var dataObj = {
				'action':'cruise_is_reservation_only_request',
				'cruise_id' : cruiseId,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					isReservationOnly = data;
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});

			return isReservationOnly;
		},
		addTrProdToCart : function (p_id) {
			$.get(window.site_url + '/?post_type=product&add-to-cart=' + p_id, function() {
				cruises.trRedirectToCart();
			});
		},	
		trRedirectToCart : function () {
			top.location.href = window.wooCartPageUri;
		},
		getCruiseScheduleId : function (cruiseId, cabinTypeId, date) {

			var scheduleId = 0;

			var dataObj = {
				'action':'cruise_available_schedule_id_request',
				'cruiseId' : cruiseId,
				'cabinTypeId' : cabinTypeId,
				'dateValue' : date,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					scheduleId = data;
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});

			return scheduleId;
		},
		bindCruiseRatesTable : function () {
			
			$(".price_row").show();

			$('table.breakdown thead').html('');
			$('table.breakdown tfoot').html('');
			$('table.breakdown tbody').html('');

			var adults = $('#booking_form_adults').val();
			if (!adults)
				adults = 1;
				
			var children = $('#booking_form_children').val();
			if (!children)
				children = 0;
				
			var colCount = 2;
			var headerRow = '<tr class="rates_head_row">';
			
			headerRow += '<th>' + window.dateLabel + '</th>';		
			
			if (window.cruiseIsPricePerPerson) {
				headerRow += '<th>' + window.adultCountLabel + '</th>';
				headerRow += '<th>' + window.pricePerAdultLabel + '</th>';
				headerRow += '<th>' + window.childCountLabel + '</th>';
				headerRow += '<th>' + window.pricePerChildLabel + '</th>';
				colCount = 6;
			}
			
			headerRow += '<th>' + window.pricePerDayLabel + '</th>';		
			
			headerRow += '</tr>';

			$('table.breakdown thead').append(headerRow);	
			
			var footerRow = '<tr>';
			footerRow += '<th colspan="' + (colCount - 1) + '">' + window.priceTotalLabel + '</th>';
			footerRow += '<td class="total_price">0</td>';
			footerRow += '</tr>';

			$('table.breakdown tfoot').append(footerRow);
			
			if (window.startDate) {
			
				$('#datepicker_loading').show();
			
				var startTime = window.startDate.valueOf();
				
				window.rateTableTotalPrice = 0;
				
				cruises.buildCruiseRateRow(startTime, adults, children);
			}
			
		},
		buildCruiseRateRow : function (startTime, adults, children) {

			var price = 0;
			
			var d = new Date(startTime);
			var day = d.getDate();
			var month = d.getMonth() + 1;
			var year = d.getFullYear();
			var dateValue = day + "-" + month + "-" + year; 

			var dataObj = {
				'action':'cruise_get_price_request',
				'cruiseId' : window.cruiseId,
				'cabinTypeId' : window.cabinTypeId,
				'dateValue' : dateValue,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				dataType: 'json',
				success:function(prices) {
					var tableRow = '';
					// This outputs the result of the ajax request
					window.rateTableRowIndex++;
					var pricePerCruise = parseFloat(prices.price);
					var pricePerChild = 0;
					var totalPrice = 0;
					
					tableRow += '<tr>';
					tableRow += '<td>' + dateValue + '</td>';
					
					if (window.cruiseIsPricePerPerson) {
						pricePerChild = parseFloat(prices.child_price);
						tableRow += '<td>' + adults + '</td>';
						tableRow += '<td>' + window.currencySymbol + pricePerCruise + '</td>';
						tableRow += '<td>' + children + '</td>';
						tableRow += '<td>' + window.currencySymbol + pricePerChild + '</td>';
						totalPrice = (pricePerCruise * adults) + (pricePerChild * children);
					} else {
						totalPrice = pricePerCruise;
					}					
					
					$('.total_price').html(window.currencySymbol + ' ' + totalPrice);
					$("#confirm_total").html(window.currencySymbol + ' ' + totalPrice)
					
					tableRow += '<td>' + window.currencySymbol + totalPrice + '</td>';
					window.rateTableTotalPrice = totalPrice;
					
					tableRow += '</tr>';
					
					$('table.breakdown tbody').append(tableRow);
					
					$('#datepicker_loading').hide();
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});

		},
		showCruiseInfo : function () {
			$('.three-fourth .gallery').show();
			$('.three-fourth .inner-nav').show();
			$('.three-fourth .tab-content').show();
			$(".tab-content").hide();
			$(".tab-content:first").show();
			$(".inner-nav li:first").addClass("active");
		},
		showCruiseBookingForm : function () {
			$('#cruise-booking-form').show();
			$('.three-fourth .gallery').hide();
			$('.three-fourth .inner-nav').hide();
			$('.three-fourth .tab-content').hide();
		},
		hideCruiseBookingForm : function () {
			$('#cruise-booking-form').hide();
		},
		showCruiseConfirmationForm : function () {
			$('#cruise-confirmation-form').show();
		},
		bindCruiseControls : function (cruise_id, cabin_type_id) {

			if ($('#booking_form_adults option').size() == 0) {
			
				var	max_count = $('li#cabin_type_' + cabin_type_id + ' .cabin-information .max_count').val();
				var max_child_count = $('li#cabin_type_' + cabin_type_id + ' .cabin-information .max_child_count').val();
				
				for ( var i = 1; i <= max_count; i++ ) {
					$('<option ' + (i == 1 ? 'selected' : '') + '>').val(i).text(i).appendTo('#booking_form_adults');
				}
				$("#booking_form_adults").uniform();
				
				$('#booking_form_adults').on('change', function (e) {
					var optionSelected = $("option:selected", this);
					var valueSelected = this.value;
					cruises.bindCruiseRatesTable();				
				});

				if (max_child_count > 0) {
					$('<option selected>').val(0).text(0).appendTo('#booking_form_children');
					for ( var i = 1; i <= max_child_count; i++ ) {
						$('<option>').val(i).text(i).appendTo('#booking_form_children');
					}
					$("#booking_form_children").uniform();
					
					$('#booking_form_children').on('change', function (e) {
						var optionSelected = $("option:selected", this);
						var valueSelected = this.value;
						cruises.bindCruiseRatesTable();
					});
				} else {
					$('.booking_form_children').hide();
				}
			}
			
		},
		bindCruiseDatePicker : function  () {	

			if (typeof $('#cruise_schedule_datepicker') !== 'undefined') {

				$('#cruise_schedule_datepicker').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: 1,
					minDate: 0,
					beforeShowDay: function(d) {
						var date1 = null;
						if ($("#start_date").val())
							date1 = $.datepicker.parseDate(window.datepickerDateFormat, $("#start_date").val());

						if (window.cruiseScheduleEntries) {
							var dateTextForCompare1 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2) + " 00:00:00";
							var dateTextForCompare2 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
							if ($.inArray(dateTextForCompare1, window.cruiseScheduleEntries) == -1 && $.inArray(dateTextForCompare2, window.cruiseScheduleEntries) == -1)
								return [false, 'ui-datepicker-unselectable ui-state-disabled'];
						}
						
						return [true, date1 && (d.getTime() == date1.getTime()) ? "dp-highlight" : ""];
					},
					onSelect: function(dateText, inst) {

						$(".price_row").show();
					
						$("#start_date_span").html(dateText);
						$("#start_date").val(dateText);
						var startDateText = $("#start_date").val();
						var date1 = $.datepicker.parseDate(window.datepickerDateFormat, startDateText);
						window.startDate = date1;
						cruises.bindCruiseRatesTable();
					},
					onChangeMonthYear: function (year, month, inst) {
						window.currentMonth = month;
						window.currentYear = year;
						window.currentDay = 1;
						window.cruiseScheduleEntries = cruises.getCruiseScheduleEntries(window.cruiseId, window.cabinTypeId, window.currentDay, window.currentMonth, window.currentYear);
						cruises.bindCruiseDatePicker();
					}
				});
			}

		},
		getCruiseScheduleEntries : function (cruiseId, cabinTypeId, day, month, year) {
			var dateArray = new Array();

			var dataObj = {
				'action':'cruise_schedule_dates_request',
				'cruiseId' : cruiseId,
				'cabinTypeId' : cabinTypeId,
				'month' : month,
				'year' : year,
				'day' : day,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(json) {
					// This outputs the result of the ajax request
					var scheduleDates = JSON.parse(json);
					var i = 0;
					for (i = 0; i < scheduleDates.length; ++i) {
						if (scheduleDates[i].cruise_date != null) {
							dateArray.push(scheduleDates[i].cruise_date);
						}
					}
				},
				error: function(errorThrown){

				}
			});

			return dateArray;
		}
	}
	

})(jQuery);
	