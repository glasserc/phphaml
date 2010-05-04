<?php
/**
 * Tag generating tests
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */

final class HamlSyntaxTests extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->parser = new HamlParser(dirname(__FILE__).'/templates/original', false);
		$this->parser->setTmp(dirname(__FILE__).'/../../tmp/haml');
	}

	public function testMultiline()
	{
		$this->parser->setSource("%p Hi |\n there");
		$this->assertEquals("<p>Hi there</p>", $this->parser->fetch());
	}

	public function testPipeSafe()
	{
		$this->parser->setSource("%p Hi\n|\nthere");
		$this->assertEquals("<p>Hi</p>\n|\nthere", $this->parser->fetch());
	}

	public function testMultilineChain()
	{
		$this->parser->setSource("%p Hi |\n|\nthere");
		// This test is gonna be broken for a while
		//$this->assertEquals("<p>Hi |</p>\nthere", $this->parser->fetch());
	}

	public function testMultilineNonword()
	{
		$this->parser->setSource("%p= 'hi'. |\n  'there'\n");
		$this->assertEquals("<p>hithere</p>", $this->parser->fetch());
		// This isn't gonna work easily
		$this->markTestIncomplete();
		$this->parser->setSource("%p Hi.\n- # |\n%p there");
		$this->assertEquals("<p>Hi.</p>\n<p>there</p>", $this->parser->fetch());
	}

	public function testElseSafe()
	{
		$this->parser->setSource("- if(false)\n  Hi!\n- else\n  Bye!");
		$this->assertEquals("  Bye!", $this->parser->fetch()); // FIXME: whitespace?
	}

	public function testElseSafe2()
	{
		$this->parser->setSource("#test0\n  #test1\n    - if(false)\n      Hi!\n    - else\n      Bye!");
		$this->assertEquals("<div id=\"test0\">\n  <div id=\"test1\">\n      Bye!\n  </div>\n</div>", $this->parser->fetch()); // FIXME: whitespace?
		$this->parser->setSource("#test0\n  #test1\n    #test2\n      - if(false)\n        Hi!\n      - else\n        Bye!");
		$this->assertEquals("<div id=\"test0\">\n  <div id=\"test1\">\n    <div id=\"test2\">\n        Bye!\n    </div>\n  </div>\n</div>", $this->parser->fetch()); // FIXME: whitespace?
	}

	public function testElseIfSafe()
	{
		$this->parser->setSource("- if(false)\n  Hi!\n- elseif(false)\n  Nope!\n- else\n  Bye!");
		$this->assertEquals("  Bye!", $this->parser->fetch()); // FIXME: whitespace?
	}


	public function testCommas()
	{
		$this->parser->setSource("%a{ :href => 'a,b,c',:rel=> 'link'}");
		$this->assertEquals('<a href="a,b,c" rel="link"></a>', $this->parser->fetch());
	}

	/**
	 * @expectedException HamlException
	 */
	public function testBadIndent(){
		$this->parser->setSource("Hi\n there");
		$this->parser->fetch();
	}

	public function testBlankLines(){
		$this->parser->setSource("%div\n  Hi\n\n  there");
		$this->assertEquals("<div>\n  Hi\n  there\n</div>", $this->parser->fetch());
	}

	public function testWhitespaceEater1(){
		$this->parser->setSource("%a{:href => 'foo'}> hello\n!");
		$this->assertEquals('<a href="foo">hello</a>!', $this->parser->fetch());
	}

	public function testWhitespaceEater2(){
		$this->parser->setSource("!\n%a{:href => 'foo'}> hello\n!");
		$this->assertEquals('!<a href="foo">hello</a>!', $this->parser->fetch());
	}

	public function testWhitespaceEaterNest(){
		$this->parser->setSource("%div\n  !\n  %a{:href => 'foo'}> hello\n  !");
		$this->assertEquals("<div>\n  !<a href=\"foo\">hello</a>!\n</div>", $this->parser->fetch());
	}
}
