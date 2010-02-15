<?php
/**
 * Attribute generating tests
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */

class test_scope {
  const hi = 'hi';
}

/**
 * Attribute generating tests
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */
final class AttributeHamlTests extends PHPUnit_Framework_TestCase
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
	 * Test attributes
	 */
	public function testAttributes()
	{
		$this->parser->setSource('%b{ :align => "center", :bgcolor => "green" } Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes syntax withot spaces before
	 * and after brackets
	 */
	public function testAttributesWithoutSpacesBeforeAndAfterBrackets()
	{
		$this->parser->setSource('%b{:align => "center", :bgcolor => "green"} Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes syntax without spaces before
	 * and after arrows (=>)
	 */
	public function testAttributesWithoutSpacesBeforeAndAfterArrows()
	{
		$this->parser->setSource('%b{ :align=>"center", :bgcolor=>"green" } Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes syntax without any spaces
	 */
	public function testAttributesWithoutAnySpaces()
	{
		$this->parser->setSource('%b{:align=>"center",:bgcolor=>"green"} Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes syntax without colons
	 */
	public function testAttributesWithoutColons()
	{
		$this->parser->setSource('%b{ align => "center", bgcolor => "green" } Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test PHP evaluating in attributes
	 */
	public function testAttributesPhp()
	{
		$this->parser->assign('size', 16);
		$this->parser->assign('name', 'foo');
		$this->parser->setSource('%input{ :style => "font-size: $size;", :name => $name }');
		$this->assertEquals('<input name="foo" style="font-size: 16;" />', $this->parser->fetch());
	}

	/**
	 * Test passing arrays of attributes
	 */
	public function testAttributesArray()
	{
		$this->parser->assign('attrs', array('name' => 'foo', 'style' => 'font-size: 16;'));
		$this->parser->setSource('%input{ :type => "text", $attrs }');
		$this->assertEquals('<input name="foo" style="font-size: 16;" type="text" />', $this->parser->fetch());
	}
	
	/**
	 * Test commas in attribute values
	 */
	public function testAttributesCommas()
	{
		$this->parser->assign('attrs', array('name' => 'foo', 'style' => 'font-size: 16;'));
		$this->parser->setSource('%input{ :type => "this, is, sparta", $attrs }');
		$this->assertEquals('<input name="foo" style="font-size: 16;" type="this, is, sparta" />', $this->parser->fetch());
	}

	/**
	 * Test commas in attribute values
	 */
	public function testAttributesDoubleColon()
	{
		$this->parser->assign('attrs', array('name' => 'foo', 'style' => 'font-size: 16;'));
		$this->parser->setSource('%input{ :xml:lang => "en-us", :http-equiv => "Content-Type",:foo => test_scope::hi, $attrs }');
		$this->assertEquals('<input name="foo" style="font-size: 16;" foo="hi" http-equiv="Content-Type" xml:lang="en-us" />', $this->parser->fetch());
	}

	/**
	 * Test attributes stack
	 */
	public function testAttributesListsStack()
	{
		$this->parser->setSource('%b{ :foo => "bar" }#myid{ :hello => "world"} Lorem ipsum');
		$this->assertEquals('<b foo="bar" hello="world" id="myid">Lorem ipsum</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes and IDs stack
	 *
	 * @see AttributeHamlTests::testIdStack()
	 */
	public function testAttributesAndIdStack()
	{
		$this->parser->setSource('%b{ :id => "x" }#y{ :id => "y" } Hello, World!');
		$this->assertEquals('<b id="y">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes and classes stack. Remember:
	 * .bar has higher priority than { :class => "hello" }
	 *
	 * @see AttributeHamlTests::testIdStack()
	 */
	public function testAttributesAndClassStack()
	{
		$this->parser->setSource('%b{ :class => "foo" }.bar{ :class => "hello" }.world Hello, World!');
		$this->assertEquals('<b class="bar world">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test IDs stack. Remember: #foo has higher
	 * priority than { :id => "x" }. So if you want
	 * dynamic ID use eg.
	 * { :id => isset($news) ? "news_$news->ID" : "content" }
	 */
	public function testIdStack()
	{
		$this->parser->setSource('%b#foo#bar{ :id => "x" } Hello, World!');
		$this->assertEquals('<b id="bar">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test class joining. Remember: only classes
	 * writed as .foo.bar are joined.
	 *
	 * @see AttributeHamlTests::testAttributesAndClassStack()
	 */
	public function testClassJoining()
	{
		$this->parser->setSource('%b.foo.bar Hello, World!');
		$this->assertEquals('<b class="foo bar">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attributes order. Attributes are ordered
	 * alphabeticall by attribute name.
	 */
	public function testAttributesOrder()
	{
		$this->parser->setSource('%b{ :bgcolor => "green", :align => "center" } Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
	
	/**
	 * Test attribute reparing. phpHaml can repair:
	 *  - value without quotation marks (eg. { :bgcolor => green })
	 *  - value without quotation marks followed by colon (same syntax like key eg. { :align => :center }
	 */
	public function testAttributeRepair()
	{
		$this->markTestIncomplete();
		return;
		$this->parser->setSource('%b{ :bgcolor => green, :align => :center } Hello, World!');
		$this->assertEquals('<b align="center" bgcolor="green">Hello, World!</b>', $this->parser->fetch());
	}
}

?>