<?php

class Result extends stdClass
{
    public $success;
    public $message;
    public $result;

    function __construct()
    {
        $this->success = false;
        $this->message = '';
        $this->result = null;
    }
}

function requireToVar($file, $parameter)
{
    ob_start();
    require($file);
    return ob_get_clean();
}

function authorization(PDO $connection, string $module, string $action, string $errorMessage = '')
{
    if (!isset($_SESSION[SESS_KEY])) {
        header('Location: ' . URL_PATH . '/page/login');
        return;
    }

    $stmt = $connection->prepare('SELECT user_id, user_role_id FROM user WHERE user_id = ' . $_SESSION[SESS_KEY]);
    $stmt->execute();
    $user = $stmt->fetch();

    $stmt = $connection->prepare('SELECT count(*) as count FROM user_role_authorization as ur
                            INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                            WHERE ur.user_role_id = :user_role_id AND app.module = :module AND app.action = :action
                            GROUP BY app.module');
    $stmt->execute([
        ':user_role_id' => $user['user_role_id'] ?? 0,
        ':module' => $module,
        ':action' => $action,
    ]);

    $data = $stmt->fetch();

    $res = new Result();
    if ($data === false) {
        $res->success = false;
        $res->message = 'Lo sentimos, no estás autorizado para realizar esta operación';

        if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
            echo json_encode($res);
            die();
        } else {
            $content = requireToVar(VIEW_PATH . '/' . '403.view.php', [
                'message' => $res->message,
            ]);
            require_once(VIEW_PATH . '/' . 'layouts/basic.layout.php');
            die();
        }
    }
    $res->success = true;
    return $res;
}

function menuIsAuthorized($menuName)
{
    $menu = json_decode(isset($_COOKIE['admin_menu']) ? $_COOKIE['admin_menu'] : '[]', true);
    if (count($menu) == 0) {
        return false;
    }

    if (gettype($menuName) === 'string') {
        $index = array_search($menuName, array_column($menu, 'module'));
        return is_numeric($index);
    } elseif (gettype($menuName) === 'array') {
        $valid = false;
        foreach ($menuName as $row) {
            $index = array_search($row, array_column($menu, 'module'));
            if (is_numeric($index)) {
                $valid = true;
            }
        }
        return $valid;
    } else {
        return false;
    }
}

function deleteDir($target)
{
    if (is_dir($target)) {
        $files = glob($target . '/*.txt');
        foreach ($files as $file) {
            is_file(unlink($file));
        }
        rmdir($target);
    }
}


function rucIsValid($valor)
{
    $valor = trim($valor);
    if ( $valor )
    {
        if ( strlen($valor) == 11 ) // RUC
        {
            $sum = 0;
            $x = 6;
            for ( $i=0; $i<strlen($valor)-1; $i++ )
            {
                if ( $i == 4 )
                {
                    $x = 8;
                }
                $digit = $valor[$i];
                $x--;
                if ( $i==0 )
                {
                    $sum += ($digit*$x);
                }
                else
                {
                    $sum += ($digit*$x);
                }
            }
            $rest = $sum % 11;
            $rest = 11 - $rest;
            if ( $rest >= 10)
            {
                $rest = $rest - 10;
            }
            if ( $rest == $valor[strlen($valor)-1] )
            {
                return true;
            }
        }
    }
    return false;
}