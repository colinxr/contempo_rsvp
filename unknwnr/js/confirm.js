$(document).ready(function() {

	//approve click function

	$('.btn').on('click', function() {

		//Add the ID 'activeRow' to the TR which holds the button clicked

		$(this).parent().parent().attr('id', 'activeRow');

		var activeRow = $('#activeRow');
		 var alertBox = $('.alert');

		//Creates the data object that holds the Unknown RSVP data from activeRow's TDs

		var data = {
			firstName  : activeRow.find('td:eq(1)').text(),
			lastName   : activeRow.find('td:eq(2)').text(),
			email 	   : activeRow.find('td:eq(3)').text(),
			postal     : activeRow.find('td:eq(4)').text(),
			guestName  : activeRow.find('td:eq(5)').text(),
		  guestEmail : activeRow.find('td:eq(6)').text()
		}

	  //check if users clicked Approve or Deny

	    if ($(this).is('.btn.approve')){
				data.action = 'approve';
	    	alert('You want to approve ' + data.firstName + ' ' + data.lastName + '?' );
	    	alert('Person Approved');
		} else {
				data.action = 'delete';
				alert('You want to reject ' + data.firstName + ' ' + data.lastName + '?' );
				alert('Person Denied');
		}

		//converts var data to JSON object to submit through AJAX to PHP
		var json = JSON.stringify(data);

		//submits var data JSON object through ajax_post
		ajax_post(json);

		//removes the completed entry from table list
		activeRow.remove();

		// Alert Notice Handler

		alertBox.fadeIn('fast').fadeOut(2500);

		});


	// Function to sends JSON data object to process-data.php to submit to database.

	function ajax_post(json) {
		$.ajax({
	    	url: 'process-data.php',
	    	method: 'post',
	    	data: {'rsvp' : json},
	    	success: function(data){
	    		console.log(json);
	    	},
	    	error: function(xhr, textStatus, errorThrown) {
		    	console.log('ajax loading error...');
		     	return false;
	    	}
	    });
	}

}); // end of document ready function
