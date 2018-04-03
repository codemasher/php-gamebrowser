<?php
/**
 * Class idtech3Test
 *
 * @filesource   idtech3Test.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowserTest\Protocols
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowserTest\Protocols;

use chillerlan\GameBrowser\Protocols\idtech3;
use chillerlan\GameBrowserTest\QueryTestAbstract;

class idtech3Test extends QueryTestAbstract{

	protected $FQCN = idtech3::class;

	protected const masterResponse = 'ffffffff67657473657276657273526573706f6e73655c5fae81096d385cbc44f9896d375c6c3dfdbb6d385c44e8aea86d385cc100ce416d385c53aa46656d385c4ef91d186d385cc37a868c6d385cd42de6036d385c2e93ba406d385cbc44f9896d385c5fbdd5fa6d385c53aa46656d3a5c6d6fb1896d385c454f54';
	protected const infoResponse   = '67616d655c667265657a655c70756e6b6275737465725c305c707572655c315c67616d65747970655c335c73765f6d6178636c69656e74735c385c636c69656e74735c305c6d61706e616d655c7a746e33646d315c686f73746e616d655c546865444f47205b315d20467265657a655c70726f746f636f6c5c3638';

	public function testParseMasterResponse(){
		$list = $this->getMethod('parseMasterResponse')->invokeArgs(new class extends idtech3{}, [hex2bin($this::masterResponse)]);

		$this->assertCount(14, $list);

		[$ip, $port] = $list[0];
		$this->assertSame('95.174.129.9', $ip);
		$this->assertSame(27960, $port);

		[$ip, $port] = $list[13];
		$this->assertSame('109.111.177.137', $ip);
		$this->assertSame(27960, $port);
	}

	public function testParseCvars(){
		$list = $this->getMethod('parseCvars')->invokeArgs(new class extends idtech3{}, [hex2bin($this::infoResponse)]);

		$expected = [
			'game'          => 'freeze',
			'punkbuster'    => '0',
			'pure'          => '1',
			'gametype'      => '3',
			'sv_maxclients' => '8',
			'clients'       => '0',
			'mapname'       => 'ztn3dm1',
			'hostname'      => 'TheDOG [1] Freeze',
			'protocol'      => '68',
		];

		$this->assertSame($expected, $list);
	}

}
