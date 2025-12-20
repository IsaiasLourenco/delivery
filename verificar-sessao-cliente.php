<?php
session_start();

echo json_encode([
    'cliente_identificado' => isset($_SESSION['cliente_id'])
]);
