<?php
/**
 * @author      Patrick Kleinschmidt (NoxNebula) <noxifoxi@gmail.com>
 * @copyright   2013 Patrick Kleinschmidt
 * @license     GPL version 3 <http://www.gnu.org/licenses/gpl-3.0.html>
 */

/*
	This piece of code I just wrote can - I don't know why and I wrote it - calculate and compare Subnetmasks with IPs.
	It's maybe a bit buggy, netherless all my tests passed.
*/
class Subnet {
	// I can calculate IPv4 Subnets
	private $Subnets;

	public function __construct($Subnets) {
		foreach ((array)$Subnets as $Subnet) {
			if (strpos($Subnet, '/') === false)
				continue;

			$Buf = explode('/', $Subnet);

			$this->Subnets[] = [
				'subnet' => $Buf[0],
				'prefix' => $Buf[1],
				'bits'   => 32 - $Buf[1],
				'ips'    => pow(2, 32 - $Buf[1])
			];
		}
	}

	public function Compare($IP) {
		$Match = [-1, -1];

		$aIP = explode('.', $IP);
		for ($s = 0; $s < sizeof($this->Subnets); $s++) {
			$aSubnet = explode('.', $this->Subnets[$s]['subnet']);
			for ($i = 0; $i < 4 - (int)($this->Subnets[$s]['bits'] / 8); $i++) {
				if ($aIP[$i] == $aSubnet[$i] && $Match[0] < $i) {
					$Match = [$i, $s];
				} else {
					continue 2;
				}
			}
		}

		if($Match[1] == -1 || $Match[0] < 3 - ((int)($this->Subnets[$Match[1]]['bits'] / 8) + 1))
			return false;

		//print_r($Match);
		//print_r($this->Subnets[$Match[1]]);

		return $this->Check($IP, $this->Subnets[$Match[1]]);
	}

	private function Check($IP, $Subnet) {
		$aIP = explode('.', $IP);
		$aS = explode('.', $Subnet['subnet']);

		$Match = [$aS[0], $aS[1], $aS[2], $aS[3]];

		for ($i = 3; $i >= 3 - ((int)($Subnet['bits'] / 8)); $i--) {
			for ($j = $aS[$i], $jj = 0; $jj < $Subnet['ips'] && $j < 256; $j++, $jj++) {
				$Match[$i] = $j;
				if ($IP == $Match[0].'.'.$Match[1].'.'.$Match[2].'.'.$Match[3]) {
					//echo 'found: '.$IP.' = '.$Match[0].'.'.$Match[1].'.'.$Match[2].'.'.$Match[3];
					return true;
				}
				if ($aIP[$i] == $j) {
					$Subnet['ips'] -= 256;
					break;
				}
			}
		}
		return false;
	}
}
