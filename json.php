<?
//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL);
header('Content-type: text/plain');

$line = exec('/sbin/ifconfig',$out);

$devices = array(0 => "");
$devno = 0;

foreach($out as $l)
{
	if(strlen($l)==0)
	{
		$devices[$devno] = implode("\n", $devices[$devno]);
		$devno++;
	}
	else
		$devices[$devno][] = $l;
}

foreach($devices as &$d)
{
	//var_dump($d);
	//preg_match_all("/  [[:alnum:]]+( [[:alnum:]]+)*\:/", $d, $matches);
	preg_match_all("/^([ethnlo]{2,3}[0-9]{0,})|Link encap:([a-zA-Z ]+)\s{2}|HWaddr ([a-f0-9:]{17})|inet addr:([0-9\.]{7,15})|inet6 addr: ([0-9a-f:\/]+)|[T|R]X bytes:([0-9]+)/", $d, $matches);
	//var_dump($matches);
	$result = array();
	$result['dev'] = implode("", $matches[1]);
	$result['name'] = implode("", $matches[2]);
	$result['hwaddr'] = implode("", $matches[3]);
	$result['ipv4'] = implode("", $matches[4]);
	$result['ipv6'] = implode("", $matches[5]);
	$result['rx'] = $matches[6][count($matches[6])-2];
	$result['tx'] = $matches[6][count($matches[6])-1];
	$d = $result;
}

echo json_encode($devices);

?>
