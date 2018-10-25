<?php
/**
 * Class ServerQueryOptions
 *
 * @filesource   ServerQueryOptions.php
 * @created      21.10.2018
 * @package      chillerlan\GameBrowser
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser;

use chillerlan\Settings\SettingsContainerAbstract;

/**
 * @property int    $socketTimeout
 * @property string $masterHost
 * @property int    $masterPort
 * @property string $masterQuery
 */
class ServerQueryOptions extends SettingsContainerAbstract{
	use ServerQueryOptionsTrait;
}
