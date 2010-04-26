<?php
/**
 * Tag generating tests
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */

/**
 * Tag generating tests
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */
final class TagHamlTests extends PHPUnit_Framework_TestCase
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
	 * Test named inline tag
	 */
	public function testNamedInlineTag()
	{
		$this->parser->setSource('%strong Hello, World!');
		$this->assertEquals("<strong>Hello, World!</strong>", $this->parser->fetch());
	}
	
	/**
	 * Test named tag - should be block, but displayed
	 * as inline
	 */
	public function testNamedInlineBlockTag()
	{
		$this->parser->setSource('%p Lorem ipsum dolor sit amet');
		$this->assertEquals("<p>Lorem ipsum dolor sit amet</p>", $this->parser->fetch());
	}
	
	/**
	 * Test named tag - should be block
	 */
	public function testNamedBlockTag()
	{
		$this->parser->setSource("%p\n  Lorem ipsum dolor sit amet");
		$this->assertEquals("<p>\n  Lorem ipsum dolor sit amet\n</p>", $this->parser->fetch());
	}
	
	/**
	 * Test tag without name, but with ID, should be
	 * inline div
	 */
	public function testInlineIdTag()
	{
		$this->parser->setSource('#foo hello');
		$this->assertEquals('<div id="foo">hello</div>', $this->parser->fetch());
	}
	
	/**
	 * Test tag without name, but with ID (generate div)
	 */
	public function testIdTag()
	{
		$this->parser->setSource("#foo\n  hello");
		$this->assertEquals("<div id=\"foo\">\n  hello\n</div>", $this->parser->fetch());
	}

	/**
	 * Test tag without name, but with ID (generate div)
	 */
	public function testIdTagDashes()
	{
		$this->parser->setSource("#foo-bar-baz\n  hello");
		$this->assertEquals("<div id=\"foo-bar-baz\">\n  hello\n</div>", $this->parser->fetch());
	}
	
	/**
	 * Test tag without name, but with class, should be
	 * inline div
	 */
	public function testInlineClassTag()
	{
		$this->parser->setSource('.bar lorem');
		$this->assertEquals('<div class="bar">lorem</div>', $this->parser->fetch());
	}
	
	/**
	 * Test tag without name, but with class (generate div)
	 */
	public function testClassTag()
	{
		$this->parser->setSource(".bar\n  lorem");
		$this->assertEquals("<div class=\"bar\">\n  lorem\n</div>", $this->parser->fetch());
	}
	
	/**
	 * Test tags stack (last tag should display)
	 */
	public function testTagsStack()
	{
		$this->parser->setSource('%b%strong Hello, World!');
		$this->assertEquals('<strong>Hello, World!</strong>', $this->parser->fetch());
	}
	
	/**
	 * Test autoclosed tag
	 */
	public function testAutoclosedTag()
	{
		$this->parser->setSource('%meta');
		$this->assertEquals('<meta />', $this->parser->fetch());
	}
	
	/**
	 * Test autoclosed tag with content
	 * 
	 * @see TagHamlTests::testClosedContentTag()
	 */
	public function testAutoclosedContentTag()
	{
		$this->parser->setSource('%meta it should not display');
		$this->assertEquals('<meta />', $this->parser->fetch());
	}
	
	/**
	 * Test closed tag with /
	 */
	public function testClosedTag()
	{
		$this->parser->setSource('%closed/');
		$this->assertEquals('<closed />', $this->parser->fetch());
	}
	
	/**
	 * Test closed tag with content (content shouldn't
	 * display)
	 */
	public function testClosedContentTag()
	{
		$this->parser->setSource('%closed/ it should not display');
		$this->assertEquals('<closed />', $this->parser->fetch());
	}
	
	/**
	 * Test tag with xHTML namespace in name (with colon)
	 */
	public function testXhtmlTagNamespace()
	{
		$this->parser->setSource('%foo:bar Hello, World!');
		$this->assertEquals('<foo:bar>Hello, World!</foo:bar>', $this->parser->fetch());
	}
	
	/**
	 * Test tag with underscore in name
	 */
	public function testTagWithUnderscore()
	{
		$this->parser->setSource('%foo_bar Hello, World!');
		$this->assertEquals('<foo_bar>Hello, World!</foo_bar>', $this->parser->fetch());
	}
	
	/**
	 * Test tag with dash in name
	 */
	public function testTagWithDash()
	{
		$this->parser->setSource('%foo-bar Hello, World!');
		$this->assertEquals('<foo-bar>Hello, World!</foo-bar>', $this->parser->fetch());
	}

	public function testFrameBorder()
	{
		$this->parser->setSource('%iframe{ :frameBorder => "0" } Hello!!');
		$this->assertEquals('<iframe frameBorder="0">Hello!!</iframe>', $this->parser->fetch());
	}
}

?>