<?php
/**
 * Class idtech3
 *
 * @filesource   idtech3.php
 * @created      11.03.2018
 * @package      chillerlan\GameBrowser\Engines
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Engines;

use chillerlan\GameBrowser\{ServerQueryException};
use chillerlan\Settings\SettingsContainerInterface;

/**
 * @link http://caia.swin.edu.au/reports/070730A/CAIA-TR-070730A.pdf
 */
abstract class idtech3 extends ServerQueryAbstract{

	/**
	 * @var string
	 */
	protected $masterQuery;

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
	 * idtech3 constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface|null $options
	 */
	public function __construct(SettingsContainerInterface $options = null){
		parent::__construct($options);

		$this->masterHost  = $this->options->masterHost ?? 'master.quake3arena.com';
		$this->masterPort  = $this->options->masterPort ?? 27950;
		$this->masterQuery = $this->options->masterQuery ?? "\xff\xff\xff\xffgetservers %s full empty";
	}


	/**
	 * @param string|null $protocol
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getServers(string $protocol = null):array{

		if($protocol !== null){

			if(!in_array($protocol, $this->protocols, true)){
				throw new ServerQueryException('invalid protocol', $this::ERROR_INVALID_GAME_PROTOCOL);
			}

			// return the list for a given protocol
			$response = $this->query($this->masterHost, $this->masterPort, sprintf($this->masterQuery, $protocol));

			return $this->parseMasterResponse($response);
		}

		$servers = [];

		// list servers for all available protocols otherwise
		foreach($this->protocols as $version => $p){
			$response = $this->query($this->masterHost, $this->masterPort, sprintf($this->masterQuery, $p));

			$servers[$version] = $this->parseMasterResponse($response);
		}

		return $servers;
	}

	/**
	 * @param string $ip
	 * @param int    $port
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getInfo(string $ip, int $port):array{
		$response = $this->query($ip, $port, "\xff\xff\xff\xffgetinfo");

		// cut off "ÿÿÿÿinfoResponse\n\\" so that the string consists of key => value pairs
		$response = substr(trim($response), 18);

		if(strlen($response) < 1){
			throw new ServerQueryException('invalid info response', $this::ERROR_INVALID_INFO_RESPONSE);
		}

		return $this->parseCvars($response);
	}

	/**
	 * @param string $ip
	 * @param int    $port
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getStatus(string $ip, int $port):array{
		$response = $this->query($ip, $port, "\xff\xff\xff\xffgetstatus");

		// cut off "ÿÿÿÿstatusResponse\n\\"
		$response = substr(trim($response), 20);
		$response = explode("\n", $response);

		if(count($response) < 1){
			throw new ServerQueryException('invalid status response', $this::ERROR_INVALID_STATUS_RESPONSE);
		}

		// the first element is the cvar list, the remaining is the player list
		$cvars = $this->parseCvars(array_shift($response));

		return [
			'info'    => $cvars,
			// the cvars may contain additional player info
			'players' => $this->parsePlayers($response, $cvars),
		];
	}

	/**
	 * @param string $response
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	protected function parseMasterResponse(string $response):array{
		$data = explode('\\', $response);

		if(count($data) < 2){
			throw new ServerQueryException('invalid master response', $this::ERROR_INVALID_MASTER_RESPONSE);
		}

		$response = [];

		foreach($data as $str){

			// "EOT   "
			if($str === "\x45\x4f\x54\x00\x00\x00"){
				break;
			}

			// either "ÿÿÿÿgetserversResponse" or garbage data
			if(strlen($str) !== 6){
				continue;
			}

			// unpack the string into a 5-element array
			$ipport = unpack('C4/nint', $str);
			// the last element contains the port
			$port   = array_pop($ipport);
			// the remaining 4 elements represent the ip
			$ip     = implode('.', $ipport);

			if($ip === '0.0.0.0' || $port === 0){
				continue;
			}

			$response[] = [$ip, $port];
		}

		return $response;
	}

	/**
	 * @param string $response
	 *
	 * @return array
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	protected function parseCvars(string $response):array{
		$response = explode('\\', $response);
		$count    = count($response);

		if($count < 2 || $count % 2 !== 0){
			throw new ServerQueryException('invalid cvar list', $this::ERROR_MISC);
		}

		$chunk = array_chunk($response, 2); // chunk the response into key/value pairs
		$data  = array_combine(array_column($chunk, 0), array_column($chunk, 1));

		if($data === false){
			return [];
		}

		return $data;
	}

	/**
	 * @param array $playerlist
	 * @param array $cvars
	 *
	 * @return array
	 */
	protected function parsePlayers(array $playerlist, array $cvars = null):array{
		$players = [];

		foreach($playerlist as $i => $player){
			$player = explode(' ', $player, 3);

			$players[$i] = [
				'name'  => trim($player[2], '"'), // strip delimiter double quotes from the name
				'score' => (int)$player[0],
				'ping'  => (int)$player[1],
			];
		}

		return $players;
	}

}
