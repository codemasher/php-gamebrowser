<?php
/**
 * Class ServerQueryAbstract
 *
 * @filesource   ServerQueryAbstract.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowser
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser;

use ReflectionClass;

/**
 */
abstract class ServerQueryAbstract implements ServerQueryInterface{

	/**
	 * @return string
	 */
	public function getGameName():string{
		return (new ReflectionClass($this))->getShortName();
	}

}
