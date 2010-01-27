<?php
/**
 * Simpliest way to use Haml.
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/haml/HamlParser.class.php';

$title = 'This is title';
$text =  'Lorem ipsum dolor sit amet';

display_haml('./tpl/example3.haml', array(), './tmp/haml');
?>