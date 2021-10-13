<?php

require_once(MODEL_PATH . '/UserToken.php');
require_once(MODEL_PATH . '/User.php');
require_once(MODEL_PATH . '/UserForgot.php');
require_once(MODEL_PATH . '/Census.php');
require_once(CERVICE_PATH . '/SendManager/EmailManager.php');
require_once(CERVICE_PATH . '/DNI/EsaludDNI.php');
require_once(CERVICE_PATH . '/DNI/JNE.php');
require_once(CERVICE_PATH . '/DNI/Fact.php');

class PageController extends Controller
{
    private $connection;
    private $userModel;
    private $censusModel;
    private $userTokenModel;
    private $userForgotModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->userTokenModel = new UserToken($connection);
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

            if (($body['documentNumber'] ?? '') == '') {
                throw  new Exception('Falta ingresar el número del documento');
            }
            if (($body['documentType'] ?? '') == '') {
                throw  new Exception('Falta especificar el tipo de documento');
            }

            $documentType = $body['documentType'];
            $documentNumber = $body['documentNumber'];

            if($body['documentType'] === 'ruc'){
                if (!RUCIsValid($body['documentNumber'])) {
                    throw  new Exception('Número de documento invalido');
                }
            } else if(strlen($body['documentNumber']) !== 8){
                throw  new Exception('Número de documento invalido');
            }

            if (($body['googleKey'] ?? '') == '') {
                throw  new Exception('No se especifico una clave');
            }

            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='. GOOGLE_RE_SECRET_KEY . '&response=' . $body['googleKey'] );
            $response = json_decode($response);
            if($response->success == false){
                throw  new Exception('Acceso incorrecto');
            }


            // $documentType = $body['documentType'];
            // $documentNumber = $body['documentNumber'];

            if($documentType == 'ruc'){
                $census = $this->censusModel->queryByRuc($documentNumber);
                $res->view = $this->render('partials/censusByRuc.partial.php',[
                    'census' => $census,
                ],'',true);
            } else {
                $factRes = new FactDni();
                $responseFact = $factRes->query($documentNumber);
                if (!$responseFact->success){
                    $pearson = [
                        'name' => $responseFact->result['name'],
                        'motherLastName' => $responseFact->result['motherLastName'],
                        'lastName' => $responseFact->result['lastName'],
                        'documentNumber' => $responseFact->result['documentNumber'],
                        'sex' => '',
                        'birthDate' => '',
                    ];
                } else {
                    $pearson = [
                        'name' => $responseFact->result['name'],
                        'motherLastName' => $responseFact->result['motherLastName'],
                        'lastName' => $responseFact->result['lastName'],
                        'documentNumber' => $responseFact->result['documentNumber'],
                        'sex' => '',
                        'birthDate' => '',
                    ];
                }

                $res->view = $this->render('partials/pearson.partial.php',[
                    'pearson' => $pearson,
                ],'',true);
            }

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

    public function token()
    {
        try{
            if (!isset($_SESSION[SESS_KEY])) {
                $this->redirect('/');
            }

            $userToken = $this->userTokenModel->getAllByUserId($_SESSION[SESS_KEY]);
            $this->render('token.view.php', [
                'userToken' => $userToken,
            ], 'layouts/site.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
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
    public function error403()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';

        if (strtolower($_SERVER['HTTP_ACCEPT']) === 'application/json') {
            http_response_code(403);
        } else {
            $this->render('403.view.php', [
                'message' => $message
            ], 'layouts/site.layout.php');
        }
    }
}
