#!/usr/bin/perl

$root_path=$ENV{ROOT_PATH};
$file=$ARGV[0];

$full_text="";
open(O, "<$file");
while($r=<O>) {
$full_text.=$r;
}
close(O);

open(O, ">$file");
$full_text=~s/__DBNAME__/$ENV{DB_NAME}/g;
print O $full_text;
close(O);
