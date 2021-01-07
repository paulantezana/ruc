<?php


class UserRole extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('user_roles', 'user_role_id', $connection);
    }

    public function insert(array $userRole, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare('INSERT INTO user_roles (description, created_at, created_user_id, state)
                                                    VALUES (:description, :created_at, :created_user_id, :state)');

            $stmt->bindParam(':description', $userRole['description']);
            $stmt->bindParam(':state', $userRole['state'], PDO::PARAM_BOOL);

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
}
