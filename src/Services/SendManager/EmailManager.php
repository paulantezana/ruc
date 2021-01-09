<?php

require_once __DIR__ . '/HtmlTemplate.php';

class EmailManager
{
    public static function send($from, $to, $subject, $message)
    {
        $res = new Result();
        try {
            if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email del destinataio es invalido');
            }
            if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email de origen es invalido');
            }

            $headers = "From: " . APP_NAME . " <{$from}>" . "\r\n";
            // $headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
            // $headers .= "CC: susan@example.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            // HTML MESSAGE START
            $message = HtmlTemplate::layout($message);
            $sendSuccess = mail($to, $subject, $message, $headers);
            if($sendSuccess){
                $res->success = true;
                $res->message = 'El correo se envió exitosamente.';
            } else {
                $res->success = false;
                $res->message = 'No se pudo enviar el correo electrónico.';
            }
        } catch (Exception $e) {
            $res->success = false;
            $res->message = $e->getMessage();
        }
        return $res;
    }
}
