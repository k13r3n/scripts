<?php
#
# Layer 2 Pathinator to Specific ASR's by Kieren
#
fwrite(STDOUT, "VLAN ID (i.e 2653)?\n"); 

// Read the input 
$VLANID = fgets(STDIN); 
$VLANID = trim($VLANID);

fwrite(STDOUT, "Description (i.e BT_EAD_ONEA1111111_Client_The-Site)?\n"); 

// Read the input 
$Desc = fgets(STDIN); 
$Desc = trim($Desc);

fwrite(STDOUT, "PoP Agg Switch Hostname (i.e agg-01.btssglr)?\n"); 

// Read the input 
$AggSwitch = fgets(STDIN); 
$AggSwitch = trim($AggSwitch);
$AggSwitch = strtolower($AggSwitch);

fwrite(STDOUT, "Port you are using at BT POP (i.e 5)?\n"); 

// Read the input 
$PoPPort = fgets(STDIN); 
$PoPPort = trim($PoPPort);

fwrite(STDOUT, "Which ASR are you using (i.e 1 -7)?\n"); 

// Read the input 
$ASR = fgets(STDIN); 
$ASR = trim($ASR);

$array1 = array(
	"agg-01.btssglr" => "77.244.143.220",
	"agg-01.btsswes" => "77.244.143.224",
	"agg-01.btclken" => "77.244.129.179",
	"agg-01.btsmlt" => "77.244.129.251",
	"agg-01.btssred" => "77.244.143.219",
	"agg-5bb20-02.tcwil" => "77.244.129.238",
	"agg-01.btwscen" => "77.244.143.203",
	"agg-ef12-01.ssebrs" => "77.244.129.227",
	"agg-01.rbsov" => "77.244.129.247",
	"tpt-01.ixn" => "77.244.143.193",
	"agg-01.btlccar" => "77.244.143.207",
	"agg-01.tcwil" => "77.244.129.252",
	"agg-01.btwwextr" => "77.244.143.222",
	"agg-01.btswcfate" => "77.244.143.217",
	"agg-01.tcams" => "77.244.129.236",
	"agg-01.btswsx" => "77.244.143.216",
	"agg-01.btmyls" => "77.244.143.226",
	"agg-02-new.ixn" => "77.244.143.235",
	"agg-01.bteacol" => "77.244.129.137",
	"agg-01.ssebrs" => "77.244.143.211",
	"agg-01.btswpn" => "77.244.143.218",
	"agg-vpls-01.ixn" => "77.244.129.239",
	"agg-5bb20-03.tcwil" => "77.244.143.240",
	"agg-02.thdo" => "77.244.143.243",
	"agg-01.btcmcen" => "77.244.143.206",
	"agg-01.btnedu" => "77.244.143.208",
	"agg-01.btssnor" => "77.244.143.225",
	"agg-01.btemlongb" => "77.244.143.130",
	"agg-01.btcmcgf" => "77.244.143.229",
	"agg-01.btwwpyth" => "77.244.143.223",
	"agg-01.btslli" => "77.244.143.134",
	"agg-01.ixn" => "77.244.129.228",
	"agg-01.btwwyeov" => "77.244.143.244",
	"agg-01.btwrfulm" => "77.244.129.138",
	"agg-01.btcmastx" => "77.244.129.250",
	"agg-01.btslsf" => "77.244.143.204",
	"agg-01.btwnae" => "77.244.143.215",
	"agg-02.btcmcgf" => "77.244.143.238",
	"agg-01.btnsden" => "77.244.143.228",
	"agg-02.btssred" => "77.244.143.133",
	"agg-01.btmybd" => "77.244.143.136",
	"agg-02.btemlongb" => "77.244.129.139",
	"agg-01.btsskmd" => "77.244.143.242",
	"agg-02.tcwil" => "77.244.143.231",
	"agg-vpls-02.ixn" => "77.244.143.236",
	"agg-01.btsdpcntc" => "77.244.129.222",
	"agg-01.btwrsthbk" => "77.244.143.129",
	"tpt-02.ixn" => "77.244.143.197",
	"agg-01.btlclan" => "77.244.143.201",
	"agg-01.btclklg" => "77.244.143.239",
	"agg-01.btthrg" => "77.244.143.241",
	"agg-01.btsmof" => "77.244.143.221",
	"agg-01.btlcpre" => "77.244.143.200",
	"agg-01.btnent" => "77.244.143.202",
	"agg-01.btlcamb" => "77.244.143.209",
	"agg-01.btesros" => "77.244.143.227",
	"agg-5bb20-01.tcwil" => "77.244.129.229",
	"agg-02.btemmontf" => "77.244.143.237",
	"agg-01.btlvcen" => "77.244.143.205",
	"agg-01.btsdchchs" => "77.244.129.223",
	"agg-vpls-01-new.ixn" => "77.244.143.196",
	"agg-02.btwewblo" => "77.244.129.220",
	"agg-01.pulsg" => "77.244.129.241",
	"agg-01.btwewblo" => "77.244.129.178",
	"agg-01-new.ixn" => "77.244.143.234",
	"agg-01.thdo" => "77.244.129.237",
	"agg-01.btlvroy" => "77.244.143.210",
	"agg-01.btemmontf" => "77.244.143.213",
	"agg-01.btstsoton" => "77.244.129.221"
	); 

