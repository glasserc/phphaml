<?php
/**
 * Text blocks example
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/haml/HamlParser.class.php';

$parser = new HamlParser('./tpl', './tmp/haml');
$parser->registerBlock('md5', 'md5');

echo $parser->setFile('example4.haml');

?>