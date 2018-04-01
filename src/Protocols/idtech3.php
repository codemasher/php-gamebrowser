<?php
/**
 * Class idtech3
 *
 * @filesource   idtech3.php
 * @created      11.03.2018
 * @package      chillerlan\GameBrowser\Protocols
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Protocols;

use chillerlan\GameBrowser\ServerQueryAbstract;
use chillerlan\GameBrowser\ServerQueryException;

/**
 * http://caia.swin.edu.au/reports/070730A/CAIA-TR-070730A.pdf
 */
abstract class idtech3 extends ServerQueryAbstract{

	/**
	 * @var string
	 */
	protected $queryheader = "\xff\xff\xff\xff";

	/**
	 * @var string
	 */
	protected $masterHost;

	/**
	 * @var int
	 */
	protected $masterPort;

	/**
	 * @var array
	 */
	protected $protocols;

	/**
	 * @param string $host
	 * @param int    $port
	 * @param string $query
	 *
	 * @return string
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	protected function query(string $host, int $port, string $query):string {
		$fp = fsockopen('udp://'.$host, $port, $errno, $errstr, 1);

		if(!$fp){
			throw new ServerQueryException($errstr);
		}

		fwrite($fp, $this->queryheader.$query);
		socket_set_timeout($fp, 1);

		$response     = fread($fp, strlen($this->queryheader));
		$unread_bytes = socket_get_status($fp)['unread_bytes'] ?? 0;

		if($unread_bytes > 0){
			$response .= fread($fp, $unread_bytes);
		}

		fclose($fp);

		$response = str_replace([$this->queryheader, "infoResponse\n\\", "statusResponse\n\\"], '', $response);

		return $response;
	}

	/**
	 * @return array
	 */
	public function getServers():array {
		$servers = [];

		foreach($this->protocols as $version => $protocol){
			$response = $this->query($this->masterHost, $this->masterPort, 'getservers '.$protocol.' full empty');

			$servers[$version] = $this->parseMasterResponse($response);
		}

		return $servers;
	}

	/**
	 * @param string $ip
	 * @param int    $port
	 *
	 * @return array
	 */
	public function getInfo(string $ip, int $port):array {
		$response = $this->query($ip, $port, 'getinfo');

		return $this->parseCvars($response);
	}

	/**
	 * @param string $ip
	 * @param int    $port
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getStatus(string $ip, int $port):array {
		$response = $this->query($ip, $port, 'getstatus');
		$response = explode("\n", $response); // explode on the newline (LF) character

		if(count($response) < 2){
			throw new ServerQueryException('invalid status response');
		}

		return $this->parseStatus($response);
	}

	/**
	 * @param string $response
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	protected function parseMasterResponse(string $response):array {
		$data = explode("\\", $response); // explode the response on the backslash

		if(count($data) < 2){
			throw new ServerQueryException('invalid master response');
		}

		$response = [];

		foreach($data as $str){

			if(strlen($str) !== 6 || strpos($str, 'EOT') === 0){
				continue;
			}

			$str  = unpack('C4/nint', $str); // unpack the string into a 5-element array
			$port = array_pop($str); // the last element contains the port

			$response[] = [
				'ip'   => implode('.', $str), // the remaining 4 elements represent the ip
				'port' => $port,
			];

		}

		return $response;
	}

	/**
	 * @param string $response
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	protected function parseCvars(string $response):array {
		$response = explode('\\', $response);

		if(count($response) < 2){
			throw new ServerQueryException('invalid cvar list');
		}

		$response = array_chunk($response, 2); // chunk the rest into key/value pairs

		return array_combine(array_column($response, 0), array_column($response, 1));
	}

	/**
	 * @param array $response
	 *
	 * @return array
	 */
	protected function parseStatus(array $response):array {
		$cvars = $this->parseCvars(array_shift($response)); // the next element is the cvar list

		array_pop($response); // remove leftover whitespace from the end, the remaining part is the playerlist

		return [
			'info'    => $cvars,
			'players' => $this->parsePlayers($response, $cvars), // the cvars may contain additional player info
		];
	}

	/**
	 * @param array $playerlist
	 * @param array $cvars
	 *
	 * @return array
	 */
	protected function parsePlayers(array $playerlist, array $cvars):array {
		$players = [];

		foreach($playerlist as $i => $player){
			$player    = explode(' ', $player);
			$player[2] = trim($player[2], '"'); // strip double quotes from the name

			$players[$i] = array_combine(['score', 'ping', 'name'], $player);
		}

		return $players;
	}

}
