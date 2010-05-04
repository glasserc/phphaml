<?php
/**
 * Common Haml tests
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */

/**
 * Common Haml tests
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */
final class HamlTests extends PHPUnit_Framework_TestCase
{
	/**
	 * Haml parser
	 *
	 * @var HamlParser
	 */
	private $parser;
	
	/**
	 * Create HamlParser
	 */
	public function setUp()
	{
		$this->parser = new HamlParser(dirname(__FILE__).'/templates/original', false);
		$this->parser->setTmp(dirname(__FILE__).'/../../tmp/haml');
	}

	/**
	 * Test one templated used many times
	 */
	public function testTemplateUsedManyTimes()
	{
		$this->parser->setSource('%li= $i');
		for ($i = 1; $i <= 10; $i++)
			$this->assertEquals("<li>$i</li>",
				$this->parser->assign('i', $i)
				     ->fetch());
	}

	public function testTemplateReentrancy(){
		$this->parser->assign("a", 1);
		$parser2 = new HamlParser();
		$this->assertEquals(0, count($parser2->getVariables()));
		$this->assertEquals(1, count($this->parser->getVariables()));
		$parser2->setSource('= $var');
		$parser2->setTmp(dirname(__FILE__).'/../../tmp/haml');
		$this->assertEquals("Hello", $parser2->render(array('var' => "Hello")));
	}
}

?>