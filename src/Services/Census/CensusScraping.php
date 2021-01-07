<?php
require_once(__DIR__ . '/util.php');

class CensusScraping
{
    private $filePath;
    private $fileUrl;
    private $fileData;

    public function __construct()
    {
        $this->filePath = CENSUS_PATH . '/census.zip';
        if(APP_DEV){
            $this->fileUrl = 'https://assets.paulantezana.com/padron_reducido_ruc.zip';
        } else {
            $this->fileUrl = 'http://www2.sunat.gob.pe/padron_reducido_ruc.zip';
        }
        $this->fileData = CENSUS_PATH . '/files.json';
    }

    public function dowloand($param = [])
    {
        $res = new Result();
        try {
            $startTime = microtime(true);

            // file_put_contents($this->fileUrl, fopen($this->filePath, 'r'));

            // ------------------
            $filePath = fopen($this->filePath, 'w+');
            if ($filePath === false) {
                throw new Exception('Could not open: ' . $this->filePath);
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FILE, $filePath);
            curl_setopt($curl, CURLOPT_TIMEOUT, 28800);
            curl_setopt($curl, CURLOPT_URL, $this->fileUrl);
            if(isset($param['enabledAgent']) && $param['enabledAgent'] == true){
                curl_setopt($curl, CURLOPT_USERAGENT, '"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.11) Gecko/20071204 Ubuntu/7.10 (gutsy) Firefox/2.0.0.11');
            }
            if(isset($param['enabledVerifyHost'])){
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $param['enabledVerifyHost']);
            }
            if(isset($param['enabledVerfyPer'])){
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $param['enabledVerfyPer']);
            }
            
            curl_exec($curl);
            if (curl_errno($curl)) {
                throw new Exception(curl_error($curl));
            }

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            fclose($filePath);

            if ($statusCode != 200) {
                throw new Exception('Curl status Code: ' . $statusCode);
            }

            $endTime = microtime(true);

