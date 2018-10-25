<?php
/**
 * Trait ServerQueryOptionsTrait
 *
 * @filesource   ${FILE_NAME}
 * @created      21.10.2018
 * @package      chillerlan\GameBrowser
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser;

trait ServerQueryOptionsTrait{

	/**
	 * the socket timeout
	 *
	 * @var int
	 */
	protected $socketTimeout;

	/**
	 * the master server IP or hostname
	 *
	 * @var string
	 */
	protected $masterHost;

	/**
	 * the master server port
	 *
	 * @var int
	 */
	protected $masterPort;

	/**
	 * the master query string.
	 *
	 * - idtech3: a %s represents the protocol string, e.g. "getservers %s full empty"
	 *
	 * @var string
	 */
	protected $masterQuery;

}
