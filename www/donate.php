<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<!-- Heavily based on http://wikimediafoundation.org#Donate/en -->
	<!-- Believed to be GNU Free Documentation License -->
	<meta http-equiv="Content-Language" content="en" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Support OpenStreetMap Donate Now</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<script type="text/javascript" src="js/main.js"></script>
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
  if ( form.amountGiven.value != "" ) {
    amount = form.amountGiven.value;
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
//]]>
</script>
</head>
<body>
	<div align="center">
			<div id="container" dir="ltr">
<div id="banner" dir="ltr">
<div class="logo-box"><a href="http://www.openstreetmap.org/"><img alt="OpenStreetMap Logo" src="images/osm_logo.png" width="120" height="120" border="0" /></a></div>
<h1>Donate to OpenStreetMap</h1>
</div>
<div class="content-container" style="margin: 5px 80px;"><!-- content container -->
<div class="infobox">
	<p><strong>25 Oct 2009: Domain donation total reached. Thanks to all who contributed. You can continue to support OpenStreetMap with a general donation using the form below.</strong></p>
</div>
</div>
<div class="content-container" style="margin: 5px 80px;"><!-- content container -->
<p></p>
<form method="post" action="process/paypal-submit.php" name="paypalcontribution" onsubmit="return validateForm(this)">
<p><input type="hidden" name="gateway" value="paypal" /><input type="hidden" name="language" value="en" /></p>
<div id="liquid-round" style="width:600px;"><a id='newtop' name='newtop'></a>
<div class="rightside">
<div class="top"><span></span></div>
<div class="bottom">
<div class="bottom-right">
<div class="center-content"><!-- CONTENT BEGIN -->
<div id="main-title">Support OpenStreetMap</div>
<p>&nbsp;</p>
<div style="clear:left">Contribute with your credit card through PayPal. (Other ways to give, including electronic funds transfer can be <a href="http://wiki.openstreetmap.org/wiki/Donations">found here</a>.)</div>
<div id="sub-title">Amount</div>
<div id="amount-box">
	<input type="radio" name="amount" id="input_amount_1" onclick="document.paypalcontribution. amountGiven.value = '15'" />&#160;<label for="input_amount_1">£15</label> &#160;&#160;&#160; 
	<input type="radio" name="amount" id="input_amount_2" onclick="document.paypalcontribution. amountGiven.value = '30'" /><label for="input_amount_2">&#160;£30</label> &#160;&#160;&#160; 
	<input type="radio" name="amount" id="input_amount_3" onclick="document.paypalcontribution. amountGiven.value = '50'" /><label for="input_amount_3">&#160;£50</label> &#160;&#160;&#160; 
	<input type="radio" name="amount" id="input_amount_other" value="Other" />&#160;<label for="input_amount_other">Other:</label> <input type="text" name="amountGiven" size="5" onfocus="this.form.input_amount_other.checked=true;" />&#160; <!-- currency menu -->
<select name="currency_code" id="input_currency_code" size="1">
<option value="GBP" selected="selected">GBP – £</option>
<option value="XXX">-------</option>
<option value="GBP">GBP – £</option>
<option value="EUR">EUR – €</option>
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
<div id="blue-title">Public Comment</div>
<div>Have a thought to share with the world? Put up to 200 characters here:</div>
<input type="text" name="comment" size="30" maxlength="200" style="width:100%;" />
<div class="small"><input type="checkbox" name="comment-option" id="input_comment-option" value="comment" checked="checked" />&#160;&#160;<label for="input_comment-option">Please list my name (next to my comment) on the public donor list.</label></div>
<br />
<div>Your credit card donation will be processed by PayPal. The charge will appear as "OpenStreetMap Foundation" on your credit card statement.</div>
<br />
<input type="submit" value="DONATE" class="red-input-button" />
<div class="small">For more information about the OpenStreetMap Foundation’s non-profit status, the Treasurer’s Report, or other questions, <a href="http://foundation.openstreetmap.org/">click here</a>.</div>
<br /><br />&nbsp;
<!-- CONTENT END --></div>
</div>

</div>
</div>
</div>
</form>
<p></p>
</div>
<div id="footer">
<div class="logo-box"><a href="http://www.openstreetmap.org/"><img alt="OpenStreetMap Logo" src="images/osm_logo.png" width="120" height="120" border="0" /></a></div>
<div>
<p>OpenStreetMap is supported by the <a href="http://foundation.openstreetmap.org/" class="external text">OpenStreetMap Foundation</a>. Questions or comments? Contact the OpenStreetMap Foundation: <a href="mailto:donate@osmfoundation.org" class="external text">donate@osmfoundation.org</a></p>
</div>

<div id="languages">
	<a href="/comments/">Live Donor List</a> 
<!--
	Later... 
	<a href="af/" title="Skenk">Afrikaans</a> · 
-->
<br /></div>
</div>
</div>
		</div>
	</body>
</html>
