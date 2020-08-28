<?php

require_once(MODEL_PATH . '/Census.php');

class Api1Controller extends Controller
{
    private $connection;
    private $censusModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->censusModel = new Census($connection);
    }

    public function query()
    {
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");

        $res = new Result();
        try {
            if(!isset($_GET['ruc'])){
                throw new Exception('Ingrese un ruc');
            }
            $ruc = $_GET['ruc'];

            if(!rucIsValid($ruc)){
                throw new Exception('Formato de ruc invalido');
            }

            $census = $this->censusModel->queryByRuc($ruc);
            if($census == false){
                throw new Exception('No se encontró ningún resultado');
            }

            $res->result = $census;
            $res->message = 'Success';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function oldApi(){
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");

        $res = new Result();
        $res->errorMessage = '';
        $res->socialReason = '';
        $res->fiscalAddress = '';
        try {
            if(!isset($_GET['ruc'])){
                throw new Exception('Ingrese un ruc');
            }
            $ruc = $_GET['ruc'];

            if(!rucIsValid($ruc)){
                throw new Exception('Formato de ruc invalido');
            }

            $census = $this->censusModel->queryByRuc($ruc);
            if($census == false){
                throw new Exception('No se encontró ningún resultado');
            }

            $res->ruc =  $census['ruc'];
            $res->socialReason =  $census['social_reason'];
            $res->fiscalAddress =  $census['address'];
            $res->message = 'Success';
            $res->success = true;
        } catch (Exception $e) {
            $res->errorMessage = $e->getMessage();
        }
        echo json_encode($res);
    }
}
