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

use chillerlan\GameBrowser\Games\Q3A;
use chillerlan\GameBrowser\Engines\idtech3;
use chillerlan\GameBrowserTest\QueryTestAbstract;

class idtech3Test extends QueryTestAbstract{

	protected string $FQCN = idtech3::class;

	protected string $masterResponse = 'ffffffff67657473657276657273526573706f6e73655c5fae81096d385cbc44f9896d375c6c3dfdbb6d385c44e8aea86d385cc100ce416d385c53aa46656d385c4ef91d186d385cc37a868c6d385cd42de6036d385c2e93ba406d385cbc44f9896d385c5fbdd5fa6d385c53aa46656d3a5c6d6fb1896d385c454f54';
	protected string $infoResponse   = '67616d655c667265657a655c70756e6b6275737465725c305c707572655c315c67616d65747970655c335c73765f6d6178636c69656e74735c385c636c69656e74735c305c6d61706e616d655c7a746e33646d315c686f73746e616d655c546865444f47205b315d20467265657a655c70726f746f636f6c5c3638';
// wop info   ffffffff696e666f526573706f6e73650a5c766f69705c315c675f6e656564706173735c305c707572655c315c67616d65747970655c365c73765f6d6178636c69656e74735c35305c675f68756d616e706c61796572735c395c636c69656e74735c395c6d61706e616d655c776f705f636f6c6f72737461676563746c5c686f73746e616d655c5e31485e32655e33635e34745e35695e36635e3773205e30465e31755e326e205e33535e34655e35725e36765e37655e30725c70726f746f636f6c5c37315c67616d656e616d655c576f726c646f665061646d616e
// wop status ffffffff737461747573526573706f6e73650a5c646d666c6167735c305c706f696e746c696d69745c385c74696d656c696d69745c31365c73765f686f73746e616d655c5e31485e32655e33635e34745e35695e36635e3773205e30465e31755e326e205e33535e34655e35725e36765e37655e30725c73765f6d6178636c69656e74735c35305c73765f6d696e526174655c305c73765f6d6178526174655c32353030305c73765f646c526174655c3130305c73765f6d696e50696e675c305c73765f6d617850696e675c305c73765f646c55524c5c687474703a2f2f7a6f636b2d7365727665722e64652f576f505f312e362f706b332d66696c65732f5c675f4c50535f73746172746c697665735c385c675f616c6c6f77766f74655c305c675f646f5761726d75705c315c675f7761726d75705c33305c675f6d617847616d65436c69656e74735c305c675f4c50535f666c6167735c305c675f696e7374615061645c305c76657273696f6e5c776f7020312e365f73766e32313733205c636f6d5f67616d656e616d655c576f726c646f665061646d616e5c636f6d5f70726f746f636f6c5c37315c675f67616d65747970655c365c6d61706e616d655c776f705f636f6c6f72737461676563746c5c73765f70726976617465436c69656e74735c305c73765f616c6c6f77446f776e6c6f61645c315c626f745f6d696e706c61796572735c325c2e41646d696e5c4b61692d4c69204865637469635c2e456d61696c5c6865637469637c61747c7a6f636b2d7365727665722e64655c2e496e666f315c596f75206d75737420656e61626c6520274175746f20446f776e6c6f61642720746f20706c617920616c6c206d617073206f6e207468697320736572766572215c2e496e666f325c4f7220646f776e6c6f616420697420617420687474703a2f2f7a6f636b2d7365727665722e64655c2e5765625c7777772e7a6f636b2d7365727665722e64655c67616d656e616d655c776f705c675f6e656564706173735c305c506c61796572735f5465616d5c3220332032203120312033203320322031205c506c61796572735f426f745c3020302030203020302030203020302030205c675f626572796c6c69756d5c76312e392e31302d653337396332355c2e576562315c776f726c646f667061646d616e2e6e65745c2e576562325c7777772e7a6f636b2d7365727665722e64650a332034382022486563746963220a3020343820225e6441725468334b5e6d28526f4d614e694129220a2d322034362022646f70696b220a2d3120373220225e362d5e357f5e3444655e3341645e3153685e324f745e357f5e362d220a2d3120343820225e337f5e34425e33655e346e5e337f220a2d3220353320225e365265626f6f74220a3020343820225e3441645e3372695e347b48554e7d220a3220343820225e322e3a4c5e347c5e32463a2e5e385e34495e326c745e34695e3273220a3320373220225e362d5e357f5e3153745e3341725e344b695e324c6c5e3745725e357f5e36220a

	public function testParseMasterResponse():void{
		$list = $this->getMethod('parseMasterResponse')->invokeArgs(new Q3A, [hex2bin($this->masterResponse)]);

		$this->assertCount(14, $list);

		[$ip, $port] = $list[0];
		$this->assertSame('95.174.129.9', $ip);
		$this->assertSame(27960, $port);

		[$ip, $port] = $list[13];
		$this->assertSame('109.111.177.137', $ip);
		$this->assertSame(27960, $port);
	}

	public function testParseCvars():void{
		$list = $this->getMethod('parseCvars')->invokeArgs(new Q3A, [hex2bin($this->infoResponse)]);

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
