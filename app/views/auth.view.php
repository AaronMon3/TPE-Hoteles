<?php

class AuthView {

    public function showLogin($error = null) {
        $error = $error;
        require __DIR__ . '/../../templates/admin/login.phtml';
    }

}
