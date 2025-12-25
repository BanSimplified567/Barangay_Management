<?php
// app/controllers/Auth/ForgotPasswordController.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Send reset email or token
    $success = true;
}

$pageTitle = "Forgot Password";
require '../app/views/auth/forgot-password.php';
