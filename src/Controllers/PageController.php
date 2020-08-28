<?php

require_once(MODEL_PATH . '/User.php');
require_once(MODEL_PATH . '/UserForgot.php');
require_once(MODEL_PATH . '/Census.php');
require_once(MODEL_PATH . '/AppAuthorization.php');
require_once(CERVICE_PATH . '/SendManager/EmailManager.php');

class PageController extends Controller
{
    private $connection;
    private $userModel;
    private $censusModel;
    private $userForgotModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->censusModel = new Census($connection);
        $this->userForgotModel = new UserForgot($connection);
    }

    public function home()
    {
        try{
            $this->render('home.view.php', [], 'layouts/site.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
    }

    public function rucQuery(){
        $res = new Result();
        try {
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            if (($body['ruc'] ?? '') == '') {
                throw  new Exception('Falta ingresar el ruc');
            }
            if (!RUCIsValid($body['ruc'])) {
                throw  new Exception('Ruc invalido');
            }
            if (($body['googleKey'] ?? '') == '') {
                throw  new Exception('No se especifico una clave');
            }

            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='. GOOGLE_RE_SECRET_KEY . '&response=' . $body['googleKey'] );
            $response = json_decode($response);
            if($response->success == false){
                throw  new Exception('Acceso incorrecto');
            }

            $census = $this->censusModel->queryByRuc($body['ruc']);
            $res->view = $this->render('partials/censusByRuc.partial.php',[
                'census' => $census,
            ],'',true);
            $res->message = 'Consulta exitosa';
            $res->result = $response;
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function support()
    {
        $this->render('support.view.php', [], 'layouts/site.layout.php');
    }

    public function price()
    {
        $this->render('price.view.php', [], 'layouts/site.layout.php');
    }

    public function error404()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';

        if (strtolower($_SERVER['HTTP_ACCEPT']) === 'application/json') {
            http_response_code(404);
        } else {
            $this->render('404.view.php', [
                'message' => $message
            ], 'layouts/site.layout.php');
        }
    }

    public function login()
    {
        try {
            if (isset($_POST['commit'])) {
                try {
                    if (!isset($_POST['email']) || !isset($_POST['password'])) {
                        throw new Exception('Los campos usuario y contraseña son requeridos');
                    }

                    $email = htmlspecialchars($_POST['email']);
                    $password = htmlspecialchars($_POST['password']);

                    if (empty($email) || empty($password)) {
                        throw new Exception('Los campos usuario y contraseña son requeridos');
                    }

                    $user = $this->userModel->login($email, $password);

                    $responseApp = $this->initApp($user);
                    if (!$responseApp->success) {
                        session_destroy();
                        $this->render('403.view.php', [
                            'message' => $responseApp->message,
                        ], 'layouts/site.layout.php');
                        return;
                    }

                    $this->redirect('/');
                    return;
                } catch (Exception $e) {
                    $this->render('login.view.php', [
                        'messageType' => 'error',
                        'message' => $e->getMessage(),
                    ], 'layouts/site.layout.php');
                }
            } else {
                $this->render('login.view.php', [], 'layouts/site.layout.php');
            }
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
    }

    public function register()
    {
        try {
            $message = '';
            $messageType = '';
            $error = [];

            if (isset($_POST['commit'])) {
                try {
                    if (!isset($_POST['register'])) {
                        throw new Exception('No se proporcionó ningun dato');
                    }

                    $register = $_POST['register'];

                    $validate = $this->validateRegister($register);
                    if (!$validate->success) {
                        throw new Exception($validate->message);
                    }

                    $userName = htmlspecialchars($register['userName']);
                    $email = htmlspecialchars($register['email']);
                    $password = htmlspecialchars($register['password']);
                    $fullName = htmlspecialchars($register['fullName']);

                    $userId = $this->userModel->insert([
                        'userName' => $userName,
                        'email' => $email,
                        'password' => $password,
                        'fullName' => $fullName,
                        'userRoleId' => 2,
                    ], 0);

                    $loginUser = $this->userModel->getById($userId);
                    $responseApp = $this->initApp($loginUser);
                    if (!$responseApp->success) {
                        session_destroy();
                        $this->render('403.view.php', [
                            'message' => $responseApp->message,
                        ]);
                        return;
                    }

                    $urlApp = HOST . URL_PATH . '/page/login';
                    $resEmail = EmailManager::send(
                        APP_EMAIL,
                        $email,
                        '¡🚀 Bienvenido a ' . APP_NAME . ' !',
                        '<div>
                            <h1>' . $fullName . ', bienvenido(a) a ' . APP_NAME . '. Acelera tu negocio</h1>
                            <p>' . APP_DESCRIPTION . '</p>
                            <a href="' . $urlApp . '">Ingresar al sistema</a>
                        </div>'
                    );

                    if (!$resEmail->success) {
                        throw new Exception($resEmail->message);
                    }

                    $this->redirect('/');
                    return;
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    $messageType = 'error';
                }
            }

            $this->render('register.view.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ], 'layouts/site.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
    }

    public function forgot()
    {
        if (isset($_SESSION[SESS_KEY])) {
            $this->redirect('/');
        }

        try {
            $resView = new Result();
            $resView->messageType = '';

            if (isset($_POST['commit'])) {
                try {
                    $email = htmlspecialchars($_POST['email'] ?? '');
                    if (($email) == '') {
                        throw new Exception('Falta ingresar el correo');
                    }

                    $user = $this->userModel->getBy('email', $email);
                    if (!$user) {
                        throw new Exception('Este correo electrónico no está registrado.');
                    }

                    $currentDate = date('Y-m-d H:i:s');
                    $token = sha1($currentDate . $user['user_id'] . $user['email']);

                    $this->userForgotModel->insert([
                        'secretKey' => $token,
                        'userId' => $user['user_id'],
                    ], $user['user_id']);

                    $urlForgot = HOST . URL_PATH . '/page/forgotValidate?key=' . $token;
                    $resEmail = EmailManager::send(
                        APP_EMAIL,
                        $user['email'],
                        'Recupera tu Contraseña',
                        '<p>Recientemente se solicitó un cambio de contraseña en tu cuenta. Si no fuiste tú, ignora este mensaje y sigue disfrutando de la experiencia de ' . APP_NAME . '.</p>
                                 <a href="' . $urlForgot . '" target="_blanck">Cambiar contraseña</a>'
                    );
                    if (!$resEmail->success) {
                        throw new Exception($resEmail->message);
                    }

                    $resView->message = 'El correo electrónico de confirmación de restablecimiento de contraseña se envió a su correo electrónico.';
                    $resView->messageType = 'success';
                } catch (Exception $exception) {
                    $resView->message = $exception->getMessage();
                    $resView->messageType = 'error';
                }
            }

            $this->render('forgot.view.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
            ], 'layouts/site.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
    }

    public function forgotValidate()
    {
        if (isset($_SESSION[SESS_KEY])) {
            $this->redirect('/');
        }

        try {
            $resView = new Result();
            $resView->messageType = '';
            $resView->contentType = 'validateToken';

            $user = [];
            $currentDate = date('Y-m-d H:i:s');

            // change password
            if (isset($_GET['key'])) {
                $resView->contentType = 'validateToken';
                $key = htmlspecialchars($_GET['key']);
                try {
                    $forgot = $this->userForgotModel->getBySecretKey($key);
                    if (!$forgot) {
                        throw new Exception('Token invalido o expirado');
                    }

                    $diff = strtotime($currentDate) - strtotime($forgot['created_at']);
                    if (($diff / 60) > 120) {
                        throw new Exception('Token expirado');
                    }
                    $user['user_id'] = $forgot['user_id'];
                    $user['user_forgot_id'] = $forgot['user_forgot_id'];

                    $resView->message = 'Token valido cambie su contraseña';
                    $resView->messageType = 'success';
                } catch (Exception $e) {
                    $resView->message = $e->getMessage();
                    $resView->messageType = 'error';
                }
            } else if (isset($_POST['commit'])) {
                $resView->contentType = 'changePassword';
                try {
                    $password = htmlspecialchars($_POST['password']);
                    $confirmPassword = htmlspecialchars($_POST['confirmPassword']);
                    $userForgotId = htmlspecialchars($_POST['userForgotId']);
                    $user['user_id'] = htmlspecialchars($_POST['userId']);

                    if (!($confirmPassword === $password)) {
                        throw new Exception('Las contraseñas no coinciden');
                    }
                    if (!$user['user_id']) {
                        throw new Exception('Usuario no especificado.');
                    }

                    $password = sha1($password);
                    $this->userModel->UpdateById($user['user_id'], [
                        "updated_at" => $currentDate,
                        "updated_user_id" => $user['user_id'],

                        'password' => $password,
                    ]);
                    $this->userForgotModel->updateById($userForgotId, [
                        "updated_at" => $currentDate,
                        "updated_user_id" => $user['user_id'],

                        'used' => 1,
                        'secret_key' => '',
                    ]);

                    $resView->message = 'Cambio de contraseña exitosa';
                    $resView->messageType = 'success';
                } catch (Exception $e) {
                    $resView->message = $e->getMessage();
                    $resView->messageType = 'error';
                }
            }

            $this->render('forgotValidate.view.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
                'contentType' => $resView->contentType,
                'user' => $user,
            ], 'layouts/site.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    private function initApp($user)
    {
        $res = new Result();
        try {
            $appAuthorizationModel = new AppAuthorization($this->connection);
            $menu = $appAuthorizationModel->getMenu($user['user_role_id']);

            // 1 day
            setcookie('admin_menu', json_encode($menu), time() + (86400000), '/');
            
            unset($user['password']);
            $_SESSION[SESS_KEY] = $user['user_id'];
            $_SESSION[SESS_USER] = $user;

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }

    private function validateRegister($body)
    {
        $res = new Result();
        $res->success = true;
        if (($body['email'] ?? '') == '') {
            $res->success = false;
            $res->message .= 'Falta ingresar el correo electrónico';
        }
        if (($body['fullName'] ?? '') == '') {
            $res->success = false;
            $res->message .= 'Falta ingresar el nombre completo del usuario';
        }
        if (($body['userName'] ?? '') == '') {
            $res->success = false;
            $res->message .= 'Falta ingresar el nombre de usuario';
        }
        if (($body['password'] ?? '') == '') {
            $res->success = false;
            $res->message .= 'Falta ingresar la contraseña';
        }
        if (($body['passwordConfirm'] ?? '') == '') {
            $res->success = false;
            $res->message .= 'Falta ingresar la confirmación contraseña';
        }
        if ($body['password'] != $body['passwordConfirm']) {
            $res->success = false;
            $res->message .= 'Las contraseñas no coinciden';
        }

        return $res;
    }
}
