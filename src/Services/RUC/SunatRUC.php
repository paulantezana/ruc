<?php
    Class SunatRUC{
        private $cookie_file;
        private $curl;
        private $config;

        public function __construct($config = []){
            $this->cookie_file = dirname(__FILE__) . '/cookie.txt';
            $this->curl = curl_init();

            $this->config = new stdClass();
            $this->config->workers = true;
            $this->config->establishments = true;
            $this->config->representatives = true;

            $this->SessionInit();
        }

        private function SessionInit(){
            $options = [
                CURLOPT_URL => 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/frameCriterioBusqueda.jsp',
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_COOKIEFILE => $this->cookie_file,
                CURLOPT_COOKIEJAR => $this->cookie_file,
                CURLOPT_RETURNTRANSFER => true
            ];

            curl_setopt_array($this->curl, $options);
            curl_exec($this->curl);
        }

        private function RandomNumber() : string {
            $options = [
                CURLOPT_URL => "https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random",
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_COOKIEFILE => $this->cookie_file,
                CURLOPT_COOKIEJAR => $this->cookie_file,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
            ];

            curl_setopt_array($this->curl, $options);
            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                return false;
            }
            return $response;
        }

        public function Query(string $ruc){
            $res = new Result();
            try{
                if( (strlen($ruc)!=8 && strlen($ruc)!=11) || !is_numeric($ruc) )
                {
                    throw new Exception('Formato RUC/DNI no validos');
                }

                if( strlen( $ruc )==11 && is_numeric($ruc) && !$this->RUCIsValid( $ruc ) )
                {
                    throw new Exception('RUC no valido');
                }

                if( strlen( $ruc ) == 8 && is_numeric($ruc) )
                {
                    $ruc = $this->DniToRuc($ruc);
                }

                $dataResult = $this->GeneralData($ruc);
                if (!$dataResult->success) {
                    throw new Exception($dataResult->message);
                }

//               if ($this->config->workers){
//                   $workerResult = $this->NumWorkers($ruc);
//                   if ($workerResult->success){
//                       $res->result['worker'] = $workerResult->result;
//                   }
//               }
//
//                if ($this->config->establishments){
//                    $establishmentResult = $this->Establishment($ruc);
//                    if ($establishmentResult->success){
//                        $res->result['establishment'] = $establishmentResult->result;
//                    }
//                }
//
//                if ($this->config->representatives){
//                    $legalRepresentativeResult = $this->LegalRepresentative($ruc);
//                    if ($legalRepresentativeResult->success){
//                        $res->result['reprecentant'] = $legalRepresentativeResult->result;
//                    }
//                }
                $res->result = $dataResult->result;
                $res->success = true;
            }catch (Exception $e){
                $res->message = $e->getMessage();
            }
            return $res;
        }

        private function GeneralData(string $ruc){
            $res = new Result();
            $numRand = $this->RandomNumber();
            if (!$numRand) {
                $res->message = "No se pudo conectar a sunat";
                return $res;
            }

            $dataSend = [
                'accion' => 'consPorRuc',
                'actReturn' =>  '1',
                'nroRuc' => $ruc,
                'numRnd' => $numRand
            ];

            $fields = "";
            foreach ($dataSend as $key => $value) {
                $fields .= "$key=$value" . '&';
            }
            $fields = trim($fields,'&');

            $options = [
                CURLOPT_URL => "https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias",
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_COOKIEFILE => $this->cookie_file,
                CURLOPT_COOKIEJAR => $this->cookie_file,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $fields,
            ];

            curl_setopt_array($this->curl, $options);
            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                $res->message = "No se encontraron datos suficientes";
                return $res;
            }

            $data = [];

            //RazonSocial
            $patron='/<input type="hidden" name="desRuc" value="(.*)">/';
            $output = preg_match_all($patron, $response, $matches, PREG_SET_ORDER);
            if(isset($matches[0]))
            {
                $RS = utf8_encode(str_replace('"','', ($matches[0][1])));
                $data['documentNumber'] = $ruc;
                $data['socialReason'] = trim($RS);
            }

            //Telefono
            $patron='/<td class="bgn" colspan=1>Tel&eacute;fono\(s\):<\/td>[ ]*-->\r\n<!--\t[ ]*<td class="bg" colspan=1>(.*)<\/td>/';
            $output = preg_match_all($patron, $response, $matches, PREG_SET_ORDER);
            if( isset($matches[0]) )
            {
                $data["telephone"] = trim($matches[0][1]);
            }

            // Condicion Contribuyente
            $patron='/<td class="bgn"[ ]*colspan=1[ ]*>Condici&oacute;n del Contribuyente:[ ]*<\/td>\r\n[\t]*[ ]+<td class="bg" colspan=[1|3]+>[\r\n\t[ ]+]*(.*)[\r\n\t[ ]+]*<\/td>/';
            $output = preg_match_all($patron, $response, $matches, PREG_SET_ORDER);
            if( isset($matches[0]) )
            {
                $data["condition"] = strip_tags(trim($matches[0][1]));
            }
            $busca=array(
                "tradename" 			=> "Nombre Comercial",
                "type" 						=> "Tipo Contribuyente",
                "registrationDate" 		=> "Fecha de Inscripci&oacute;n",
                "state" 					=> "Estado del Contribuyente",
                "address" 				=> "Direcci&oacute;n del Domicilio Fiscal",
                "emissionSystem" 			=> "Sistema de Emisi&oacute;n de Comprobante",
                "outdoorActivity"		=> "Actividad de Comercio Exterior",
                "accountingSystem" 		=> "Sistema de Contabilidad",
                "job" 					=> "Profesi&oacute;n u Oficio",
                "economicActivity" 		=> "Actividad\(es\) Econ&oacute;mica\(s\)",
                "electronicBroadcast" 		=> "Emisor electr&oacute;nico desde",
                "electronicProof" 	=> "Comprobantes Electr&oacute;nicos",
                "ple" 						=> "Afiliado al PLE desde"
            );
            foreach($busca as $i=>$v)
            {
                $patron='/<td class="bgn"[ ]*colspan=1[ ]*>'.$v.':[ ]*<\/td>[ ]*\r\n[\t]*[ ]+<td class="bg" colspan=[1|3]+>(.*)<\/td>/';
                $output = preg_match_all($patron, $response, $matches, PREG_SET_ORDER);
                if(isset($matches[0]))
                {
                    $data[$i] = trim(utf8_encode( preg_replace( "[\s+]"," ", ($matches[0][1]) ) ) );
                }
            }
            if( isset($data["electronicProof"]) )
            {
                $new = explode(',', $data["electronicProof"]);
                if( is_array($new))
                {
                    $data["electronicProof"] = $new;
                }
                else
                {
                    $data["electronicProof"] = array( $data["electronicProof"]);
                }
            }

            // Condicion Contribuyente
            $patron = '/<td width="(\d{2})%" colspan=1 class="bgn">Fecha de Inicio de Actividades:<\/td>\r\n[\t]*[ ]+<td class="bg" colspan=1> (.*)<\/td>/';
            $output = preg_match_all($patron, $response, $matches, PREG_SET_ORDER);
            if( isset($matches[0][2]) )
            {
                $data["startActivities"] = strip_tags(trim($matches[0][2]));
            }

            // Actividad Economica
            $patron='/<option value="00" > (.*) - (.*) <\/option>\r\n/';
            $rpta = preg_match_all($patron, $response, $matches, PREG_SET_ORDER);
            if( !empty($matches) )
            {
                $ae = array();
                foreach ($matches as $key => $value)
                {
                    $ae[] = array(
                        'ciiu' 	=> utf8_encode(trim($value[1])),
                        'description' 	=> utf8_encode(trim($value[2]))
                    );
                }
                $data["economicActivity"] = $ae;
            }

            $res->success = true;
            $res->result = $data;
            return $res;
        }

        private function NumWorkers(string $ruc )
        {
            $res = new Result();
            $url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
            $dataSend = array(
                "accion" 	=> "getCantTrab",
                "nroRuc" 	=> $ruc,
                "desRuc" 	=> ""
            );

            $fields = "";
            foreach ($dataSend as $key => $value) {
                $fields .= "$key=$value" . '&';
            }
            $fields = trim($fields,'&');

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_COOKIEFILE => $this->cookie_file,
                CURLOPT_COOKIEJAR => $this->cookie_file,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $fields,
            ];

            curl_setopt_array($this->curl, $options);
            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                $pattern = "/<td align='center'>(.*)-(.*)<\/td>[\t|\s|\n]+<td align='center'>(.*)<\/td>[\t|\s|\n]+<td align='center'>(.*)<\/td>[\t|\s|\n]+<td align='center'>(.*)<\/td>/";
                $output = preg_match_all($pattern, $response, $matches, PREG_SET_ORDER);
                if( count($matches) > 0 )
                {
                    $numberOfWorker = array();
                    $i = 1;
                    foreach( $matches as $obj )
                    {
                        $numberOfWorker[]=array(
                            "periodo" 				=> $obj[1]."-".$obj[2],
                            "anio" 					=> $obj[1],
                            "mes" 					=> $obj[2],
                            "total_trabajadores" 	=> $obj[3],
                            "pensionista" 			=> $obj[4],
                            "prestador_servicio" 	=> $obj[5]
                        );
                    }
                    $res->result = $numberOfWorker;
                    $res->success = true;
                    return $res;
                }
            }
            return $res;
        }

        private function Establishment( string $ruc )
        {
            $res = new Result();
            $url = 'https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
            $dataSend = array(
                'nroRuc' => $ruc,
                'accion' => 'getLocAnex',
                'desRuc' 	=> ''
            );

            $fields = "";
            foreach ($dataSend as $key => $value) {
                $fields .= "$key=$value" . '&';
            }
            $fields = trim($fields,'&');

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_COOKIEFILE => $this->cookie_file,
                CURLOPT_COOKIEJAR => $this->cookie_file,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $fields,
            ];

            curl_setopt_array($this->curl, $options);
            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                $pattern = '/<td align="[center|left]+"[ ]*class=bg>\r\n[ ]*[\r\n]*[ ]*(.*)<\/td>/';
                $output = preg_match_all($pattern, $response, $matches);
                if( !empty($matches[1]) )
                {
                    if( count($matches[1])%4 == 0 )
                    {
                        $nuevo = array_chunk( $matches[1], 4 );
                        $establecimientos = array();
                        foreach($nuevo as $value)
                        {
                            $establecimientos[]=array(
                                'codigo' 				=> utf8_encode(trim( $value[0] )),
                                'tipo' 					=> utf8_encode(trim( $value[1] )),
                                'Direccion' 			=> utf8_encode(trim( preg_replace('/[ ]*-/', ' -', $value[2]) )),
                                'activida_economica'	=> utf8_encode(trim( $value[3] ))
                            );
                        }

                        $res->result = $establecimientos;
                        $res->success = true;
                        return $res;
                    }
                }
            }
            return $res;
        }

        private function LegalRepresentative( $ruc )
        {
            $res = new Result();
            $url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
            $dataSend = array(
                "accion" 	=> "getRepLeg",
                "nroRuc" 	=> $ruc,
                "desRuc" 	=> ""
            );

            $fields = "";
            foreach ($dataSend as $key => $value) {
                $fields .= "$key=$value" . '&';
            }
            $fields = trim($fields,'&');

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_COOKIEFILE => $this->cookie_file,
                CURLOPT_COOKIEJAR => $this->cookie_file,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $fields,
            ];

            curl_setopt_array($this->curl, $options);
            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                $pattern = '/<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="center">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>/';
                $output = preg_match_all($pattern, $response, $matches, PREG_SET_ORDER);
                if( count($matches) > 0 )
                {
                    $representantes_legales = array();
                    $i = 1;
                    foreach( $matches as $obj )
                    {
                        $representantes_legales[]=array(
                            "tipodoc" 				=> trim($obj[1]),
                            "numdoc" 				=> trim($obj[2]),
                            "nombre" 				=> utf8_encode(trim($obj[3])),
                            "cargo" 				=> utf8_encode(trim($obj[4])),
                            "desde" 				=> trim($obj[5]),
                        );
                    }

                    $res->result = $representantes_legales;
                    $res->success = true;
                    return $res;
                }
            }
            return $res;
        }

        private function DniToRuc($dni)
        {
            if ($dni!="" || strlen($dni) == 8)
            {
                $sum = 0;
                $hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
                $sum = 5; // 10[NRO_DNI]X (1*5)+(0*4)
                for( $i=2; $i<10; $i++ )
                {
                    $sum += ( $dni[$i-2] * $hash[$i] ); //3,2,7,6,5,4,3,2
                }
                $whole = (int)($sum/11);

                $digit = 11 - ( $sum - $whole*11);

                if ($digit == 10)
                {
                    $digit = 0;
                }
                else if ($digit == 11)
                {
                    $digit = 1;
                }
                return "10".$dni.$digit;
            }
            return false;
        }

        private function RUCIsValid($valor)
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
    }
