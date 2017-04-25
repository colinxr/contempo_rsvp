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

		alertBox.fadeIn('fast').fadeOut(3000);

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

/*! jQuery JSON plugin v2.5.1 | github.com/Krinkle/jquery-json */
!function($){"use strict";var escape=/["\\\x00-\x1f\x7f-\x9f]/g,meta={"\b":"\\b","	":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},hasOwn=Object.prototype.hasOwnProperty;$.toJSON="object"==typeof JSON&&JSON.stringify?JSON.stringify:function(a){if(null===a)return"null";var b,c,d,e,f=$.type(a);if("undefined"===f)return void 0;if("number"===f||"boolean"===f)return String(a);if("string"===f)return $.quoteString(a);if("function"==typeof a.toJSON)return $.toJSON(a.toJSON());if("date"===f){var g=a.getUTCMonth()+1,h=a.getUTCDate(),i=a.getUTCFullYear(),j=a.getUTCHours(),k=a.getUTCMinutes(),l=a.getUTCSeconds(),m=a.getUTCMilliseconds();return 10>g&&(g="0"+g),10>h&&(h="0"+h),10>j&&(j="0"+j),10>k&&(k="0"+k),10>l&&(l="0"+l),100>m&&(m="0"+m),10>m&&(m="0"+m),'"'+i+"-"+g+"-"+h+"T"+j+":"+k+":"+l+"."+m+'Z"'}if(b=[],$.isArray(a)){for(c=0;c<a.length;c++)b.push($.toJSON(a[c])||"null");return"["+b.join(",")+"]"}if("object"==typeof a){for(c in a)if(hasOwn.call(a,c)){if(f=typeof c,"number"===f)d='"'+c+'"';else{if("string"!==f)continue;d=$.quoteString(c)}f=typeof a[c],"function"!==f&&"undefined"!==f&&(e=$.toJSON(a[c]),b.push(d+":"+e))}return"{"+b.join(",")+"}"}},$.evalJSON="object"==typeof JSON&&JSON.parse?JSON.parse:function(str){return eval("("+str+")")},$.secureEvalJSON="object"==typeof JSON&&JSON.parse?JSON.parse:function(str){var filtered=str.replace(/\\["\\\/bfnrtu]/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"");if(/^[\],:{}\s]*$/.test(filtered))return eval("("+str+")");throw new SyntaxError("Error parsing JSON, source is not valid.")},$.quoteString=function(a){return a.match(escape)?'"'+a.replace(escape,function(a){var b=meta[a];return"string"==typeof b?b:(b=a.charCodeAt(),"\\u00"+Math.floor(b/16).toString(16)+(b%16).toString(16))})+'"':'"'+a+'"'}}(jQuery);