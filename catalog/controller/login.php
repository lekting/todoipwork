<?php

class ControllerLogin extends Controller {

    /*
    * Processing login of user
    */
    public function login() {
        if(!isset($_POST['username']) || !isset($_POST['password']))
            return 'empty';

        $username = $this->db->escape($_POST['username']);
        $password = $this->db->escape($_POST['password']);

        if($username !== 'admin' || $password !== '123')
            return 'incorrect';

        $_SESSION['logged'] = true;
        return 'success';
    }

    /*
    * Processing logout of user
    */
    public function logout() {
        session_destroy();

        header('Location: /');
    }

}