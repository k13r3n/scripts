<?php

// Make sure there are always two command line arguments and exit if not
if(count($_SERVER["argv"]) < 1){
  echo "\none argument is required: Path to input file and Vlan Tag Number\n\nPlease try again....\n\n";
  exit;
}
// Assign command line arguments to variables
$filename = $_SERVER["argv"][1];


$file = file($filename);
// tries to get rid of variations of as
$patterns = array();
$patterns[0] = '/.as42689.net/';
$patterns[1] = '/.as42689.ne/';
$patterns[2] = '/.as42689.n/';
$patterns[3] = '/.as42689./';
$patterns[4] = '/.as42689/';
$patterns[5] = '/.as4268/';
$patterns[6] = '/.as426/';
$patterns[7] = '/.as42/';
$patterns[8] = '/.as4/';
$patterns[9] = '/.as/';
$patterns[10] = '/.a/';
$newfile = preg_replace($patterns, "", $file);
{
echo "conf t\n";
echo "!\n";
}
foreach($newfile as $line){
// Split the words on each row by whitespace
    $parts = preg_split('/\s+/', $line);

echo "int ".$parts[1].$parts[2]."\n";
echo "desc ".$parts[0]."\n";
echo "!\n";

}
{
echo "end\n";
echo "wr\n";
}
?>
