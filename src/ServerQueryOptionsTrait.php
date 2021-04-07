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
	 */
	protected int $socketTimeout = 1;

	/**
	 * the master server IP or hostname
	 */
	protected ?string $masterHost = null;

	/**
	 * the master server port
	 */
	protected ?int $masterPort = null;

	/**
	 * the master query string.
	 *
	 * - idtech3: a %s represents the protocol string, e.g. "getservers %s full empty"
	 */
	protected ?string $masterQuery = null;

}
