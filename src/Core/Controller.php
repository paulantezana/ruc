<?php

class Controller
{
    protected function render($path, $parameter = [], $template = '', $return = false)
    {

        $content = requireToVar(VIEW_PATH . '/' . $path, $parameter);

        if ($template === '') {
            if ($return) {
                return $content;
            } else {
                echo $content;
                return;
            }
        }

        if ($return) {
            ob_start();
            require_once(VIEW_PATH . '/' . $template);
            return ob_get_clean();
        } else {
            require_once(VIEW_PATH . '/' . $template);
        }
    }

    protected function redirect($url = "")
    {
        header('Location: ' . URL_PATH . $url);
    }
}
