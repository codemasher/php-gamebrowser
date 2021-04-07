<?php
/**
 * Class Tremulous
 *
 * @filesource   Tremulous.php
 * @created      24.10.2018
 * @package      chillerlan\GameBrowser\Games
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Games;

use chillerlan\GameBrowser\Engines\idtech3;

/**
 * @link http://tremulous.net/forum/index.php?topic=9363.msg143723#msg143723
 */
class Tremulous extends idtech3{

	protected string $masterHost = 'master.tremulous.net';
	protected int    $masterPort = 30710;
	protected array  $protocols  = [
		'1.1' => '69',
		'1.2' => '70',
	];

	/**
	 * @link http://tremulous.net/forum/index.php?topic=8750.msg137461#msg137461
	 *
	 * for each slot P will contain a one of:
	 *  - for an empty slot
	 *  0 for a spectator
	 *  1 for an alien
	 *  2 for a human
	 *
	 * the first character corresponds to slot 0, the second to slot 1, etc.
	 * the playerlist you get from the server will be in the same order,
	 * but it won't contain an empty spot if there is no player in that slot.
	 * the playerlist also doesn't include players in private slots,
	 * so you'll have to skip the first sv_privateclients characters of P.
	 */
	protected function parsePlayers(array $playerlist, array $cvars = null):array{
		$players = [];
		$teams   = isset($cvars['P']) ? str_split(str_replace('-', '', $cvars['P'])) : [];

		foreach($playerlist as $i => $player){
			$player = explode(' ', $player, 3);

			$players[$i] = [
				'name'  => trim($player[2], '"'), // strip delimiter double quotes from the name
				'score' => (int)$player[0],
				'ping'  => (int)$player[1],
				'team'  => isset($teams[$i]) ? (int)$teams[$i] : null,
			];
		}

		return $players;
	}

}
