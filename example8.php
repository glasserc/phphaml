<?php
/**
 * Example with CSS generating with Sass
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Examples
 */

require_once './includes/sass/SassParser.class.php';

$sass = new SassParser('./tpl', './tmp/sass');
header('Content-Type: text/css');
echo $sass->render('example8.sass');

?>