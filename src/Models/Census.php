<?php
class Census extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('census', 'ruc', $connection);
    }

    public function paginate(int $page, int $limit = 20, string $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query('SELECT COUNT(*) FROM census WHERE ruc LIKE ' . "'%".$search."%'")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $stmt = $this->db->prepare("SELECT * FROM census WHERE ruc LIKE :ruc LIMIT $offset, $limit");
            $stmt->bindValue(':ruc','%'.$search.'%');
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $data = $stmt->fetchAll();

            return [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insertByRuc($census)
    {
        try {
            $stmt = $this->db->prepare('CALL insert_row_census(:ruc, :social_reason, :taxpayer_state, :domicile_condition, :ubigeo, :type_road, :name_road,
                        :zone_code, :type_zone, :number, :inside, :lot, :department, :kilometer, :address, :full_address, :last_update_sunat)');
                        
            $stmt->bindParam(':ruc', $census['ruc']);
            $stmt->bindParam(':social_reason', $census['social_reason']);
            $stmt->bindParam(':taxpayer_state', $census['taxpayer_state']);
            $stmt->bindParam(':domicile_condition', $census['domicile_condition']);
            $stmt->bindParam(':ubigeo', $census['ubigeo']);
            $stmt->bindParam(':type_road', $census['type_road']);
            $stmt->bindParam(':name_road', $census['name_road']);
            $stmt->bindParam(':zone_code', $census['zone_code']);
            $stmt->bindParam(':type_zone', $census['type_zone']);
            $stmt->bindParam(':number', $census['number']);
            $stmt->bindParam(':inside', $census['inside']);
            $stmt->bindParam(':lot', $census['lot']);
            $stmt->bindParam(':department', $census['department']);
            $stmt->bindParam(':kilometer', $census['kilometer']);
            $stmt->bindParam(':address', $census['address']);
            $stmt->bindParam(':full_address', $census['full_address']);
            $stmt->bindParam(':last_update_sunat', $census['last_update_sunat']);

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
            $stmt = $this->db->prepare('INSERT  INTO census (ruc, social_reason, taxpayer_state, domicile_condition, ubigeo, type_road, name_road,
                                                            zone_code, type_zone, number, inside, lot, department, kilometer, address, full_address,
                                                            last_update_sunat) 
                                                    VALUES (:ruc, :social_reason, :taxpayer_state, :domicile_condition, :ubigeo, :type_road, :name_road,
                                                            :zone_code, :type_zone, :number, :inside, :lot, :department, :kilometer, :address, :full_address,
                                                            :last_update_sunat)');
            $stmt->bindParam(':ruc', $census['ruc']);
            $stmt->bindParam(':social_reason', $census['social_reason']);
            $stmt->bindParam(':taxpayer_state', $census['taxpayer_state']);
            $stmt->bindParam(':domicile_condition', $census['domicile_condition']);
            $stmt->bindParam(':ubigeo', $census['ubigeo']);
            $stmt->bindParam(':type_road', $census['type_road']);
            $stmt->bindParam(':name_road', $census['name_road']);
            $stmt->bindParam(':zone_code', $census['zone_code']);
            $stmt->bindParam(':type_zone', $census['type_zone']);
            $stmt->bindParam(':number', $census['number']);
            $stmt->bindParam(':inside', $census['inside']);
            $stmt->bindParam(':lot', $census['lot']);
            $stmt->bindParam(':department', $census['department']);
            $stmt->bindParam(':kilometer', $census['kilometer']);
            $stmt->bindParam(':address', $census['address']);
            $stmt->bindParam(':full_address', $census['full_address']);
            $stmt->bindParam(':last_update_sunat', $census['last_update_sunat']);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function runSql($sql)
    {
        try {
            $stmt = $this->db->prepare('ALTER TABLE `census` DROP PRIMARY KEY;');
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            // var_dump($sql);
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            $stmt = $this->db->prepare('ALTER TABLE census ADD PRIMARY KEY (ruc)');
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function queryByRuc($ruc)
    {
        try {
            $stmt = $this->db->prepare('SELECT  ruc, social_reason, taxpayer_state, domicile_condition, ubigeo,
                                        type_road, name_road, zone_code, type_zone, number, inside, lot, department,
                                        kilometer, address, full_address, last_update_sunat
                                        FROM census
                                        WHERE ruc = :ruc');
            $stmt->bindParam(':ruc', $ruc);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
    public function truncate()
    {
        try {
            $stmt = $this->db->prepare('TRUNCATE TABLE census');

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}
