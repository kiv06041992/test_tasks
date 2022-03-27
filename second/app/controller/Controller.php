<?php
namespace app\controller;


use app\DB;

abstract class Controller {

    protected $dbc;
    protected $get;
    protected $post;

    public function __construct() {
        $this->dbc  = DB::getConnection();
        $this->get  = $_GET;
        $this->post = $_POST;

        if (count($this->post)) {
            foreach ($this->post as $key=>$value) {
                //We can use different ways for filtration and security
                $this->post[$key] = addslashes($value);
            }
        }
    }

    public function actionNotFound() {
        header('HTTP/1.1 404 Not Found');
        $this->showView('error');
    }

    protected function showView($view, $data = []) {
        require DIR_VIEW . '/layout/header.php';
        require DIR_VIEW . '/'.$view.'.php';
        require DIR_VIEW . '/layout/footer.php';
    }

    protected function isUser() {
        return isset($_SESSION['email']) && $_SESSION['email'] != '';
    }

    protected function goTo($path) {
        header("Location: {$path}");
    }
}