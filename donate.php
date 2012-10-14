<?
Header("content-type: application/xhtml+xml; charset=UTF-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<!-- Heavily based on http://donate.openstreetmap.org/ -->
	<!-- Heavily based on http://wikimediafoundation.org#Donate/en -->
	<!-- Believed to be GNU Free Documentation License -->
	<meta http-equiv="Content-Language" content="en" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Support OpenStreetBrowser - Donate Now</title>
	<link rel="stylesheet" href="donate.css" type="text/css"/>
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
<script type="text/javascript" language="javascript">
//<![CDATA[
function validateForm( form ) {
  var minimums = {
    'USD' : 1,
    'GBP' : 1, // $1.26
    'EUR' : 1, // $1.26
    'AUD' : 2, // $1.35
    'CAD' : 1, // $0.84
    'CHF' : 1, // $0.85
    'CZK' : 20, // $1.03
    'DKK' : 5, // $0.85
    'HKD' : 10, // $1.29
    'HUF' : 200, // $0.97
    'JPY' : 100, // $1
    'NZD' : 2, // $1.18
    'NOK' : 10, // $1.44
    'PLN' : 5, // $1.78
    'SGD' : 2, // $1.35
    'SEK' : 10 // $1.28
  };

  var error = true;

  // Get amount selection
  var amount = null;
  for ( var i = 0; i < form.amount.length; i++ ) {
    if ( form.amount[i].checked ) {
      amount = form.amount[i].value;
    }
  }
  if ( form.amount.value != "" ) {
    amount = form.amount.value;
  }
  // Check amount is a real number
  error = ( amount == null || isNaN( amount ) || amount.value <= 0 );
  if ( error ) {
    alert( 'You must enter a valid amount.' );
  }

  // Check amount is at least the minimum
  var currency = form.currency_code[form.currency_code.selectedIndex].value;
  if ( typeof( minimums[currency] ) == 'undefined' ) {
    minimums[currency] = 1;
  }
  if ( amount < minimums[currency] ) {
    alert( 'You must contribute at least $1'.replace('$1', minimums[currency] + ' ' + currency ) );
    error = true;
  }
  
  return !error;
}

function close_donation() {
  window.top.close_donation();
}
//]]>
</script>
</head>
<body>
	<div align="center">
			<div id="container" dir="ltr">
<div id="banner" dir="ltr">
<h1>Donate to OpenStreetBrowser</h1>
<p>
Hi! You like this service, don't you?
</p><p>
The OpenStreetBrowser is my hobby, I'm developing it in my free time. It takes a lot of time to continously improve it.
</p><p>
If you appreciate this service and want to show your gratitude, you could donate some money. It would encourage me to continue working on it.
</p><p>
I myself promise to donate 5% of this income to the <a href='http://www.osmfoundation.org/'>OpenStreetMap-Foundation</a>.
</p>
<hr/>
<p>
You could either flattr me:<br/>
<a class="FlattrButton" style="display:none;" href="http://www.openstreetbrowser.org/"></a>
</p>
<hr/>
<form target="_new" method="post" action="https://www.paypal.com/cgi-bin/webscr" name="paypalcontribution" onsubmit="return validateForm(this)">
<p><input type="hidden" name="gateway" value="paypal" /><input type="hidden" name="language" value="en" /></p>
<p>or contribute (with your credit card) through PayPal:</p>

<div id="amount-box">
	<input type="hidden" name="cmd" value="_xclick" />
	<input type="hidden" name="business" value="skunk@xover.mud.at" />
	<input type="hidden" name="item_name" value="OpenStreetBrowser Donation" />
	<input type="radio" name="amount_sel" id="input_amount_1" onclick="document.forms[0]. amount.value = '5'" />&#160;<label for="input_amount_1">5</label>
	<input type="radio" name="amount_sel" id="input_amount_2" onclick="document.forms[0]. amount.value = '10'" /><label for="input_amount_2">&#160;10</label>
	<input type="radio" name="amount_sel" id="input_amount_3" onclick="document.forms[0]. amount.value = '25'" /><label for="input_amount_3">&#160;25</label>
	<input type="radio" name="amount_sel" id="input_amount_other" value="Other" />&#160;<label for="input_amount_other">Other:</label> <input type="text" name="amount" size="5" onfocus="this.form.input_amount_other.checked=true;" /> <!-- currency menu -->
<select name="currency_code" id="input_currency_code" size="1">
<option value="EUR">EUR – €</option>
<option value="GBP">GBP – £</option>
<option value="USD">USD - $</option>
<option value="AUD">AUD – $</option>
<option value="CAD">CAD – $</option>
<option value="CHF">CHF –</option>
<option value="CZK">CZK – Kč</option>

<option value="DKK">DKK – kr</option>
<option value="EUR">EUR – €</option>
<option value="HKD">HKD – HK$</option>
<option value="HUF">HUF – Ft</option>
<option value="GBP">GBP – £</option>
<option value="JPY">JPY – ¥</option>
<option value="NZD">NZD – NZ$</option>
<option value="NOK">NOK – kr</option>
<option value="PLN">PLN – zł</option>

<option value="SGD">SGD – S$</option>
<option value="SEK">SEK – kr</option>
<option value="USD">USD – $</option>
</select></div>
<br />
<div>Your donation will be processed by PayPal. The charge will appear as "Stephan Plepelits" on your credit card statement.</div>
<br />
<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" valign="middle" />
</form>
</div>
<a href="donors.html">Live Donor List</a> - <a href='mailto:skunk@xover.mud.at?subject=OpenStreetBrowser%20Donation'>Send me a comment</a><br/><a href='javascript:close_donation()'>Close Window</a>
</div>
		</div>
	</body>
</html>
