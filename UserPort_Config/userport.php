<?php
if(count($_SERVER["argv"]) < 3){
  echo "\nTwo arguments are required: Path to input file and Vlan Tag Number\n\nPlease try again....\n\n";
  exit;
}
// Assign command line arguments to variables
$filename = $_SERVER["argv"][1];
$vlan =  $_SERVER["argv"][2];


$file = file($filename);
{
echo "conf t\n";
echo "!\n";
}
foreach($file as $line){
echo "default int ".$line."";
echo "!\n";
}

foreach($file as $line){


echo "int ".$line."";
echo "description User Port\n";
echo "switchport access vlan ".$vlan."\n";
echo "switchport mode access\n";
echo "ip arp inspection limit rate 200\n";
echo "load-interval 30\n";
echo "no power efficient-ethernet\n";
echo "no cdp enable\n";
echo "spanning-tree portfast\n";
echo "spanning-tree bpduguard enable\n";
echo "ip verify source\n";
echo "!\n";
}
{
echo "end\n";
echo "wr\n";
}
?>

