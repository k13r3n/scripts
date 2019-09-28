<?php
#
# UNITE Corp Configurator made by Kieren
#
fwrite(STDOUT, "Please enter Uplink Subnet with Mask:\n"); 

// Read the input 
$UplinkIPAndMask = fgets(STDIN); 
$UplinkIPAndMask = trim($UplinkIPAndMask);


fwrite(STDOUT, "Please enter LAN Subnet with Mask:\n"); 

// Read the input 
$LANIPAndMask = fgets(STDIN); 
$LANIPAndMask = trim($LANIPAndMask);

fwrite(STDOUT, "Uplink VLAN?\n"); 

// Read the input 
$UplinkVLAN = fgets(STDIN); 
$UplinkVLAN = trim($UplinkVLAN);

fwrite(STDOUT, "Site Name and City?\n"); 

// Read the input 
$SiteName = fgets(STDIN); 
$SiteName = trim($SiteName);

fwrite(STDOUT, "Cisco (1) or Ruckus (2)?\n"); 

// Read the input 
$Vendor = fgets(STDIN); 
$Vendor = trim($Vendor);


        $SplitCIDR = SplitCIDR($UplinkIPAndMask);
        $UplinkSubnetID = $SplitCIDR[0];
        $UplinkCIDRmask = $SplitCIDR[1];

        $SplitCIDR = SplitCIDR($LANIPAndMask);
        $LANSubnetID = $SplitCIDR[0];
        $LANCIDRmask = $SplitCIDR[1];

if (filter_var($UplinkSubnetID, FILTER_VALIDATE_IP)) {
} else {
    echo("\n");
    echo("$UplinkSubnetID is not a valid IP address\n");
    echo("\n");
        fwrite(STDOUT, "Please enter Uplink Subnet with Mask:\n");
        $UplinkIPAndMask = fgets(STDIN); 
        $UplinkIPAndMask = trim($UplinkIPAndMask);
}

if (filter_var($LANSubnetID, FILTER_VALIDATE_IP)) {
} else {
    echo("\n");
    echo("$LANSubnetID is not a valid IP address\n");
    echo("\n");
        fwrite(STDOUT, "Please enter LAN Subnet with Mask:\n");
        $LANIPAndMask = fgets(STDIN); 
        $LANIPAndMask = trim($LANIPAndMask);;
}

    //Fx to convert CIDR to Netmask
    function CIDRtoMask($Int) {
            return Long2IP(-1 << (32 - (Int)$Int));
    }
    //fx to split CIDR eg 5.151.4.1/24 splits at the /
    function SplitCIDR($string){
            return explode("/", $string);

    }

        //turn the IP Into a long subtype so we can add to it.
    $UplinkLongIP = IP2Long($UplinkSubnetID);
    $UplinkLongIP++;
    $UplinkIPPlus1 = Long2IP($UplinkLongIP);
        //take the CIDR notfication and convert it Into a Netmask
    $UplinkNetmask = CIDRtoMask($UplinkCIDRmask);
    // a really stupid method to add 2 to long ip plus 1 to get the IP of the core switch
    $UplinkLongUplinkIPPlus3 = $UplinkLongIP;
    $UplinkLongUplinkIPPlus3++;
        $UplinkIPPlus2 = Long2IP($UplinkLongUplinkIPPlus3);
    $UplinkLongUplinkIPPlus3++;
    $UplinkIPPlus3 = Long2IP($UplinkLongUplinkIPPlus3);

        //turn the IP Into a long subtype so we can add to it.
    $LANLongIP = IP2Long($LANSubnetID);
    $LANLongIP++;
    $LANIPPlus1 = Long2IP($LANLongIP);
        //take the CIDR notfication and convert it Into a Netmask
    $LANNetmask = CIDRtoMask($LANCIDRmask);
    // a really stupid method to add 2 to long ip plus 1 to get the IP of the core switch
    $LANLongLANIPPlus3 = $LANLongIP;
    $LANLongLANIPPlus3++;
        $LANIPPlus2 = Long2IP($LANLongLANIPPlus3);
    $LANLongLANIPPlus3++;
    $LANIPPlus3 = Long2IP($LANLongLANIPPlus3);

