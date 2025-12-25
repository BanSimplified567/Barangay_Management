<?php
// app/controllers/CertificationController.php

$pageTitle = "Certifications";

$subAction = $_GET['sub'] ?? 'index';

switch ($subAction) {
    case 'request':
        require '../app/views/certifications/request.php';
        break;

    case 'approve':
        require '../app/views/certifications/approve.php';
        break;

    default:
        require '../app/views/certifications.php';
}
