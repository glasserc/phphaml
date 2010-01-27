<?php
/**
 * Original Haml tests from svn.hamptoncatlin.com. Remember
 * phpHaml is not same as original Haml. I must change this
 * tests.
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */

/**
 * Original Haml tests from svn.hamptoncatlin.com. Remember
 * phpHaml is not same as original Haml. I must change this
 * tests.
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */
final class OriginalHamlTests extends PHPUnit_Framework_TestCase
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
	 * Return modified content of original
	 * Haml template
	 *
	 * @param string Template name (without extension)
	 * @param array Lines to change (if null then line is removed)
	 * @return string
	 */
	private function getModifiedFile($name, $lines = array())
	{
		$name = dirname(__FILE__)."/templates/original/$name.haml";
		$file = file_get_contents($name);
		if (empty($lines))
			return $file;
		$flines = explode("\n", $file);
		foreach ($lines as $key => $value)
			$flines[$key-1] = $value;
		foreach ($lines as $key => $value)
			if (is_null($value))
				unset($flines[$key-1]);
		return implode("\n", $flines);
	}
	
	/**
	 * Return modified content of original
	 * Haml test result
	 *
	 * @param string Result name (without extension)
	 * @param array Lines to change (if null then line is removed)
	 * @param array Characters to replace
	 * @return string
	 */
	private function getResults($name, $lines = array(), $rep = array())
	{
		$name = dirname(__FILE__)."/results/original/$name.xhtml";
		$file = file_get_contents($name);
		if (empty($lines) && empty($rep))
			return $file;
		$file = strtr($file, $rep);
		$flines = explode("\n", $file);
		foreach ($lines as $key => $value)
			$flines[$key-1] = $value;
		foreach ($lines as $key => $value)
			if (is_null($value))
				unset($flines[$key-1]);
		return implode("\n", $flines);
	}
	
	/**
	 * Test content_for_layout.haml
	 */
	public function testContentForLayout()
	{
		$this->parser->assign('content_for_layout', 'Lorem ipsum dolor sit amet');
		$this->parser->setSource(
			$this->getModifiedFile('content_for_layout', array(
				1	=> '!!! Transitional', // phpHaml default doctype is 1.1
				6	=> '      = $content_for_layout', // change Ruby syntax to PHP
				8	=> '      = $content_for_layout',
				10	=> '      = $content_for_layout'
			)));
		$this->assertEquals(
			$this->getResults('content_for_layout', array(
				3	=> '  <head></head>', // phpHaml looks upon empty block tag as inline
				4	=> null // so remove next line
			), array('\'' => '"')), // phpHaml use " in attributes
			$this->parser->fetch() . "\n");
	}
	
	/**
	 * Test eval_suppressed.haml
	 */
	public function testEvalSuppressed()
	{
		$this->parser->embedCode(false);
		$this->parser->setSource(
			$this->getModifiedFile('eval_suppressed', array(
				3	=> '- print "not even me!"' // change Ruby syntax to PHP
			)));
		$this->assertEquals(
			$this->getResults('eval_suppressed', array(
			), array('\'' => '"')), // phpHaml use " in attributes
			$this->parser->fetch() . "\n");
	}
	
	/**
	 * Test list.haml
	 */
	public function testList()
	{
		$this->parser->setSource(
			$this->getModifiedFile('list'));
		$this->assertEquals(
			$this->getResults('list'),
			$this->parser->fetch() . "\n");
	}
	
	/**
	 * Test original_engine.haml
	 */
	public function testOriginalEngine()
	{
		$this->parser->setSource(
			$this->getModifiedFile('original_engine', array(
				1 => '!!! Transitional'
			)));
		$this->assertEquals(
			$this->getResults('original_engine', array(
				7	=> null,
				8	=> '      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>',
				9	=> null,
				18	=> null,
				19	=> '<pre>This is some text that\'s in a pre block!',
				20	=> 'Let\'s see what happens when it\'s rendered! What about now, since we\'re on a new line?',
				21	=> '</pre>'
			), array('\'' => '"')),
			$this->parser->fetch() . "\n");
	}
	
	/**
	 * Test tag_parsing.haml
	 * 
	 * @see AttributeHamlTests::testAttributeRepair()
	 */
	public function testTagParsing()
	{
		$this->parser->setSource(
			$this->getModifiedFile('tag_parsing'));
		$this->assertEquals(
			$this->getResults('tag_parsing', array(
				15	=> '  <p class="foo bar" id="boom"></p>',
				16	=> null,
				26	=> '  <foo a="b"></foo>',
				27	=> '  <div c="d" class="foo"></div>'
			), array('\'' => '"')),
			$this->parser->fetch() . "\n");
	}
	
	/**
	 * Test very_basic.haml
	 */
	public function testVeryBasic()
	{
		$this->parser->setSource(
			$this->getModifiedFile('very_basic', array(
				1	=> '!!! Transitional'
			)));
		$this->assertEquals(
			$this->getResults('very_basic', array(
				3	=> '  <head></head>',
				4	=> null,
				5	=> '  <body></body>',
				6	=> null
			)),
			$this->parser->fetch() . "\n");
	}
}

?>