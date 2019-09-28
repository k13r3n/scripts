<?php
	//Take an ipalloc file and turn it into asr config
	
	//Vars
	$filename = $_SERVER["argv"][1];
	$ipalloc = null; // my ipalloc file
	$needle = null; // a search term
	$uplink_vlan; // the vlan of my uplink
	$natvlans;  //an arry of all my nat vlans
	$pieces = null;
	// import file and split it into lines
	
	$ipalloc = file($filename); 
 
	//fx to search for string
	function search($test)	{
	   global $needle;
		if(strpos($test, $needle) !== false){
			return true;
		}
	}
		//fx to search IPalloc then explode 
	function my_search($searchterm, $ipalloc)	{
		$needle = $searchterm;
		if(array_filter($ipalloc, 'search') === false) {
		echo "Shit guys fire the misslile \n";
		}
		$output = array_filter($ipalloc, 'search');
		reset ($output);
		$output = preg_split("/[\s,]+/", current($output));
		//$output = explode(" ", current($output));
		return $output;
	}
	//Fx to convert CIDR to netmask
	function CIDRtoMask($int) {
    		return long2ip(-1 << (32 - (int)$int));
	}
	//fx to split CIDR eg 5.151.4.1/24 splits at the / 
	function splitCIDR($string){
		return explode("/", $string);
		 
	}
	//fx to give the subnet id and broadcast adress from a ip formatted like 1.1.1.1/29 returns an array 1st is subnet and pos 1 is broadcast
	function cidrToRange($cidr) {
		$range = array();
		$cidr = explode('/', $cidr);
		$range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
		$range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
		return $range;
}

	//return the element from the array that contains uplink
	$needle = 'Uplink';
	$pieces = my_search($needle, $ipalloc);

	$needle = null;	
	$needle = 'VLAN' . $pieces[1];
	$needle = rtrim($needle);
	//find the vlanUplink line and get the ip into uplink vlan
	$uplink_vlan = my_search($needle, $ipalloc);
	$uplink_vlan = array_map('trim', $uplink_vlan);
	
	//explode the ip/CIDR
	$uplinkSNID = splitCIDR($uplink_vlan[1]);

	$longip = ip2long(rtrim($uplinkSNID[0]));
	$longip++;
	$IPplus1 = long2ip($longip);
	$netmask = CIDRtoMask($uplinkSNID[1]);
	// a really stupid method to add 2 to long ip plus 1 to get the IP of the core switch
	$longipplus3 = $longip;
	$longipplus3++;
	$longipplus3++;
	$IPplus3 = long2ip($longipplus3);
	
	//here i'm going to reverse my array to find the uplink vlan because i'm using strpos() and that just finds the first one.
	$ripalloc = array_reverse($ipalloc);
	$needle = null;	
	$needle = 'VLAN' .trim($pieces[1]);
	$needle = trim($needle);
	//$uplink_vlanipv6 now contains 0 => vlanXXXX 1=> IP/CIDR
	$uplink_vlanipv6 = my_search($needle, $ripalloc);
	//uplinkipv6_3rd will be the 3rd section of an ipv6 address
	$uplinkipv6_3rd = explode(":",$uplink_vlanipv6[1]);
	$sitename = explode(".", $uplink_vlan[4]);

	
	//find the speed line and get the speed a 2 part arry, speed is in postion [1]
	$needle = null;	
	$needle = 'speed';	
	$speed = my_search($needle, $ipalloc);
	
	//this gets the nat pool range returns a 2 part array, part 0 is subnet id part 1 is broadcast
    	$needle = null;	
	$needle = 'NATPOOL';
	$natCIDR = my_search($needle, $ipalloc);
	$nat = cidrToRange($natCIDR[1]);
	//now just get the subnet id (natCIDR1[0]) and subnet mask (natnetmask)
	$natCIDR1 = splitCIDR($natCIDR[1]);
	$natnetmask = CIDRtoMask($natCIDR1[1]);

	
	
	
