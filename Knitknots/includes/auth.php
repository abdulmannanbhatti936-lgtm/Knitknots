<?php
require_once __DIR__ . '/config.php';

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Require admin login
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header("Location: " . SITE_URL . "/admin/login.php");
        exit();
    }
}

/**
 * Log out admin
 */
function logoutAdmin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    session_destroy();
    header("Location: " . SITE_URL . "/admin/login.php");
    exit();
}