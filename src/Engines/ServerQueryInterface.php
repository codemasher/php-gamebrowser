<?php
/**
 * Interface ServerQueryInterface
 *
 * @filesource   ServerQueryInterface.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowser\Engines
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Engines;

interface ServerQueryInterface{

	const ERROR_NONE                    = 0x0;
	const ERROR_MISC                    = 0x1;
	const ERROR_INVALID_SERVER_RESPONSE = 0x2;
	const ERROR_INVALID_MASTER_RESPONSE = 0x4;
	const ERROR_INVALID_INFO_RESPONSE   = 0x8;
	const ERROR_INVALID_STATUS_RESPONSE = 0x10;
	const ERROR_INVALID_GAME_PROTOCOL   = 0x20;


	/**
	 * @param string $host
	 * @param int    $port
	 * @param string $query
	 *
	 * @return string
	 */
	public function query(string $host, int $port, string $query):string;

	/**
	 * @return array
	 */
	public function getServers():array;

	/**
	 * @param string $ip
	 * @param int    $port
	 *
	 * @return array
	 */
	public function getInfo(string $ip, int $port):array;

	/**
	 * @param string $ip
	 * @param int    $port
	 *
	 * @return array
	 */
	public function getStatus(string $ip, int $port):array;

	/**
	 * @return string
	 */
	public function getGameName():string;
}
