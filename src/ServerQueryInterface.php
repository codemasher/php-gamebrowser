<?php
/**
 * Interface ServerQueryInterface
 *
 * @filesource   ServerQueryInterface.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowser
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser;

/**
 */
interface ServerQueryInterface{

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

}
