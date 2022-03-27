<?php
namespace app\controller;

use app\DB;

class Site extends Controller {

    public function actionIndex() {
        $data['view']['title'] = "Main Page";
        $this->showView('index', $data);
    }

    public function actionLogin() {
        $data['view']['title'] = "Login Page";

        if (isset($this->post['email']) &&
            $this->post['email'] != '' &&
            isset($this->post['password']) &&
            $this->post['password'] != '') {
            $r = mysqli_query($this->dbc,
                        "SELECT * FROM user 
                                WHERE `email` = '{$this->post['email']}' AND 
                                      `password` = MD5('{$this->post['password']}')");

            if (mysqli_fetch_assoc($r)) {
                if ($this->login($this->post['email'])) {
                    $this->goTo('/?controller=User&action=actionIndex');
                }
            } else {
                $data['view']['error'] = 'Some problems with login.';
            }
        }

        $this->showView('login', $data);
    }

    public function actionLogout() {
        $this->logout();
        $this->goTo('/');
    }

    public function actionRegistration() {
        $data['view']['title'] = "Registration Page";

        if (isset($this->post["name"]) &&
            $this->post["name"] != '' &&
            isset($this->post["email"]) &&
            $this->post["email"] != '' &&
            isset($this->post["password"]) &&
            $this->post["password"] != '') {

            $r = mysqli_query($this->dbc,
            "SELECT * FROM `user` WHERE `email` = '{$this->post['email']}'");
            if (mysqli_fetch_assoc($r)) {
                $data['view']['error'] = 'This email is used already';
            } else {
                $r = mysqli_query($this->dbc,
                    "INSERT INTO `user` (`name`, `email`, `password`) 
                                VALUES ('{$this->post['name']}', 
                                       '{$this->post['email']}', 
                                       MD5('{$this->post['password']}'))");
                if ($r && $this->login($this->post['email'])) {
                    $this->goTo('/?controller=User&action=actionIndex');
                } else {
                    $data['view']['error'] = 'User was not created.';
                }
            }

        }

        $this->showView('registration', $data);
    }

    public function actionRecoveryPassword() {
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
            $r = mysqli_query($this->dbc, "SELECT * FROM `user` WHERE `email` = '{$this->get['email']}'");
            if (mysqli_fetch_assoc($r)) {
                $_SESSION['recovery']['code'] = md5($this->get['email'] . rand());
                $_SESSION['recovery']['email'] = $this->get['email'];
                $data['view']['code'] = $_SESSION['recovery']['code'];

            } else {
                $data['view']['error'] = "We did not find '{$this->get['email']}' email";
            }
        }

        $this->showView('recoveryPassword', $data);
    }

    private function login($email = '') {
        if ($email != '') {
            $_SESSION['email'] = $email;
            return true;
        } else {
            return false;
        }
    }

    private function logout() {
        session_destroy();
        return true;
    }

}