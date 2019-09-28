<?php
//this is for  taking the output of cdp nei and making it into a pastable format to paste back into the switch.
//arg 1 must be the output of a sh cdp nei det | i ^Device|^Interface
// Make sure there are always two command line arguments and exit if not
if(count($_SERVER["argv"]) < 1){
  echo "\none argument is required: file containing output of this command sh cdp nei det | i ^Device|^Interface\n\n\n";
  exit;
}
echo "renenber to run this command sh cdp nei det | i ^Device|^Interface\n\n";
// Assign command line arguments to variables then slit the file by each line using hte file method.
$filename = $_SERVER["argv"][1];
$file = file($filename);

// reverse the file as the format currently is line 1 hostname line 2 port.
$file =  array_reverse($file);


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
$patterns[9] = '/\.as/';
$file = preg_replace($patterns, "", $file);
$newfile = preg_replace('/Interface:/', "int ", $file);
$newfile = preg_replace('/Device ID:/', "desc ", $newfile);

function deletecomma($string)
{
return(preg_replace("/,.*/", "",$string));
//return(substr($string, 0, strpos($string, ",")));
}
//print_r($newfile);
$newfile = array_map("deletecomma", $newfile);
$newfile = array_map('rtrim', $newfile);
//print_r($newfile);

echo "conf t\n";
echo "!\n";


foreach($newfile as $line){
// Split the words on each row by whitespace
//    $parts = preg_split('/\s+/', $line);

echo $line;
echo " \n";
}

{
echo "end\n";
echo "wr\n";
}
?>
