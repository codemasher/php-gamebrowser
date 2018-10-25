<?php
/**
 * Class WoP
 *
 * @filesource   WoP.php
 * @created      31.03.2018
 * @package      chillerlan\GameBrowser\Games
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Games;

use chillerlan\GameBrowser\Engines\idtech3;

class WoP extends idtech3{

	protected $masterHost = 'master.worldofpadman.com';
	protected $masterPort = 27955;
	protected $protocols  = [
		// there's no reliable way to tell 1.1 and 1.2 apart... ty for not listening to me. ~smiley
		// the "version" cvar seems to be the best bet, but it differs between OS
		'1.2' => '68',
		// 1.5 and 1.5.1
		'1.5' => '69',
		// 1.5.2+ actually - what a mess...
		'1.6' => 'WorldofPadman 71',
	];

	/**
	 * @inheritdoc
	 *
	 * teams:
	 *   0 -> free
	 *   1 -> red
	 *   2 -> blue
	 *   3 -> spectator
	 */
	protected function parsePlayers(array $playerlist, array $cvars = null):array {
		$protocol = (int)($cvars['protocol'] ?? $cvars['com_protocol'] ?? 0);

		// wop 1.5 and 1.5.1, 5-element player string: [score, ping, team, is_bot, name]
		// wop 1.5.2+, default q3a player string [score, ping, name], team/is_bot via cvar
		$explodeLimit = $protocol === 69 ? 5 : 3;

		if($protocol === 71){
			foreach(['Players_Team', 'Players_Bot'] as $k){
				$cvars[$k] = explode(' ', trim($cvars[$k]));
			}
		}

		$players = [];

		foreach($playerlist as $i => $p){
			$p = explode(' ', $p, $explodeLimit);

			$player = [
				'name'   => $p[2],
				'score'  => (int)$p[0],
				'ping'   => (int)$p[1],
				'team'   => null,
				'is_bot' => null
			];

			// assign team and bot indicators
			// wop 1.5 and 1.5.1
			if($protocol === 69){
				$player['team']   = (int)$p[2];
				$player['is_bot'] = (bool)$p[3];
				$player['name']   = $p[4];
			}
			// wop 1.5.2+
			elseif($protocol === 71){
				$player['team']   = (int)$cvars['Players_Team'][$i];
				$player['is_bot'] = (bool)$cvars['Players_Bot'][$i];
			}

			// strip double quotes (delimiters) from the name
			$player['name'] = trim($player['name'], '"');

			$players[$i] = $player;
		}

		return $players;
	}

}
