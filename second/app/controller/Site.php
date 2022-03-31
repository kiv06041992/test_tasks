<?php
namespace app\controller;

use app\DB;
use app\model\User;

class Site extends Controller {

    public function actionIndex(): void {
        $data['view']['title'] = "Main Page";
        $this->showView('index', $data);
    }

    public function actionLogin(): void {
        $data['view']['title'] = "Login Page";

        if (isset($this->post['email']) &&
            $this->post['email'] != '' &&
            isset($this->post['password']) &&
            $this->post['password'] != '') {

            $modelUser = new User();
            $r = $modelUser->read(['email' => $this->post['email'], 'password' => md5($this->post['password'])]);
            if (count($r) > 0) {
                if ($this->login($this->post['email'])) {
                    $this->goTo('/?controller=User&action=actionIndex');
                }
            } else {
                $data['view']['error'] = 'Some problems with login.';
            }
        }

        $this->showView('login', $data);
    }

    public function actionLogout(): void {
        $this->logout();
        $this->goTo('/');
    }

    public function actionRegistration(): void {
        $data['view']['title'] = "Registration Page";

        if (isset($this->post["name"]) &&
            $this->post["name"] != '' &&
            isset($this->post["email"]) &&
            $this->post["email"] != '' &&
            isset($this->post["password"]) &&
            $this->post["password"] != '') {

            $modelUser = new User();
            if (count($modelUser->read(['email' => $this->post['email']])) > 1) {
                $data['view']['error'] = 'This email is used already';
            } else {
                $modelUser->init(['name' => $this->post['name'], 'email' => $this->post['email'], 'password' => md5($this->post['password'])]);
                if ($modelUser->create() && $this->login($this->post['email'])) {
                    $this->goTo('/?controller=User&action=actionIndex');
                } else {
                    $data['view']['error'] = 'User was not created.';
                }
            }

        }

        $this->showView('registration', $data);
    }

    public function actionRecoveryPassword(): void {
        $data['view']['title'] = "RecoveryPassword Page";

        if (isset($this->get['code'])) {
            //only for this session
            if ($_SESSION['recovery']['code'] == $this->get['code']) {
                $email = $_SESSION['recovery']['email'];
                unset($_SESSION['recovery']);
                $this->login($email);
            } else {
                $data['view']['error'] = 'We did not find this recovery code.';
            }
        }

        if (isset($this->get['email'])) {
            $modelUser = new User();
            $r = $modelUser->read(['email' => $this->get['email']]);
            if (count($r) > 0) {
                $_SESSION['recovery']['code'] = md5($this->get['email'] . rand());
                $_SESSION['recovery']['email'] = $this->get['email'];
                $data['view']['code'] = $_SESSION['recovery']['code'];

            } else {
                $data['view']['error'] = "We did not find '{$this->get['email']}' email";
            }
        }

        $this->showView('recoveryPassword', $data);
    }

    private function login(string $email = ''): bool {
        if ($email != '') {
            $_SESSION['email'] = $email;
            return true;
        } else {
            return false;
        }
    }

    private function logout(): bool {
        session_destroy();
        return true;
    }

}