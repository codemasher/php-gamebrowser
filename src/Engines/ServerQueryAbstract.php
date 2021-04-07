<?php
/**
 * Class ServerQueryAbstract
 *
 * @filesource   ServerQueryAbstract.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowser\Engines
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Engines;

use chillerlan\GameBrowser\{ServerQueryException, ServerQueryOptions};
use chillerlan\Settings\SettingsContainerInterface;
use ReflectionClass, RuntimeException;

abstract class ServerQueryAbstract implements ServerQueryInterface{

	protected SettingsContainerInterface $options;
	protected int $timeout = 1;

	/**
	 * ServerQueryAbstract constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface|null $options
	 */
	public function __construct(SettingsContainerInterface $options = null){
		$this->options = $options ?? new ServerQueryOptions;

		if($this->options->socketTimeout > 0){
			$this->timeout = $this->options->socketTimeout;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getGameName():string{
		return (new ReflectionClass($this))->getShortName();
	}

	/**
	 * @inheritDoc
	 */
	public function query(string $host, int $port, string $query):string{

		// temporarily convert warnings etc. to exceptions to catch possible warnings
		set_error_handler(function($errno, $errstr){

			if(error_reporting() === 0){
				return false;
			}

			throw new RuntimeException($errstr, $errno);
		});

		// https://daniel.haxx.se/blog/2011/02/21/localhost-hack-on-windows/
		$fp = stream_socket_client('udp://'.$host.':'.$port, $errno, $errstr, $this->timeout);

		restore_error_handler();

		if(!$fp){
			throw new RuntimeException($errstr, $errno);
		}

		fwrite($fp, $query);
		stream_set_timeout($fp, $this->timeout);

		$response     = fread($fp, 65536);
		$unread_bytes = stream_get_meta_data($fp)['unread_bytes'] ?? 0;

		if($unread_bytes > 0){
			$response .= fread($fp, $unread_bytes);
		}

		fclose($fp);

		if(empty(trim($response))){
			throw new ServerQueryException('empty/invalid response', $this::ERROR_INVALID_SERVER_RESPONSE);
		}

		return $response;
	}

}
