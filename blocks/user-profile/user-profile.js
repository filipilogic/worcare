document.addEventListener('DOMContentLoaded', function() {
	var copyLink = document.getElementById('copyEmpSurveyLink');
	var triggerTextCollection = document.getElementsByClassName('ud-blurred');

	// Attach click event listener
	if ( copyLink ) {
		copyLink.addEventListener('click', function(event) {
		  // Prevent the default behavior of the link
		  event.preventDefault();
	
		  // Create a temporary input element
		  var tempInput = document.createElement('input');
	
		  // Set the value of the input to the link's href attribute
		  tempInput.value = copyLink.href;
	
		  // Append the input element to the document
		  document.body.appendChild(tempInput);
	
		  // Select the input's value
		  tempInput.select();
	
		  // Execute the copy command
		  document.execCommand('copy');
	
		  // Remove the temporary input element
		  document.body.removeChild(tempInput);
	
		  // Display the copy message
		  var copyMessage = document.getElementById('copyEmpSurveyLinkMessage');
		  copyMessage.style.display = 'block';
	
		  // Hide the message after a short delay
		  setTimeout(function() {
			copyMessage.style.display = 'none';
		  }, 3000);
		});
	}

    // Loop through the collection and attach event listener to each element
    Array.from(triggerTextCollection).forEach(function(triggerText) {
        triggerText.addEventListener('click', function(event) {
            // Prevent the default behavior of the link
            event.preventDefault();

            var triggerTextMessage = document.getElementById('triggerTextMessage');
            triggerTextMessage.style.display = 'block';

            // Hide the message after a short delay
            setTimeout(function() {
                triggerTextMessage.style.display = 'none';
            }, 3000);
        });
    });
});

jQuery(document).ready(function(){
	// Click event handler for the button
	jQuery('.il_edit_password_btn').click(function(e){
		e.preventDefault();
		
		// Show the section with class .ud-password-change-form
		jQuery('.ud-password-change-form').show();

		// Scroll to the .ud-password-change-form element
		jQuery('html, body').animate({
			scrollTop: jQuery('.ud-password-change-form').offset().top
		}, 200); // 1000 milliseconds (1 second) for the scroll animation
	});
});
