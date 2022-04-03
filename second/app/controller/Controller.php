<?php
namespace app\controller;



abstract class Controller {

    protected array $get = [];
    protected array $post = [];

    public function __construct() {
        $this->get  = $_GET;
        $this->post = $_POST;

        if (count($this->post)) {
            foreach ($this->post as $key=>$value) {
                //We can use different ways for filtration and security
                $this->post[$key] = addslashes($value);
            }
        }
    }

    public function actionNotFound(): void {
        header('HTTP/1.1 404 Not Found');
        $this->showView('error');
    }

    protected function showView(string $view, array $data = []): void {
        require DIR_VIEW . '/layout/header.php';
        require DIR_VIEW . '/'.$view.'.php';
        require DIR_VIEW . '/layout/footer.php';
    }

    protected function isUser(): bool {
        return isset($_SESSION['email']) && $_SESSION['email'] != '';
    }

    protected function goTo(string $path): void {
        header("Location: {$path}");
    }
}