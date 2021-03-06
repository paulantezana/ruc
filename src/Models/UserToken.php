<?php


class UserToken extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('user_tokens', 'user_token_id', $connection);
    }

    public function getAllByUserId($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT ut.*, ta.title AS tariff_title, ta.max_query_count FROM user_tokens AS ut
                                        LEFT JOIN tariffs AS ta ON ut.tariff_id = ta.tariff_id
                                        WHERE user_id = :user_id");
            $stmt->bindParam(":user_id", $userId);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getByUserId($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_tokens WHERE user_id = :user_id LIMIT 1");
            $stmt->bindParam(":user_id", $userId);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getTariffById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT ut.*, ta.title AS tariff_title, ta.description AS tariff_description, ta.max_query_count 
                                        FROM user_tokens AS ut
                                        INNER JOIN tariffs AS ta ON ut.tariff_id = ta.tariff_id AND ta.state = 1
                                        WHERE ut.user_token_id = :user_token_id LIMIT 1");
            $stmt->bindParam(":user_token_id", $id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insert(array $userToken, int $userId)
    {
        try {
            $currentDateTime = date('Y-m-d H:i:s');
            $currentDate = date('Y-m-d');

            $stmt = $this->db->prepare('INSERT INTO user_tokens (api_token, start_date, query_count, user_id, created_at, created_user_id)
                                                    VALUES (:api_token, :start_date, :query_count, :user_id, :created_at, :created_user_id)');

            $stmt->bindParam(':api_token', $userToken['apiToken']);
            $stmt->bindValue(':start_date', $currentDate);
            $stmt->bindValue(':query_count', 0);
            $stmt->bindParam(':user_id', $userToken['userId']);

            $stmt->bindParam(':created_at', $currentDateTime);
            $stmt->bindParam(':created_user_id', $userId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return  (int) $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function counterUp(int $userTokenId, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare('UPDATE user_tokens SET query_count = (query_count + 1), updated_at = :updated_at, updated_user_id = :updated_user_id WHERE user_token_id = :user_token_id');

            $stmt->bindParam(':user_token_id', $userTokenId);

            $stmt->bindParam(':updated_at', $currentDate);
            $stmt->bindParam(':updated_user_id', $userId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return  (int) $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }
}
