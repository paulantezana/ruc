<?php


class UserToken extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('user_tokens', 'user_token_id', $connection);
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

    public function insert(array $userToken, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare('INSERT INTO user_tokens (api_token, query_count, user_id, created_at, created_user_id)
                                                    VALUES (:api_token, :query_count, :user_id, :created_at, :created_user_id)');

            $stmt->bindParam(':api_token', $userToken['apiToken']);
            $stmt->bindValue(':query_count', 0);
            $stmt->bindParam(':user_id', $userToken['userId']);

            $stmt->bindParam(':created_at', $currentDate);
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