//uplink IP range is $uplink_vlan[1]
//ip plus 1 is $IPplus1
	
	//$wcmask=long2ip( ~ip2long($mask) );
	//$bcast=long2ip( ip2long($ip) | ip2long($wcmask) );

	echo "conf t\n";	
	echo "!\n";
	echo "interface Port-channel3.".$pieces[1]."\n";
	echo "description to ".rtrim($uplink_vlan[4])."\n";
	echo "encapsulation dot1Q ".$pieces[1]."\n";
	echo "ip address ".$IPplus1." ".$netmask."\n";
	echo "ip mtu 1500\n";
	echo "ip nat inside\n";
	echo "ip pim sparse-mode\n";
	echo "ip flow monitor CCNetFlow sampler 1in100 input\n";
	echo "ip flow monitor CCNetFlow sampler 1in100 output\n";
	echo "ip access-group ipv6-tunnel-block in\n";
	echo "ipv6 address 2A01:388:".$uplinkipv6_3rd[2].":F000::1/64\n";
	echo "ipv6 enable\n";
	echo "ipv6 mtu 1500\n";
	echo "service-policy output POLICY_".rtrim($speed[1])."MB\n";
	echo "!\n";
	echo "router bgp 64512\n";
	echo "neighbor ".$IPplus3." peer-group customer\n";
	echo "neighbor ".$IPplus3." description ".rtrim(ucfirst($sitename[3])).": ".ucfirst($sitename[1])."\n";
	echo "neighbor 2A01:388:".$uplinkipv6_3rd[2].":F000::3 peer-group customer6\n";
	echo "neighbor 2A01:388:".$uplinkipv6_3rd[2].":F000::3 description ".rtrim(ucfirst($sitename[3])).": ".ucfirst($sitename[1])."\n";
	echo "!\n";
	echo "address-family ipv4\n";
	echo "neighbor ".$IPplus3." activate\n";
	echo "no neighbor 2A01:388:".$uplinkipv6_3rd[2].":F000::3 activate\n";
	echo "!\n";
	echo "address-family ipv6\n";
	echo "neighbor 2A01:388:".$uplinkipv6_3rd[2].":F000::3 activate\n";
	echo "!\n";
	
	echo "ip access-list extended ".$sitename[1]."-".$sitename[2]."-".rtrim($sitename[3])."\n";	
	#var_dump($ipalloc);
	//a foreach loop that looks through our file to find 100. IPs and echo their permit statements
	foreach ($ipalloc as $item){
		$value = preg_replace('/\t+|\s+/', "!", $item);
#		print_r($value);
		if(strpos(trim($value), '!100.') !== false){
			if(strpos($value, 'VLAN6') === false){
			$one = explode("!", ($value));
#			print_r($one);
			$one = array_map('trim', $one);
#			print_r($one);
			$two = splitCIDR($one[1]);
			$three = CIDRtoMask($two[1]);
			$wcmask =long2ip( ~ip2long($three) );
			echo "permit ip ".$two[0]." ".$wcmask." any\n";
			}
		}
		else {}
        }
		
	unset($value);
	echo "!\n";
	echo "ip nat inside source list ".$sitename[1]."-".$sitename[2]."-".rtrim($sitename[3])." pool ".$sitename[1]."-".$sitename[2]."-".rtrim($sitename[3])."-pool-1 overload\n";
	echo "!\n";
	echo "ip nat pool ".$sitename[1]."-".$sitename[2]."-".rtrim($sitename[3])."-pool-1 ".$nat[0]." ".$nat[1]." prefix-length ".rtrim($natCIDR1[1])."\n";
	echo "!\n";
	echo "ip route ".$natCIDR1[0]." ".$natnetmask." Null0\n";
	echo "!\n";
	echo "router bgp 64512\n";
	echo "address-family ipv4\n";
	echo "network ".$natCIDR1[0]." mask ".$natnetmask."\n";
	echo "!\n";	
	echo "end\n";
	echo "!\n";
   
?>
