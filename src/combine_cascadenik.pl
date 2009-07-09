#!/usr/bin/perl

$root_path=$ENV{ROOT_PATH};

open(O, "python compile.py $root_path/render/routes.mml|");
while($r=<O>) {
  if($r =~ /<Style /) {
    $mode=1;
  }
  elsif($r =~ /<Layer /) {
    $mode=2;
  }

  if($mode>0) {
    $text[$mode].=$r;
  }

  if(($r =~ /<\/Style>/)||($r =~ /<\/Layer>/)) {
    $mode=0;
  }
}
close(O);

open(O, "<$root_path/render/global.mml");
while($r=<O>) {
  if($r eq "INSERTSTYLE\n") {
    print $text[1];
  }
  elsif($r eq "INSERTLAYER\n") {
    print $text[2];
  }
  else {
    print $r;
  }
}
close(O);
