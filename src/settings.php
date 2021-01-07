<?php
date_default_timezone_set('America/Lima');

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
  error_log($severity. $message. $filename. $lineno . PHP_EOL, 3,  __DIR__ . '/../files/errors.log');
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}
set_error_handler('exceptions_error_handler');

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

define('SESS_KEY','SnId_ruc');
define('SESS_USER','SnUser_ruc');

define('APP_NAME','RUC API');
define('APP_AUTHOR','paulantezana');
define('APP_DESCRIPTION','Servicio de api perú');
define('APP_EMAIL','paulantezana.2@gmail.com');

define('APP_DEV',false);

define('GOOGLE_RE_KEY','6LfcgMQZAAAAALLDsxsVOzNJzzYJ3zNS1nnw0fYm');
define('GOOGLE_RE_SECRET_KEY','6LfcgMQZAAAAANIo0O9NzC5bJyPowYVt9gQMyqyo');