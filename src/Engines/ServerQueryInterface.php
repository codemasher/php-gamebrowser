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
	 * @throws \RuntimeException - if fsockopen() fails
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function query(string $host, int $port, string $query):string;

	/**
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getServers():array;

	/**
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getInfo(string $ip, int $port):array;

	/**
	 * @throws \chillerlan\GameBrowser\ServerQueryException
	 */
	public function getStatus(string $ip, int $port):array;

	/**
	 *
	 */
	public function getGameName():string;
}
