<?php

require_once(MODEL_PATH . '/User.php');

class HomeController extends Controller
{
    private $connection;
    private $userModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function home()
    {
        try {
            $this->render('dashboard.view.php', [], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/basic.layout.php');
        }
    }

    public function getGlobalInfo()
    {
        $res = new Result();
        try {
            $user = $this->userModel->getById($_SESSION[SESS_KEY]);

            $res->result = [
                'user' => [
                    'userId' => $user['user_id'],
                    'email' => $user['email'],
                    'avatar' => $user['avatar'],
                    'userName' => $user['user_name'],
                    'userRole' => $user['user_role_id'],
                ],
            ];
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
}
