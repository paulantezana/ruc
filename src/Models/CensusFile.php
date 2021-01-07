<?php
class CensusFile extends Model
{

    public function __construct(PDO $connection)
    {
        parent::__construct('census_files', 'census_file_id', $connection);
    }

    public function getAllIsNotProcess(){
        try {
            $stmt = $this->db->prepare('SELECT * FROM census_files WHERE is_process = 0');
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function truncate()
    {
        try {
            $stmt = $this->db->prepare('TRUNCATE TABLE census_files');

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function insert($census)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO census_files (file_path,file_type) VALUES(:file_path,:file_type)');
            $stmt->bindParam(':file_path', $census['filePath'], PDO::PARAM_STR_CHAR);
            $stmt->bindParam(':file_type', $census['fileType'], PDO::PARAM_STR_CHAR);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}
