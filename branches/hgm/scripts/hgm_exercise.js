// hgm exercise functionality
	$().ready(function() {
					   
	
		// Process form submit:
		$('#process_form').click(function() {
										  
			$('#error_msg').html(' ');
										  
			// Make sure we have input first (basic validation):
			if($('#input_checkbox_links').is(':checked') !== true && $('#input_checkbox_images').is(':checked') !== true){
				$('#error_msg').html('Please select one of the x-tract options');
				return false;
			}
			if($('#input_url').val() == '' && $('#input_html').val() == ''){
				$('#error_msg').html('Please specify either a URL or HTML for input');
				return false;
			}
			if($('#input_url').val() != '' && $('#input_url').val().substr(0,7) != 'http://'){
				$('#error_msg').html('Please specify a valid URL');
				return false;
			}

			var formData = $('#d_form').serialize();
			
			$.post('hgm_exercise.php',formData,processData);
			
			
     	});
		
		function processData(data){
			
			 $("#result_link").html('');
			 $("#result_pics").html('');
			 
			 img_counter = 0;
			 link_counter = 0;
			
			 dataObj = eval("(" + data + ")");
			 
			 // Process links output
			 if(typeof(dataObj.d_links) != "undefined"){
				 jQuery.each(dataObj.d_links, function() {								   					   
					$("#result_link").append("<li>" + this + "</li>");
					link_counter ++;
				  });
				 // Correct relative paths:
				 $("#result_link li a").each(function() {
					var current_url = $(this).attr('href');
					if(typeof(current_url) != "undefined" != ''){
						if(current_url.substr(0,4) != 'http'){
							var new_url = $('#input_url').val() + "/" + current_url;
							$(this).attr('href',new_url);
						}
					}
				 });
			}
			$("#links_counter").html(link_counter);
			 
			 // Process images output
			 if(typeof(dataObj.d_images) != "undefined"){
				 jQuery.each(dataObj.d_images, function() {
					$("#result_pics").append("<li>" + this + "</li>");
					img_counter ++;
				 });
			 }
			$("#pics_counter").html(img_counter);
			
		}

					   
	 }); // End ready function