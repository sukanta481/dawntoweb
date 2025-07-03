<?php include('includes/header.php'); ?>

<style>
    .blog-header {
        background: #FFA91A;
        color: #fff;
        padding: 60px 0 30px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    .blog-title {
        font-size: 3rem;
        font-weight: 700;
        letter-spacing: -1px;
        margin-bottom: 0;
    }
    .blog-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 18px rgba(40, 55, 71, 0.10);
        transition: box-shadow 0.2s;
        margin-bottom: 30px;
    }
    .blog-card:hover {
        box-shadow: 0 6px 32px rgba(40, 55, 71, 0.20);
    }
    .blog-card-img {
        border-radius: 20px 20px 0 0;
        max-height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .blog-category-list {
        list-style: none;
        padding: 0;
    }
    .blog-category-list li {
        margin-bottom: 10px;
    }
    .blog-category-list a {
        color: #1976D2;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .blog-category-list a:hover {
        color: #43A047;
    }
    .read-more-btn {
        background: #1976D2;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 22px;
        font-weight: 500;
        transition: background 0.2s;
    }
    .read-more-btn:hover {
        background: #43A047;
        color: #fff;
    }
    @media (max-width: 767.98px) {
        .blog-header {
            padding: 32px 0 15px 0;
        }
        .blog-title {
            font-size: 2rem;
        }
    }
</style>

<div class="blog-header">
    <h1 class="blog-title">Our Blog</h1>
    <p style="font-size:1.2rem;font-weight:400;max-width:600px;margin:auto;">
        Insights, guides, and tips on digital marketing, business growth, and the web.
    </p>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar (Categories/Topics) -->
        <aside class="col-lg-3 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3" style="color:#1976D2;">Topics</h5>
                <ul class="blog-category-list">
                    <li><a href="#">All</a></li>
                    <li><a href="#">Digital Marketing</a></li>
                    <li><a href="#">SEO</a></li>
                    <li><a href="#">Social Media</a></li>
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">Branding</a></li>
                    <li><a href="#">Growth Hacking</a></li>
                </ul>
            </div>
        </aside>

        <!-- Blog Posts -->
        <section class="col-lg-9">
            <div class="row">
                <!-- Blog Card Example 1 -->
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card blog-card">
                        <img src="img/blog-1.jpg" alt="Blog Post" class="blog-card-img">
                        <div class="card-body">
                            <span class="badge badge-success mb-2">Digital Marketing</span>
                            <h5 class="card-title">How to Build Trust for Your Brand Online</h5>
                            <p class="card-text">Discover actionable ways to build credibility and trust through digital marketing strategies...</p>
                            <a href="blog-single.php?id=1" class="read-more-btn">Read More</a>
                        </div>
                    </div>
                </div>
                <!-- Blog Card Example 2 -->
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card blog-card">
                        <img src="img/blog-2.jpg" alt="Blog Post" class="blog-card-img">
                        <div class="card-body">
                            <span class="badge badge-primary mb-2">SEO</span>
                            <h5 class="card-title">SEO Trends for 2025</h5>
                            <p class="card-text">Stay ahead of the competition with the latest trends in search engine optimization for this year...</p>
                            <a href="blog-single.php?id=2" class="read-more-btn">Read More</a>
                        </div>
                    </div>
                </div>
                <!-- Blog Card Example 3 -->
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card blog-card">
                        <img src="img/blog-3.jpg" alt="Blog Post" class="blog-card-img">
                        <div class="card-body">
                            <span class="badge badge-warning mb-2" style="color:#fff;">Social Media</span>
                            <h5 class="card-title">Instagram Growth Tactics for Agencies</h5>
                            <p class="card-text">Learn the best-kept secrets of growing your business profile on Instagram organically...</p>
                            <a href="blog-single.php?id=3" class="read-more-btn">Read More</a>
                        </div>
                    </div>
                </div>
                <!-- More cards can be added dynamically -->
            </div>
            <!-- Pagination (static example) -->
            <nav aria-label="Blog Pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </nav>
        </section>
    </div>
</div>

<?php include('includes/footer.php'); ?>
