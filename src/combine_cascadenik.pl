#!/usr/bin/perl

$root_path=$ENV{ROOT_PATH};

$full_text="";
open(OB, "<$root_path/render/base.mml");
while($rb=<OB>) {
  if($rb =~ "INSERTLAYER ([a-zA-Z0-9_]+)\n") {
    @text=get_mss_mll($1);
    $full_text.=$text[1];
    $full_text.=$text[2];
  }
  else {
    $full_text.=$rb;
  }
}
close(OB);

$full_text=~s/__DBNAME__/$ENV{DB_NAME}/g;
print $full_text;
exit;

sub get_mss_mll {
  my $part=$_[0];
  my @text;
  my $mode;
  my $r;
  print STDERR "$part\n";

  open(O, "cascadenik-compile.py $root_path/render/$part.mml|");

  while($r=<O>) {
    if($r =~ /<Style /) {
      $mode=1;
    }
    elsif($r =~ /<Layer /) {
      $mode=2;
    }

    if($mode>0) {
      if($r =~ /^(.*)<StyleName>(.*)<\/StyleName>/) {
	$text[$mode].="$1<StyleName>$part $2</StyleName>\n";
      }
      elsif($r =~ /^(.*)<Style name="(.*)">/) {
	$text[$mode].="$1<Style name=\"$part $2\">\n";
      }
      elsif($r =~ /^(.*)<Layer name="(.*)"(.*)$/) {
	$text[$mode].="$1<Layer name=\"$part $2\"$3\n";
      }
      else {
	$text[$mode].=$r;
      }
    }

    if(($r =~ /<\/Style>/)||($r =~ /<\/Layer>/)) {
      $mode=0;
    }
  }
  close(O);

  return @text;
}

sub get_mss {
  my $part=$_[0];
  my @text;

  @text=get_mss_mll($part);

  return $text[1];
}

sub get_mml {
  my $part=$_[0];
  my @text;

  @text=get_mss_mll($part);

  return $text[2];
}

