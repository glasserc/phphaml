<?php
/**
 * Including by variable
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/haml/HamlParser.class.php';

$parser = new HamlParser('./tpl', './tmp/haml');

$parser->assign('menu', 'my_menu');
echo $parser->setFile('example9.haml');
?>