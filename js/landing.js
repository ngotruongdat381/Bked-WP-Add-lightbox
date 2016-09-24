/**
 * Reset Sigup form - clear all fields
 */

function reset_sigup_form() {

  jQuery('#newsletter-section-7 #email').val('');
  jQuery('#newsletter-section-7 #name').val('');
  jQuery('#newsletter-footer #email').val('');
  jQuery('#newsletter-footer #name').val('');
}

/**
 * Add user to Mailchimp list from Newletters signup form
 */
function submit_newletter() {
  var $btn = jQuery(document.activeElement);
  var $email;
  var $name;
  var $errorResponse;
  var $successResponse;

  if ($btn.length && $btn.attr('id')=="submit-section-7") {
      $email = jQuery('#newsletter-section-7 #email').val();
      $name = jQuery('#newsletter-section-7 #name').val();
      $errorResponse = jQuery('#newsletter-section-7 #error-response');
      $successResponse = jQuery('#newsletter-section-7 #success-response');
  }

  if ($btn.length && $btn.attr('id')=="submit-footer"){
      $email = jQuery('#newsletter-footer #email').val();
      $name = jQuery('#newsletter-footer #name').val();
      $errorResponse = jQuery('#newsletter-footer #error-response');
      $successResponse = jQuery('#newsletter-footer #success-response');
  }

  jQuery.ajax({
    type: 'POST',
    url: ajaxurl, // get value from landing template
    data: {
      'action': 'mailchimp_subscribe',
      'email': $email,
      'name': $name
    }, 
    success: function(response) {
      $errorResponse.hide();
      $successResponse.show();
      reset_sigup_form();
      setTimeout(function(){
        $successResponse.hide();
      }, 5000);
    },
    error: function(response) {
      $successResponse.hide();
      $errorResponse.show();
      reset_sigup_form();
      setTimeout(function(){
        $errorResponse.hide();
      }, 5000);
    }
  });

  return false;
};
