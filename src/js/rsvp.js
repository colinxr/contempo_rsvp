///////
// RSVP Form Validation
///////
(function($) {
$(document).ready(function() {
	var column = $('.info');
	// var columnHeight = column[0].scrollHeight
	var email = getQueryVariable('email');

	// resizeColumn();
	//
	// var resizeTimeout;
	// $(window).resize(function(e) {
	// 	clearTimeout(resizeTimeout);
	//
	// 	resizeTimeout = setTimeout(function() {
	// 		console.log('resize');
	// 		resizeColumn();
	// 	}, 250);
  // });

	if (email) {
		$('#email').val(email);
	}

	$('#plus-one').click(function () {
		$('.guests').fadeToggle('fast','swing');
		$('input[name="guest-name"]').attr("required", true);
		$('input[name="guest-email"]').attr("required", true);
	});

	$('form').submit(function(e){
		submitForm();
  });

function resizeColumn() {
	var columnHeight = column[0].scrollHeight;
	var vpHeight = $(window).height();

	if (columnHeight > vpHeight && !column.hasClass('smaller')) {
		column.addClass('smaller');
	} else if (columnHeight < vpHeight && column.hasClass('smaller')){
		column.removeClass('smaller');
	}
}

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split('&');
  for (var i=0;i<vars.length;i++) {
     var pair = vars[i].split('=');
     if(pair[0] == variable){
     	return pair[1];
     }
  }
  return(false);
}

function isValid() {
	var email = $('#email').val();
	var name = $('#name').val();
	var postal = $('#postal').val();
	if($('#plus-one').is(':checked')) {
		var guestName = $('#guest-name').val();
		var guestEmail = $('#guest-email').val();
	}

	// DO VALIDATION
	return true;
}

function submitForm() {
	if (isValid()) {
		$('form').submit();
	}
}
});
})(jQuery);
