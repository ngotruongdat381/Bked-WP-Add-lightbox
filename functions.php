<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to X in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );



// Additional Functions
// =============================================================================

/**
 * Add addtion style and script files
 */

add_action( 'wp_enqueue_scripts', 'enqueue_my_styles', 1000);
function enqueue_my_styles() {
  wp_enqueue_style( 'addition-styles', get_theme_root_uri() . '/x-child/css/addition-styles.css' );
  wp_enqueue_script( 'newletter-signup-scripts', get_theme_root_uri() . '/x-child/js/landing.js' );
  wp_enqueue_script( 'addition-scripts', get_theme_root_uri() . '/x-child/js/addition-scripts.js' );
}

/**
 * Add Jquery Mobile
 */
function get_jqm() {
  wp_enqueue_script(
  'jqm_js',
  'http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js',
  array('jquery'),
  '1.4.5'
  );

  wp_register_style(
  'jqm_css',
  'http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css',
  ”,
  '1.4.5'
  );
  wp_enqueue_style(
  'jqm_css',
  'http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css',
  ”,
  '1.4.5'
  );
}
add_action('wp_enqueue_scripts', 'get_jqm');

/**
 * Action add user to Mailchimp list
 */

add_action('wp_ajax_mailchimp_subscribe', 'mailchimp_subscribe');
add_action('wp_ajax_nopriv_mailchimp_subscribe', 'mailchimp_subscribe');

function mailchimp_subscribe() {
  $api_key = "b56d25b4fcc933ccd6b385602c158b40-us13";
  $list_id = "230cd8d045";
  
  require('Mailchimp.php');
  
  $Mailchimp = new Mailchimp( $api_key );
  $Mailchimp_Lists = new Mailchimp_Lists( $Mailchimp );
  
  $subscriber = $Mailchimp_Lists->subscribe( $list_id, array('email' => htmlentities($_POST['email'])), array('FNAME' => $_POST['name']), 'html', false, true );
  if ( ! empty( $subscriber['leid'] ) ) {
    echo "success";
  }
  else {
    echo "fail";
  }
  exit();
}

/**
 * Quickview
 */

function add_ajaxurl() {
  echo '<script type="text/javascript">
          var ajaxurl = "'.admin_url('admin-ajax.php').'";
        </script>';
  }
add_action( 'wp_footer', 'add_ajaxurl', 100 );

function quickview_action() {
?>
  <div id="quickViewOverlay"></div>
  <div id="quickViewResponseData"></div>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery('a[href*="getproductquickview"]').on('click', function(e){ // filter quick view links
        e.preventDefault();
        jQuery('#quickViewResponseData').html('<div class="loading-div"><img src="<?php echo get_theme_root_uri();?>/x-child/images/ajax-loader.gif"/></div>');
        jQuery('#quickViewResponseData').css('display', 'flex');
        jQuery('#quickViewOverlay').css('display', 'block');

        var data = {
          'action': 'my_action',
          'productUrl': jQuery(this).attr("href")
        };

        jQuery.post(ajaxurl, data, function(response) {
          jQuery('#quickViewResponseData').html(response);
        });
      });
    });
  </script>
<?php
}