            $res->message = 'Downloaded success! At ' . ($endTime - $startTime) . ' seconds';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }

    public function unZip()
    {
        $res = new Result();
        try {
            $startTime = microtime(true);

            if (!is_file($this->filePath)) {
                throw new Exception('The ' . $this->filePath . ' file was not found');
            }

            $zip = new ZipArchive();
            $zipRes = $zip->open($this->filePath);
            if (!$zipRes == true) {
                throw new Exception('Could not open compressed file. Code: ' . $zipRes);
            }

            $extracState = $zip->extractTo(CENSUS_PATH);
            $zip->close();

            $endTime = microtime(true);

            if ($extracState) {
                $res->message = 'The file is successfully unzipped! At ' . ($endTime - $startTime) . ' seconds';
            } else {
                $res->message = 'The file could not be unzipped! At ' . ($endTime - $startTime) . ' seconds';
            }

            $res->success = $extracState;
            $res->path = $this->filePath;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }

    public function clear(){
        if(is_dir(CENSUS_PATH . '/files')){
            $files = glob(CENSUS_PATH . '/files' . '/*.*');
            foreach($files as $file){
                is_file(unlink($file));
            }
            rmdir(CENSUS_PATH . '/files');
        }

        mkdir(CENSUS_PATH . '/files');
    }

    public function splitFile($splitCount = 120000)
    {
        $res = new Result();
        try {
            $startTime = microtime(true);

            $this->clear();

            if (!is_file(CENSUS_PATH . '/padron_reducido_ruc.txt')) {
                throw new Exception('The ' . $this->filePath . ' file was not found');
            }

            $filePath = fopen(CENSUS_PATH . '/padron_reducido_ruc.txt', 'r');
            if ($filePath === false) {
                throw new Exception('The txt file could not be opened');
            }

            $files = [];
            $counter = 0;
            $fileCounter = 0;
            $currentFile = null;

            while (!feof($filePath)) {
                $textLine = fgets($filePath);
                if($counter === 0){
                    $counter++;
                    continue;
                }
                if ($counter % $splitCount === 0 || $counter === 1) {
                    $newFilePath = CENSUS_PATH . '/files/file' . ($fileCounter + 1) . '.txt';

                    if (gettype($currentFile) === 'resource') {
                        fclose($currentFile);
                    }

                    $currentFile =  fopen($newFilePath, 'a');
                    array_push($files, $newFilePath);
                    $fileCounter++;
                }

                fwrite($currentFile, $textLine);
                $counter++;
            }
            fclose($filePath);

            $endTime = microtime(true);

            $res->result = $files;
            $res->message = 'Split success! At ' . ($endTime - $startTime) . ' seconds';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }

    // public function splitFileSql($splitCount = 9000, $splitCountRow = 400, $type = 'plain')
    public function splitFileSql($splitCount = 5, $splitCountRow = 2, $type = 'plain')
    {
        // 9000
        $res = new Result();
        try {
            $startTime = microtime(true);

            $this->clear();

            if (!is_file(CENSUS_PATH . '/padron_reducido_ruc.txt')) {
                throw new Exception('The ' . $this->filePath . ' file was not found');
            }

            $filePath = fopen(CENSUS_PATH . '/padron_reducido_ruc.txt', 'r');
            if ($filePath === false) {
                throw new Exception('The txt file could not be opened');
            }

            $files = [];
            $counter = 0;
            $counterTxt = 0;
            $fileCounter = 0;
            $currentFile = null;
            $sql = "INSERT  INTO census (ruc, social_reason, taxpayer_state, domicile_condition, ubigeo, type_road, name_road, zone_code, type_zone, number, inside, lot, department, kilometer, address, full_address, last_update_sunat) VALUES ";

            while (!feof($filePath)) {
                $textLine = fgets($filePath);
                if($counter === 0){
                    $counter++;
                    continue;
                }

                $dataRow = explode('|', utf8_encode($textLine));
                if(count($dataRow) <= 10){
                    continue;
                }

                $entity = [];
                $entity['ruc'] = $dataRow[0];
                $entity['social_reason'] = $dataRow[1];
                $entity['taxpayer_state'] = $dataRow[2];
                $entity['domicile_condition'] = $dataRow[3];
                $entity['ubigeo'] = $dataRow[4];
                $entity['type_road'] = $dataRow[5];
                $entity['name_road'] = $dataRow[6];
                $entity['zone_code'] = $dataRow[7];
                $entity['type_zone'] = $dataRow[8];
                $entity['number'] = $dataRow[9];
                $entity['inside'] = $dataRow[10];
                $entity['lot'] = $dataRow[11];
                $entity['department'] = $dataRow[12];
                $entity['kilometer'] = $dataRow[13];
                $entity['address'] = '';
                $entity['full_address'] = '';
                $entity['last_update_sunat'] = '';

                $entity['address'] .= $dataRow[5] != '-' ? $dataRow[5] : '';
                $entity['address'] .= $dataRow[6] != '-' ? $dataRow[6] : '';
                $entity['address'] .= $dataRow[9] != '-' ? 'NRO. ' . $dataRow[9] : '';
                $entity['address'] .= $dataRow[10] != '-' ? 'INT. ' . $dataRow[10] : '';
                $entity['address'] .= $dataRow[11] != '-' ? 'LT. ' . $dataRow[11] : '';
                $entity['address'] .= $dataRow[13] != '-' ? 'MZ. ' . $dataRow[13] : '';
                $entity['address'] .= $dataRow[7] != '-' ? $dataRow[7] : '';
                $entity['address'] .= $dataRow[8] != '-' ? $dataRow[8] : '';
                $entity['address'] .= $dataRow[12] != '-' ? 'DPTO. ' . $dataRow[12] : '';
                $entity['address'] .= $dataRow[14] != '-' ? 'KM. ' . $dataRow[14] : '';

                if ((($counter + 1) % $splitCount) == 0) {
                    $sql .= "('".addslashes($entity['ruc'])."','".addslashes($entity['social_reason'])."','".addslashes($entity['taxpayer_state'])."','".addslashes($entity['domicile_condition'])."','".addslashes($entity['ubigeo'])."','".addslashes($entity['type_road'])."','".addslashes($entity['name_road'])."','".addslashes($entity['zone_code'])."','".addslashes($entity['type_zone'])."','".addslashes($entity['number'])."','".addslashes($entity['inside'])."','".addslashes($entity['lot'])."','".addslashes($entity['department'])."','".addslashes($entity['kilometer'])."','".addslashes($entity['address'])."','".addslashes($entity['full_address'])."','".addslashes($entity['last_update_sunat'])."');";
                    // $txt .= "(" . $datos[0] . ",'" . $rs . "','" . $direccion2 . "');";
                } else {
                    $sql .= "('".addslashes($entity['ruc'])."','".addslashes($entity['social_reason'])."','".addslashes($entity['taxpayer_state'])."','".addslashes($entity['domicile_condition'])."','".addslashes($entity['ubigeo'])."','".addslashes($entity['type_road'])."','".addslashes($entity['name_road'])."','".addslashes($entity['zone_code'])."','".addslashes($entity['type_zone'])."','".addslashes($entity['number'])."','".addslashes($entity['inside'])."','".addslashes($entity['lot'])."','".addslashes($entity['department'])."','".addslashes($entity['kilometer'])."','".addslashes($entity['address'])."','".addslashes($entity['full_address'])."','".addslashes($entity['last_update_sunat'])."'),";
                    // $txt .= "(" . $datos[0] . ",'" . $rs . "','" . $direccion2 . "'),";
                }

                $counter++;
                if (($counter % $splitCount) == 0) {
                    $fileCounter++;
                    if (($fileCounter % $splitCountRow) == 0) {
                        $sql = substr($sql, 0, -1);
                        $sql .= ";";
                        $sql .= PHP_EOL;

                        $newFilePath = CENSUS_PATH . '/files/file' . ($fileCounter + 1) . '.sql';

                        $currentFile = fopen($newFilePath, "a");
                        array_push($files, $newFilePath);
                        
                        fwrite($currentFile, $sql);
                        fclose($currentFile);
                        unset($sql);
                        $sql = '';
                    }
                    $sql .= PHP_EOL;
                    $sql = "INSERT  INTO census (ruc, social_reason, taxpayer_state, domicile_condition, ubigeo, type_road, name_road, zone_code, type_zone, number, inside, lot, department, kilometer, address, full_address, last_update_sunat) VALUES ";
                }
            }

            $sql = substr($sql, 0, -1);
            $sql .= ";";
            $sql .= PHP_EOL;

            $newFilePath = CENSUS_PATH . '/files/file_Main.sql';
            $currentFile = fopen($newFilePath, "a");
            array_push($files, $newFilePath);

            fwrite($currentFile, $sql);
            fclose($currentFile);
            $sql = '';

            $endTime = microtime(true);

            $res->result = $files;
            $res->message = 'Split success! At ' . ($endTime - $startTime) . ' seconds';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }
}
