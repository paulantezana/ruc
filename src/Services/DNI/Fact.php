<?php


class FactDni
{
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function Query(string $dni)
    {
        $res = new Result();
        try{
            if( strlen($dni)!=8 )
            {
                throw new Exception('EL DNI debe contener 8 dígitos');
            }
            $options = [
                CURLOPT_URL => "https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10447915125&password=985511933&nro_documento=" . $dni,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
            ];
            curl_setopt_array($this->curl, $options);

            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                throw new Exception('No se encontraron datos suficientes');
            }
            $data = json_decode($response,true);
            $resultado = $data['result'];
            if (strlen($resultado['Paterno']) == 0 || strlen($resultado['Nombre']) == 0){
                throw new Exception('No se encontró ningun dato con el DNI: ' . $dni);
            }

            $res->success = true;
            $res->result = [
                'name' => $resultado['Nombre'],
                'motherLastName' => $resultado['Materno'],
                'lastName' => $resultado['Paterno'],
                'documentNumber' => $dni,
            ];
        }catch (Exception $e){
            $res->message = $e->getMessage();
        }
        return $res;
    }
}
