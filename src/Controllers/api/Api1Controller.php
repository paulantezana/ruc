<?php

require_once(MODEL_PATH . '/Census.php');
require_once(MODEL_PATH . '/UserToken.php');

require_once(CERVICE_PATH . '/RUC/SunatRUC.php');
require_once(CERVICE_PATH . '/DNI/EsaludDNI.php');
require_once(CERVICE_PATH . '/DNI/JNE.php');

require_once(CONTROLLER_PATH . '/Helpers/ApiSign.php');

class Api1Controller extends Controller
{
    private $connection;
    private $censusModel;
    private $userTokenModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->censusModel = new Census($connection);
        $this->userTokenModel = new UserToken($connection);
    }

    public function ruc()
    {
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");

        $res = new Result();
        try {
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            if(!isset($body['ruc'])){
                throw new Exception('Ingrese un ruc');
            }
            if(!isset($body['token'])){
                throw new Exception('Ingrese un token');
            }

            $ruc = $body['ruc'];
            $token = $body['token'];
            $userQuery = $this->validateToken($token);

            if(!rucIsValid($ruc)){
                throw new Exception('Formato de ruc invalido');
            }

            $this->userTokenModel->counterUp($userQuery['userTokenId'],$userQuery['userId']);

            $census = $this->censusModel->queryByRuc($ruc);
            if($census == false){
                $sunatRUC = new SunatRUC();
                $response = $sunatRUC->Query($ruc);
                if (!$response->success){
                    throw new Exception($response->message);
                }

                $census = [
                    'ruc' => $response->result['documentNumber'],
                    'social_reason' => $response->result['socialReason'],
                    'taxpayer_state' => $response->result['state'],
                    'domicile_condition' => '',
                    'ubigeo' => '',
                    'type_road' => '',
                    'name_road' => '',
                    'zone_code' => '',
                    'type_zone' => '',
                    'number' => '',
                    'inside' => '',
                    'lot' => '',
                    'department' => '',
                    'kilometer' => '',
                    'address' => '',
                    'full_address' => $response->result['address'],
                    'last_update_sunat' => '',
                ];
            }

            $res->result = $census;
            $res->message = 'Success';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
    public function dni(){
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");

        $res = new Result();
        try {
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            if(!isset($body['dni'])){
                throw new Exception('Ingrese un dni');
            }
            if(!isset($body['token'])){
                throw new Exception('Ingrese un token');
            }

            $dni = $body['dni'];
            $token = $body['token'];
            $userQuery = $this->validateToken($token);

            if(!(strlen($dni) === 8)){
                throw new Exception('Formato de dni invalido');
            }

            $this->userTokenModel->counterUp($userQuery['userTokenId'],$userQuery['userId']);

            $esalud = new EsaludDNI();
            $response = $esalud->Query($dni);
            if (!$response->success){
                $responseJne = JNE::query($dni);
                if (!$responseJne->success){
                    throw new Exception($res->message);
                }

                $pearson = [
                    'dni' => $response->result['documentNumber'],
                    'social_reason' => $response->result['lastName'] . ' ' . $response->result['mothersLastName'] . ' ' . $response->result['name'],
                    'birth_date' => '',
                    'full_address' => '',
                ];
            } else {
                $pearson = [
                    'dni' => $response->result['documentNumber'],
                    'social_reason' => $response->result['socialReason'],
                    'birth_date' => $response->result['birthDate'],
                    'full_address' => '',
                ];
            }

            $res->result = $pearson;
            $res->message = 'Success';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);

        
    }

    private function validateToken($token){
        if (empty($token)){
            throw new Exception("No se encontró ningún token de autenticación.");
        }
        return ApiSign::decode($token);
    }
}
