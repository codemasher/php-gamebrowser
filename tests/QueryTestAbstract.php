<?php
/**
 * Class QueryTestAbstract
 *
 * @filesource   QueryTestAbstract.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowserTest
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowserTest;

use PHPUnit\Framework\TestCase;
use ReflectionClass, ReflectionMethod, ReflectionProperty;

/**
 */
abstract class QueryTestAbstract extends TestCase{

	/**
	 * @var \ReflectionClass
	 */
	protected $reflection;

	/**
	 * @var string
	 */
	protected $FQCN;

	/**
	 *
	 */
	protected function setUp(){
		$this->reflection = new ReflectionClass($this->FQCN);
	}

	/**
	 * @param string $method
	 *
	 * @return \ReflectionMethod
	 */
	protected function getMethod(string $method):ReflectionMethod {
		$method = $this->reflection->getMethod($method);
		$method->setAccessible(true);

		return $method;
	}

	/**
	 * @param string $property
	 *
	 * @return \ReflectionProperty
	 */
	protected function getProperty(string $property):ReflectionProperty {
		$property = $this->reflection->getProperty($property);
		$property->setAccessible(true);

		return $property;
	}

	/**
	 * @param        $object
	 * @param string $property
	 * @param        $value
	 *
	 * @return void
	 */
	protected function setProperty($object, string $property, $value):void {
		$property = $this->getProperty($property);
		$property->setAccessible(true);
		$property->setValue($object, $value);
	}

}
