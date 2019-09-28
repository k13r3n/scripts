<?php

// Make sure there are always two command line arguments and exit if not
if(count($_SERVER["argv"]) < 1){
  echo "\none argument is required: Path to input file with the output of sh lldp nei det | i Local I|System Name \n\nPlease try again....\n\n";
  exit;
}
echo "renenber to run the command 'sh lldp nei det | i Local I|System Name'\n"; 

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
$patterns[9] = '(.as)';
// get rid of any variation of as42689.net
$file = preg_replace($patterns, "", $file);
$newfile = preg_replace('/Local Intf:/', "int ", $file);
$newfile = preg_replace('/System Name:/', "desc ", $newfile);
//trim newfile to get rid of spacess and carriage returns at the start and end
//$newfile1 = array_map('trim', $newfile);
//$imploded = implode('',$newfile1);
//$exploded = explode("Local", $imploded);
//VAR_DUMP ($exploded);

{
echo "conf t\n";
echo "!\n";
}
foreach($newfile as $line){
// Split the words on each row by whitespace
//    $parts = preg_split('/\s+/', $line);
// var_dump ($parts);
//if ($line != NULL)
{
echo $line;
//echo "port-name ".trim($parts[6], '"')."\n";
//echo "!\n";
}
}
{
echo "end\n";
echo "wr mem\n";
}

?>