$Ruckus = <<<EOF

Ruckus Core Switch Config:
conf t
!
system-max ip-route-default-vrf 10000
!
vlan 249 name OfficeVlan
router-interface ve 249
!
vlan $UplinkVLAN name UniteWAN
 router-interface ve 2687
!
vrf UniteWAN
 rd 1:1
 address-family ipv4 max-route 128
 exit-address-family
!
ip dhcp-server pool Office_Data
 network $LANSubnetID $LANNetmask
 domain-name unite-group.co.uk
 option 3 ip $LANIPPlus1
 option 6 ip 172.18.242.1 172.16.101.1 
 lease 30
 deploy
!
router ospf vrf UniteWAN
area 0.0.0.0
!
int ve $UplinkVLAN
 port-name UniteWAN
 vrf forwarding UniteWAN
 ip address $UplinkIPPlus3 $UplinkNetmask
 ip osfp mtu-ignore
 ip ospf area 0.0.0.0
 ip ospf active
!
interface ve 249 
 port-name OfficeVLAN
 vrf forwarding UniteWAN
 ip address $LANIPPlus1 $LANNetmask
 ip ospf area 0.0.0.0
 ip ospf active
!
end
!
wr mem
!

cr-01.ixn.unite
conf t
!
interface GigabitEthernet0/0/1.$UplinkVLAN
 description $SiteName
 encapsulation dot1Q $UplinkVLAN
 ip vrf forwarding UniteWAN
 ip address $UplinkIPPlus1 $UplinkNetmask
 ip mtu 1500
 no cdp enable
 service-policy output POLICY_20MB
!
end
!
wr

cr-02.ixn.unite
conf t
!
interface GigabitEthernet0/0/1.$UplinkVLAN
 description $SiteName
 encapsulation dot1Q $UplinkVLAN
 ip vrf forwarding UniteWAN
 ip address $UplinkIPPlus2 $UplinkNetmask
 ip mtu 1500
 no cdp enable
 service-policy output POLICY_20MB
!
end
!
wr

EOF;

$Cisco = <<<EOF

Cisco Core Switch Config:
conf t
!
vlan 249
 name OfficeVlan
!
ip vrf UniteWAN
!
ip dhcp pool Office_Data
 vrf UniteWAN
 network $LANSubnetID $LANNetmask
 default-router $LANIPPlus1
 domain-name unite-group.co.uk
 dns-server 172.18.242.1 172.16.101.1                  
 lease 30
!
interface Vlan$UplinkVLAN
 ip vrf forwarding UniteWAN
 ip address $UplinkIPPlus3 $UplinkNetmask
 ip ospf mtu-ignore
 no shut
!
router ospf 2 vrf UniteWAN
 passive-interface default
 no passive-interface Vlan$UplinkVLAN
 network 10.30.0.0 0.0.255.255 area 0.0.0.0
 network 172.31.0.0 0.0.255.255 area 0.0.0.0 
!
interface Vlan249
 description Office VLAN
 ip vrf forwarding UniteWAN
 ip address $LANIPPlus1 $LANNetmask
 no shut
!
end
!
wr
!

cr-01.ixn.unite
conf t
!
interface GigabitEthernet0/0/1.$UplinkVLAN
 description $SiteName
 encapsulation dot1Q $UplinkVLAN
 ip vrf forwarding UniteWAN
 ip address $UplinkIPPlus1 $UplinkNetmask
 ip mtu 1500
 no cdp enable
 service-policy output POLICY_20MB
!
end
!
wr

cr-02.ixn.unite
conf t
!
interface GigabitEthernet0/0/1.$UplinkVLAN
 description $SiteName
 encapsulation dot1Q $UplinkVLAN
 ip vrf forwarding UniteWAN
 ip address $UplinkIPPlus2 $UplinkNetmask
 ip mtu 1500
 no cdp enable
 service-policy output POLICY_20MB
!
end
!
wr

EOF;

if ($Vendor == 1) {
    echo $Cisco;
}
elseif ($Vendor == 2) {
    echo $Ruckus;
}
else {
    echo "Invalid Input! Ask Tim how you broke me...\n";
}

?>