$result = array_key_exists($AggSwitch, $array1);

if ($result == TRUE) {
	$LSRID = $array1[$AggSwitch];
}

else {
	echo "Agg switch not found, check the hostname is correct or get Tim to add it!\n";
	exit;
}

$Ar01 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#


#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#


#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:21 tagged
#
save conf
y
#


#
# agg-ar-01.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 1:21,1:34 tagged
#
save conf
y
#


EOF;


$Ar02 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#


#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#


#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:21 tagged
#
save conf
y
#


#
# agg-ar-01.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 1:21,1:36 tagged
#
save conf
y
#


EOF;


$Ar03 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#


#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#


#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:21 tagged
#
save conf
y
#


#
# agg-ar-01.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 1:21,1:37 tagged
#
save conf
y
#

EOF;

$Ar04 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#


#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#


#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:21 tagged
#
save conf
y
#

#
# agg-ar-01.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 1:21,2:48 tagged
#
save conf
y
#


EOF;


$Ar05 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#

#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#

#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:10 tagged
#
save conf
y
#


EOF;


$Ar06 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#

#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#

#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:12 tagged
#
save conf
y
#


EOF;


$Ar07 = <<<EOF

#
# $AggSwitch
#
configure ports $PoPPort description-string "$Desc"
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports $PoPPort tagged
disable igmp snooping vlan "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer 77.244.143.236 core full-mesh
#
save conf
y
#

#
# agg-vpls-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 57 tagged
disable igmp snooping vlan$VLANID "vlan$VLANID"
create l2vpn vpls vpls_vlan$VLANID fec-id-type pseudo-wire 
configure l2vpn vpls vpls_vlan$VLANID add service vlan vlan$VLANID
configure l2vpn vpls vpls_vlan$VLANID add peer $LSRID core full-mesh
#
configure access-list Block_BPDUs vlan vlan$VLANID
#
save conf
y
#

#
# agg-ar-02.ixn
#
create vlan "vlan$VLANID"
configure vlan vlan$VLANID description "$Desc"
configure vlan vlan$VLANID tag $VLANID
configure vlan vlan$VLANID add ports 2:65,1:1 tagged
#
save conf
y
#


EOF;


if ($ASR == 1) {
    echo $Ar01;
}
elseif ($ASR == 2) {
    echo $Ar02;
}
elseif ($ASR == 3) {
    echo $Ar03;
}
elseif ($ASR == 4) {
    echo $Ar04;
}
elseif ($ASR == 5) {
    echo $Ar05;
}
elseif ($ASR == 6) {
    echo $Ar06;
}
elseif ($ASR == 7) {
    echo $Ar07;
}
else {
    echo "Invalid Input! Enter a number like '1'\n";
}

?>