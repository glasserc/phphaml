<?php
/**
 * Textile block example
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/haml/HamlParser.class.php';

// Set path to textile engine
require_once '../aFRM/includes/textile/classTextile.php';

$parser = new HamlParser('./tpl', './tmp/haml');

echo $parser->setFile('example5.haml');

?>