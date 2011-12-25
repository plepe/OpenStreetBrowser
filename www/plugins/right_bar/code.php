<?
function right_bar() {
  ?>
<div id='right_bar'>
<!-- close button -->
<div id='right_bar_counter_hide'></div>
<div id='right_bar_hide'>
<a href='javascript:right_bar_hide()'><img src='plugins/win/close_dark.png' alt='X'></a>
</div>

<div class='stuff'>
<h1><?=lang("main:donate")?></h1>
<!-- FLATTR -->
<p>
<a class="FlattrButton" style="display:none;" href="http://www.openstreetbrowser.org/"></a>
</p>
<script type="text/javascript">
/* <![CDATA[ */
    (function() {
        var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
        s.type = 'text/javascript';
        s.async = true;
        s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
        t.parentNode.insertBefore(s, t);
    })();
/* ]]> */
</script>
<!-- PAYPAL -->
<a href='javascript:time_count_do_beg()' title='Donate via PayPal'><img src='plugins/right_bar/paypal_logo.png' alt='Donate via PayPal'></a>

<hr>
<!-- TIPPS -->
<h1>Tip of the day</h1>
<p class='tip'>Did you know you can use <a href='http://osb.cc'>http://osb.cc</a> as short url?</p>

</div> <!-- .stuff -->
<hr>
<!-- TWITTERWALL -->
<iframe id='twitter' src='plugins/right_bar/twitter.html'></iframe>
<!-- END -->
</div>
  <?
}

register_hook("html_start", "right_bar");
