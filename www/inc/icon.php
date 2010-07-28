<?
require_once("inc/data_dir.php");
$icon_dir;

class icon_obj extends git_obj {
  function preprocess($files) {
    $srcfile=$this->path("file.src");
    $dstfile=$this->path("preview.png");

    $finfo=finfo_open(FILEINFO_MIME_TYPE);
    $mime=finfo_file($finfo, $srcfile);
    finfo_close($finfo);

    switch($mime) {
      case "image/svg+xml":
        // imagemagick in Ubuntu Jaunty/Lucid is broken ... use rsvg instead
	$this->exec("rsvg {$srcfile} {$dstfile}");
	break;
      default:
	$this->exec("convert {$srcfile} png:{$dstfile}");
    }
  }
}

function icon_init() {
  global $icon_dir;
  global $data_dir;

  $icon_dir=new git_dir($data_dir, "icons", "icon_obj");
}

register_hook("html_start", "icon_init");
register_hook("ajax_start", "icon_init");
