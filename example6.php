<?php
/**
 * Example with embedded Sass code
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/haml/HamlParser.class.php';

$parser = new HamlParser('./tpl', './tmp/haml');

echo $parser->setFile('example6.haml');

?>