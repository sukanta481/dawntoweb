<?php
// session_start(); // Enable on all pages after login system is added
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dawntoweb Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 CDN (or your local file) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="assets/css/admin-style.css">
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background: #f7f8fa;}
        .admin-sidebar { background: #1976D2; min-height: 100vh; color: #fff; }
        .admin-sidebar a { color: #fff; font-weight: 500; }
        .admin-sidebar .nav-link.active, .admin-sidebar .nav-link:hover { background: #155ca1; }
        .admin-header { background: #fff; border-bottom: 1px solid #eee; }
        .admin-content { padding: 2rem; }
        @media (max-width: 991.98px) {
            .admin-sidebar { min-height: auto; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
