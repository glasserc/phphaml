<?php
/**
 * Tests runner
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests
 */

if (!defined('PHPUnit_MAIN_METHOD')) 
	define('PHPUnit_MAIN_METHOD', 'TestsRunner::main');

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once dirname(__FILE__).'/../includes/haml/HamlParser.class.php';
require_once dirname(__FILE__).'/../includes/sass/SassParser.class.php';

/**
 * Tests runner
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests
 */
class TestsRunner
{
	/**
	 * Main method. Run text UI
	 */
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
	
	/**
	 * Return test suite with all tests (haml and sass)
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('All tests');
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__FILE__).'/haml'));
		$haml = new PHPUnit_Framework_TestSuite('Haml');
		foreach ($files as $file)
			if (!$file->isDir())
				if (preg_match('/(.*?)\.class\.php$/ius', $file->getFilename(), $matches))
					$haml->addTestFile($file->getPathname());
		$suite->addTestSuite($haml);
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__FILE__).'/sass'));
		$sass = new PHPUnit_Framework_TestSuite('Sass');
		foreach ($files as $file)
			if (!$file->isDir())
				if (preg_match('/(.*?)\.class\.php$/ius', $file->getFilename(), $matches))
					$sass->addTestFile($file->getPathname());
		$suite->addTestSuite($sass);
		return $suite;
	}
}

if (PHPUnit_MAIN_METHOD == 'TestsRunner::main')
	TestsRunner::main();

?>