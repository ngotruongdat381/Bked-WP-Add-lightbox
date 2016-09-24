<?php /* Template Name: Landing */
  get_header();
?>

<div id="landing">
  <div class="top">
    <div class="inner">
      <div class="logo">
        <img class="logo" src="<? echo get_theme_root_uri();?>/x-child/images/logo-combine.png" alt="">
      </div>
      <p class="coming-soon">COMING SOON</p>
    </div>
  </div>
  <div class="bottom">
    <div class="form">
      <form onsubmit="return submit_newletter();" action="">
        <h5 class="title">SUBSCRIBE TO OUR NEWSLETTER</h5>
        <div class="responses">
          <div id="error-response">
              Signup newletters fail!
          </div>
          <div id="success-response">
              Signup newletters successfully!
          </div>
        </div>
        <div class="field">
          <input id="name" type="text" placeholder="your name" required />
        </div>
        <div class="field">
          <input id="email" type="email" placeholder="your email" required />
        </div>
        <div class="submit">
          <button id="submit" type="submit">SUBMIT</button>
        </div>
      </form>
    </div>
  </div>
</div>