function popup_lightbox() {
?>
  <!-- Lightbox popup -->
  <div id="lightbox_close">X</div>
  <div id="lightbox_light" class="white_content">
  <!-- 
      <div class="logo">
          <img src="%%ASSET_images/logo-black.png%%" alt="">
      </div>
      <div class="title">JOIN MINETAN PROFESSIONAL AU AND RECEIVE 20% OFF YOUR FIRST ORDER</div>
      <div class="sub-title">AND REGISTER FOR TRADE PRICING</div>
    -->
    <p class="sign-up">SIGN UP</p> 
    <p class="get10">GET A $10</p> 
    <p class="voucher">voucher</p>
    <hr class="hline">
    <p class="para">Plus be the first to find out the lastest deals, beauty & tanning tips, trend and how to’s from the insider of tan</p>
    <div class="response">&nsbp;</div>
    <div class="form">
      <form id="lightbox-newletter" action="" onsubmit="return sendNewletter()" method="post">
        <input id="mce-EMAIL" value="" name="email[email]" type="email" placeholder="Your email" required autofocus/>
        <input value="" name="merge_vars[MMERGE3]" id="mce-MMERGE3" type="text" placeholder="Fullname/Business Name" />
        <input value="" name="merge_vars[MMERGE4]" id="mce-MMERGE4" type="text" placeholder="Phone" />
        <input type="text" name="double_optin" class="" value="false" id="double_optin" style="display: none !important">
        <input type="text" name="update_existing" class="" value="true" id="update_existing" style="display: none !important">
        <input type="text" name="send_welcome" class="" value="true" id="send_welcome" style="display: none !important">
        <p class="submit-button">
          <button type="submit" name="submit" id="newsletter-submit">SUBSCRIBE</button>
        </p>
      </form>
    </div>
  </div>
  <div id="lightbox_fade" class="black_overlay"></div>
  <script>
    function show_popup(){
      jQuery('body').addClass('noscroll');
      jQuery('#lightbox_light').show();
      jQuery('#lightbox_close').show();
      jQuery('#lightbox_fade').show();
    }
    function hide_popup(){
      jQuery('body').removeClass('noscroll');
      jQuery('#lightbox_light').hide();
      jQuery('#lightbox_close').hide();
      jQuery('#lightbox_fade').hide();
    }
    function afterSubmit(code) {
      if (code == 0) {
        jQuery('.response').html('Thanks for Subscribing!');
        jQuery('.response').addClass('success');
      } else {
        jQuery('.response').html('Subscribe fail!');
        jQuery('.response').addClass('error');
      }
      jQuery('.response').show();
    }
    function sendNewletter(){
      $form = jQuery('#lightbox-newletter');
      $.ajax({
        type: "get",
        url: 'https://us11.api.mailchimp.com/2.0/lists/subscribe.json?apikey=91fe012b526630608f154ce854db54d2-us11&id=c22cc52c1e',
        data: $form.serialize(),
        complete: function(xhr, textStatus) {
          afterSubmit(xhr.status);
        },
      });
      return false;
    }
    jQuery(document).ready(function(){
      jQuery('#lightbox_close').click(function(){
        hide_popup();
      });

/*      if($.cookie('popup') != 'seen' && $('body').attr('id') == 'shopProfessional'){
        $.cookie('popup', 'seen', { expires: 3, path: '/' });
        show_popup();
      };*/

      show_popup(); //just for testing
    });
  </script>
      <!-- End lightbox popup -->
<?php
}
add_action( 'wp_footer', 'popup_lightbox' );

add_action( 'wp_footer', 'quickview_action' );
add_action( 'wp_enqueue_scripts', 'add_frontend_ajax_javascript_file' );

function add_frontend_ajax_javascript_file() {
  wp_localize_script( 'frontend-ajax', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}

add_action( 'wp_ajax_my_action', 'get_product_responsse' );
add_action( 'wp_ajax_nopriv_my_action', 'get_product_responsse' );

function get_product_responsse() {
  $json = file_get_contents($_POST['productUrl']);
  $obj = json_decode($json);
?>
  <div class="close-quickview">
    <img src="<?php echo get_theme_root_uri();?>/x-child/images/icon-close.png"/>
  </div>
  <script>
    jQuery(document).ready(function(){
      jQuery('.close-quickview').click(function(){
        jQuery('#quickViewResponseData').hide();
        jQuery('#quickViewOverlay').hide();
      });

      //change value of button
      jQuery('.btn').val("ADD TO CART");
    });
  </script>
<?php
  $str =  str_replace("$(", "jQuery(", $obj->content);
  echo $str;
  wp_die();
}

