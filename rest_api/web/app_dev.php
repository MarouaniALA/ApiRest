<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read https://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true) || PHP_SAPI === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require __DIR__.'/../vendor/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
//header("Access-Control-Allow-Origin: *");
//$response->headers->set('Access-Control-Allow-Origin', '*');
//$response->headers->set('Access-Control-Allow-Origin', '*');
//$response->headers->set('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
//$response->headers->set('Access-Control-Allow-Headers', 'X-Header-One,X-Header-Two');

/*$response->headers->set('Access-Control-Allow-Credentials','true');

$response->headers->set('Access-Control-Allow-Headers','Origin, Accept, Authorization, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');

$response->headers->set('Access-Control-Allow-Method', 'POST, OPTIONS');//, DELETE, PUT, HEAD, OPTIONS');

$response->headers->set('Access-Control-Allow-Origin','*');

$response->headers->set('Access-Control-Max-Age','1800');

$response->headers->set('Cache-Control','max-age=0');*/

$response->send();
$kernel->terminate($request, $response);
