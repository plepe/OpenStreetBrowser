<?
function cascadenik_compile($file) {
  $file_noext=substr($file, 0, strrpos($file, "."));
  $path=substr($file, 0, strrpos($file, "/"));

  call_hooks("cascadenik_compile", &$file, $path);

  print "Cascadenik process file $file\n";
  system("cascadenik-compile.py $file $file_noext.xml");
  rename("$file_noext.xml", "$file_noext.mapnik");

  call_hooks("cascadenik_compiled", "$file_noext.mapnik");
}
