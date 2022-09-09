<?php

namespace app\Controllers;

use app\Models\User;

class LoginController {

    private function generateToken() {
        $bytes = random_bytes(32);
        $token = bin2hex($bytes);
        $_SESSION['csrf'] = $token;
        return $token;
    }

    public function showLogin() {
        return view('login', ['token' => $this->generateToken()]);
    }

    public function showSignup() {
        
    }

    public function login() {
        $token = $_POST['_csrf'] ?? '';
        $email = $_POST['emaillogin'] ?? '';
        $password = $_POST['passwordlogin'] ?? '';
        $result = $this->verifylogin($email, $password, $token);
    }

    private function verifylogin($email, $password, $token) {

        $result = [
            'message' => 'USER LOGGED IN',
            'success' => true
        ];
        if ($token !== $_SESSION['csrf']) {
            $result = [
                'message' => 'TOKEN MISMATCH',
                'success' => false
            ];
            return $result;
        }
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $result = [
                'message' => 'WRONG EMAIL',
                'success' => false
            ];
            return $result;
        }

        if (strlen($password) < 6) {
            $result = [
                'message' => 'PASSWORD TO SMALL',
                'success' => false
            ];
            return $result;
        }
        $user = new User();
        $resemail = $user->getUserByEmail($email);
        var_dump($resemail);
        die;
        if (!$resemail) {
            $result = [
                'message' => 'USER NOT FOUND',
                'success' => false
            ];
            return $result;
        }

        if (!password_verify($password, $resemail['password'])) {
            $result = [
                'message' => 'WRONG PASSWORD',
                'success' => false
            ];
            return $result;
        }
        $result['user'] = $resemail;
        return $result;
    }

}
