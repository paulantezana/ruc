<?php
date_default_timezone_set('America/Lima');

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = parse_url('http://example.com' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$virtualPath = '/' . ltrim(substr($requestUri, strlen($scriptName)), '/');
$hostName = (stripos($_SERVER['REQUEST_SCHEME'], 'https') === 0 ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

define('HOST', $hostName);
define('URI', $requestUri);
define('URL_PATH', rtrim($scriptName,'/'));
define('URL',$virtualPath);

define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"] . rtrim($scriptName,'/'));
define('CONTROLLER_PATH', ROOT_DIR . '/src/Controllers');
define('MODEL_PATH', ROOT_DIR . '/src/Models');
define('VIEW_PATH', ROOT_DIR . '/src/Views');
define('CERVICE_PATH', ROOT_DIR . '/src/Services');

define('SESS_KEY','SkyId');
define('SESS_ROLE','SkyData');

define('APP_NAME','BUSCA RUC');
define('APP_AUTHOR','skynet');
define('APP_DESCRIPTION','busqueda de ruc sunat');
define('APP_EMAIL','skynet@gmail.com');

define('FILE_PATH', '/files');