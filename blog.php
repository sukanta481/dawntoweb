<?php include('includes/header.php'); ?>
<?php
require_once 'admin/includes/db.php'; // path might be different if your structure is different

// -- 1. Topics List (for sidebar) --
$topicsArr = [];
$res = $conn->query("SELECT DISTINCT topic FROM blogs WHERE topic IS NOT NULL AND topic != ''");
while($r = $res->fetch_assoc()) {
    $topicsArr[] = $r['topic'];
}

// -- 2. Blog Filter (by topic, pagination, etc.) --
$where = "1";
$params = [];
if (isset($_GET['topic']) && $_GET['topic']) {
    $where .= " AND topic = ?";
    $params[] = $_GET['topic'];
}
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 9;
$offset = ($page-1)*$per_page;

// -- 3. Get Total Blogs (for pagination) --
$sql_count = "SELECT COUNT(*) as total FROM blogs WHERE $where";
$stmt_count = $conn->prepare($sql_count);
if ($params) $stmt_count->bind_param(str_repeat('s', count($params)), ...$params);
$stmt_count->execute();
$stmt_count->bind_result($total_blogs);
$stmt_count->fetch();
$stmt_count->close();
$total_pages = ceil($total_blogs/$per_page);

// -- 4. Get Blogs --
$sql = "SELECT * FROM blogs WHERE $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

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
        min-height: 480px;
        display: flex;
        flex-direction: column;
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
    .blog-category-list a.active,
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
        .blog-card { min-height: 380px; }
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
                    <li>
                        <a href="blog.php" class="<?php if(empty($_GET['topic'])) echo 'active'; ?>">All</a>
                    </li>
                    <?php foreach($topicsArr as $topic): ?>
                        <li>
                            <a href="blog.php?topic=<?php echo urlencode($topic); ?>"
                               class="<?php if(isset($_GET['topic']) && $_GET['topic'] === $topic) echo 'active'; ?>">
                                <?php echo htmlspecialchars($topic); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Blog Posts -->
        <section class="col-lg-9">
            <div class="row">
                <?php if($result->num_rows === 0): ?>
                    <div class="col-12">
                        <div class="alert alert-warning">No blog posts found.</div>
                    </div>
                <?php endif; ?>

                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card blog-card h-100">
                            <?php if($row['cover_image']): ?>
                                <img src="admin/uploads/<?php echo htmlspecialchars($row['cover_image']); ?>"
                                     alt="<?php echo htmlspecialchars($row['title']); ?>"
                                     class="blog-card-img">
                            <?php else: ?>
                                <img src="img/blog-placeholder.jpg"
                                     alt="No image"
                                     class="blog-card-img">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <span class="badge badge-<?php
                                    // Category color
                                    $colors = ['Digital Marketing'=>'success','SEO'=>'primary','Social Media'=>'warning','Web Development'=>'info','Branding'=>'secondary','Growth Hacking'=>'dark'];
                                    echo $colors[$row['topic']] ?? 'secondary';
                                ?> mb-2" <?php if($row['topic']=='Social Media') echo 'style="color:#fff;"'; ?>>
                                    <?php echo htmlspecialchars($row['topic']); ?>
                                </span>
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text">
                                    <?php
                                        $excerpt = strip_tags($row['content']);
                                        if (mb_strlen($excerpt) > 110) {
                                            $excerpt = mb_substr($excerpt, 0, 50) . '...';
                                        }
                                        echo htmlspecialchars($excerpt);
                                    ?>
                                </p>
                                <div class="mt-auto">
                                    <a href="blog-single.php?slug=<?php echo urlencode($row['slug']); ?>" class="read-more-btn">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
            <nav aria-label="Blog Pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($page<=1) echo 'disabled'; ?>">
                        <a class="page-link" href="blog.php?<?php
                            $q = $_GET;
                            $q['page'] = $page-1;
                            echo http_build_query($q);
                        ?>">&laquo;</a>
                    </li>
                    <?php for($i=1;$i<=$total_pages;$i++): ?>
                        <li class="page-item <?php if($page==$i) echo 'active'; ?>">
                            <a class="page-link" href="blog.php?<?php
                                $q = $_GET; $q['page'] = $i; echo http_build_query($q);
                            ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if($page>=$total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="blog.php?<?php
                            $q = $_GET;
                            $q['page'] = $page+1;
                            echo http_build_query($q);
                        ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php include('includes/footer.php'); ?>
