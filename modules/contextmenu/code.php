<?
function contextmenu_init() {
?>
<div id="contextmenu" class="contextmenu" onmouseout="contextmenu_mouseout(event)" onmouseover="javascript:clearTimeout(contextmenu_timer)" style="top:20px; left:500px; display:none;">
<span id="contextmenu_pointer" style="position:absolute; width:10px; height:10px;"></span>
<table id="contextmenu_table" cellspacing="0">
</table></div>
<?
}

register_hook("html_done", "contextmenu_init");
