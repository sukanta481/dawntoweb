<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dawntoweb - Digital Marketing Agency</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Digital Marketing Agency, Dawntoweb" name="keywords">
    <meta content="Dawntoweb is your digital growth partner." name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .navbar {
            padding-top: 18px;
            padding-bottom: 18px;
        }
        .navbar-brand img {
            height: 70px;
            max-height: 100px;
            width: auto;
            margin-top: 0;
            margin-bottom: 0;
            transition: height 0.3s;
        }
        .logo-light { display: block; }
        .logo-dark { display: none; }
        .navbar-brand.dark-logo .logo-light { display: none; }
        .navbar-brand.dark-logo .logo-dark { display: block; }
        @media (max-width: 991.98px) {
            .navbar-brand img {
                height: 50px;
            }
        }
    </style>
</head>

<body>
<!-- Navbar Start -->
<div class="container-fluid bg-white position-relative">
    <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center">
            <!-- Light Background Logo -->
            <img src="img/logo.png" alt="Dawntoweb Logo" class="logo-light">
            <!-- Dark Background Logo -->
            <img src="img/logo-dark.png" alt="Dawntoweb Logo Dark" class="logo-dark">
        </a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ml-auto py-0 pr-3 border-right">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="service.php" class="nav-item nav-link">Services</a>
                <a href="price.php" class="nav-item nav-link">Prices</a>
                <a href="project.php" class="nav-item nav-link">Projects</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="team.php" class="dropdown-item">Meet The Team</a>
                        <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link">Contact</a>
            </div>
            <div class="d-none d-lg-flex align-items-center pl-4">
                <i class="fa fa-2x fa-mobile-alt text-primary mr-3"></i>
                <div>
                    <h6 class="text-body text-uppercase mb-1"><small>Call Anytime</small></h6>
                    <h6 class="m-0">+91 8961090050</h6>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<!-- Optional: JS to toggle dark logo if navbar goes dark (e.g., on scroll) -->
<script>
    window.addEventListener('scroll', function() {
        var navbar = document.querySelector('.navbar');
        var brand = document.querySelector('.navbar-brand');
        // Example: switch logo after scrolling 50px, or if you manually add a dark class to navbar
        if (window.scrollY > 50) {
            navbar.classList.add('bg-dark', 'navbar-dark');
            brand.classList.add('dark-logo');
        } else {
            navbar.classList.remove('bg-dark', 'navbar-dark');
            brand.classList.remove('dark-logo');
        }
    });
</script>
