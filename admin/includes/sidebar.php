<!-- Sidebar -->
<nav class="col-md-2 d-none d-md-block admin-sidebar sidebar py-4">
    <div class="sidebar-sticky">
        <h4 class="text-center mb-4"><i class="fas fa-cogs"></i> Dawntoweb</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='dashboard.php'){echo ' active';} ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='pages.php'){echo ' active';} ?>" href="pages.php">
                    <i class="fas fa-file-alt"></i> Pages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='blogs.php'){echo ' active';} ?>" href="blogs.php">
                    <i class="fas fa-blog"></i> Blogs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='leads.php'){echo ' active';} ?>" href="leads.php">
                    <i class="fas fa-users"></i> Leads
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
