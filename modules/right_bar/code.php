<?
function right_bar() {
  ?>
<div id='right_bar'>
<!-- close button -->
<div id='right_bar_hide'>
<a href='javascript:right_bar_hide()'><img src='<?=modulekit_file("right_bar", "close_dark.png")?>' alt='X'></a>
</div>

<?
  $content=array();
  $paypal_url=modulekit_file("right_bar", "paypal_logo.png");
  $content[]=array(-5, "<h1>".lang("main:donate")."</h1>".
<<<EOD
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
<a href='javascript:time_count_do_beg()' title='Donate via PayPal'><img src='$paypal_url' alt='Donate via PayPal'></a>
EOD
  );

  $content[]=array(5, <<<EOD
<!-- TWITTERWALL -->
<div id='twitterwall'>
<a class="twitter-timeline"  href="https://twitter.com/search?q=%40osb_cc+OR+%23osb_cc+OR+from%3Aosb_cc+OR+OpenStreetBrowser"  data-widget-id="358461684668567552">Tweets about "@osb_cc OR #osb_cc OR from:osb_cc OR OpenStreetBrowser"</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
<!-- END -->
EOD
  );

  call_hooks("right_bar_content", &$content);

  $content=weight_sort($content);
  print implode("\n<hr>\n", $content);

  print "</div>\n";
}

register_hook("html_start", "right_bar");
