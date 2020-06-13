<?php

require_once DIR_ENGINE.'registry.php';
require_once DIR_ENGINE.'loader.php';
require_once DIR_ENGINE.'router.php';
require_once DIR_ENGINE.'db/mysqli.php';

$registry = new registry();

$db = new MySQLiCnt('127.0.0.1', 'root', 'root', 'todoip');
$registry->set('db', $db);

$router = new router();
$registry->set('router', $router);

$loader = new loader($registry);
$registry->set('load', $loader);

require_once DIR_APPLICATION.'controller/Controller.php';

$action = 'main';
if(isset($_GET['action']))
    $action = stripslashes(htmlspecialchars(trim($_GET['action'])));

$data = $loader->controller($action, [], true);

if($data === false)
    die("Error: couldn't load file: {$action}");

echo $data;