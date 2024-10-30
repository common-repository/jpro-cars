// Add & Edit - Cars
jQuery(document).ready(function(){
	// Mileage, numbers only
	jQuery("[name='car-mileage']").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			jQuery(".errmileage").css('color', 'red').html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
	// Engine, numbers only
	jQuery("[name='car-engine']").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			jQuery(".errengine").css('color', 'red').html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
	// Horsepower, numbers only
	jQuery("[name='car-horsepower']").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			jQuery(".errhorsepower").css('color', 'red').html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
	// Seats, numbers only
	jQuery("[name='car-seats']").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			jQuery(".errseats").css('color', 'red').html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
	// Price, numbers only
	jQuery("[name='car-price']").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			jQuery(".errprice").css('color', 'red').html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
	// Membership Price, numbers only
	jQuery("[name='membership_price']").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			jQuery(".errprice").css('color', 'red').html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
});