<?php
namespace app\controller;
use app\controller\Controller;

class User extends  Controller{
    public function actionIndex(): void {
        if (!$this->isUser()) {
            $this->goTo('/?controller=Site&action=actionLogin');
        } else {
            $data['view']['title'] = 'User Profile';
            $data['view']['user'] = $this->getUserData();
            $this->showView('user/index', $data);
        }
    }

    public function actionChangePassword(): void {
        $data['view']['title'] = 'ChangePassword Page';

        if (isset($this->post['newPassword']) &&
            isset($this->post['newPasswordRepeat']) &&
            $this->validateNewPassword($this->post['newPassword'], $this->post['newPasswordRepeat'])) {
            if ($this->post['newPassword'] == $this->post['newPasswordRepeat']) {
                $user = $this->getUserData();
                $r = mysqli_query($this->dbc, "UPDATE `user` 
                                                SET `password` = MD5('{$this->post['newPassword']}') 
                                                WHERE  `id`='{$user['id']}';");

                if ($r) {
                    $data['view']['error'] = 'Password was changed';
                } else {
                    $data['view']['error'] = 'Password was NOT changed';
                }
            } else {
                $data['view']['error'] = 'The passwords are not same.';
            }
        }

        $this->showView('user/changePassword', $data);
    }

    private function validateNewPassword(string $newPassword, string $newPasswordRepeat): bool {
        if ($newPassword != '' &&
            $newPasswordRepeat != '') {
                return true;
        } else {
            return false;
        }
    }

    private function getUserData(): array|null {
        if ($this->isUser()) {
            $r = mysqli_query($this->dbc,
                "SELECT * FROM `user` WHERE `email` = '{$_SESSION['email']}'");
            if ($r) {
                return mysqli_fetch_assoc($r);
            }
        } else {
            return [];
        }
    }
}