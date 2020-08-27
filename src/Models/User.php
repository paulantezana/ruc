<?php

class User extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('user', 'user_id', $connection);
    }

    public function paginate($page, $limit = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM user WHERE user_name LIKE '%{$search}%'")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $stmt = $this->db->prepare("SELECT user.*, ur.description as user_role FROM user
                                        INNER JOIN user_role ur on user.user_role_id = ur.user_role_id
                                        WHERE user.user_name LIKE :search LIMIT $offset, $limit");
            $stmt->bindValue(':search', '%' . $search . '%');

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $data = $stmt->fetchAll();

            $paginate = [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
            return $paginate;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function login($user, $password)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM user WHERE email = :email AND password = :password LIMIT 1');
            $stmt->bindParam(':email', $user);
            $stmt->bindValue(':password', sha1(trim($password)));

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $dataUser = $stmt->fetch();

            if ($dataUser == false) {
                $stmt = $this->db->prepare('SELECT * FROM user WHERE user_name = :user_name AND password = :password LIMIT 1');
                $stmt->bindParam(':user_name', $user);
                $stmt->bindValue(':password', sha1(trim($password)));

                if (!$stmt->execute()) {
                    throw new Exception($stmt->errorInfo()[2]);
                }
                $dataUser = $stmt->fetch();

                if ($dataUser == false) {
                    throw new Exception('El usuario o contraseñas es icorrecta');
                }
            }

            if ($dataUser['state'] == '0') {
                throw new Exception('Usted no esta autorizado para ingresar al sistema.');
            }

            return $dataUser;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insert($user, $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO user (user_name, email, password, full_name, user_role_id, created_at, created_user_id)
                                                    VALUES (:user_name, :email, :password, :full_name, :user_role_id, :created_at, :created_user_id)');

            $stmt->bindValue(':user_name', $user['userName']);
            $stmt->bindValue(':email', $user['email']);
            $stmt->bindValue(':password', sha1($user['password']));
            $stmt->bindValue(':full_name', $user['fullName']);
            $stmt->bindParam(':user_role_id', $user['userRoleId']);

            $stmt->bindParam(':created_at', $currentDate);
            $stmt->bindParam(':created_user_id', $userId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }
}
