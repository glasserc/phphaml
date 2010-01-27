<?php
/**
 * Example with many features.
 * 
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/haml/HamlParser.class.php';

$parser = new HamlParser('./tpl', './tmp/haml');

class ConfigModel
{
	public $ID;
	public $name;
	public $value;
	
	public function __construct($ID, $name, $value)
	{
		$this->ID = $ID;
		$this->name = $name;
		$this->value = $value; 
	}
	
	public function getID()
	{
		return $this->ID;
	}
}

$models = array();
for ($i = 1; $i <= 100; $i++)
	$models[] = new ConfigModel($i, md5($i), md5(uniqid(rand())));
$parser->assign('models', $models);

echo $parser->setFile('example1.haml');

?>