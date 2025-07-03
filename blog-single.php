<?php
require_once 'includes/db.php';

// Support loading by slug (SEO-friendly)
$blog = null;
if (isset($_GET['slug']) && $_GET['slug']) {
    $slug = $_GET['slug'];
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE slug=?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
} elseif (isset($_GET['id']) && intval($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
}

// Blog not found
if (!$blog) {
    include('includes/header.php');
    echo "<div class='container py-5'><div class='alert alert-danger'>Blog not found.</div></div>";
    include('includes/footer.php');
    exit;
}

// SEO meta tags
$seo_title = $blog['seo_title'] ?: $blog['title'];
$meta_description = $blog['meta_description'] ?: mb_strimwidth(strip_tags($blog['content']), 0, 150, '...');
$meta_keywords = $blog['meta_keywords'];
$cover_image_url = $blog['cover_image']
    ? "admin/uploads/" . htmlspecialchars($blog['cover_image'])
    : "img/default-blog.png";

// Date formatted
$publish_date = date('d M Y', strtotime($blog['created_at']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($seo_title); ?> | Dawntoweb Blog</title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add Open Graph for social sharing -->
    <meta property="og:title" content="<?php echo htmlspecialchars($seo_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta property="og:image" content="<?php echo $cover_image_url; ?>">
    <meta property="og:type" content="article">
    <?php include('includes/head_links.php'); // Bootstrap, custom CSS, favicon, etc. ?>
    <style>
        .blog-single-header {
            background: #FFA91A;
            color: #222;
            padding: 64px 0 30px 0;
            text-align: center;
        }
        .blog-single-title {
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 14px;
            color: #111;
        }
        .blog-single-meta {
            color: #444 !important;
            font-size: 1rem;
            margin-bottom: 16px;
        }
        .blog-single-cover {
            max-width: 1000px;
            width: 100%;
            max-height: 420px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(40, 55, 71, 0.14);
            margin: 0 auto 32px auto;
            display: block;
        }
        .blog-single-content {
            font-size: 1.18rem;
            line-height: 1.75;
            color: #222;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 18px rgba(40, 55, 71, 0.07);
            padding: 36px 24px;
            margin-top: -52px;
            margin-bottom: 50px;
        }
        @media (max-width:767.98px) {
            .blog-single-header { padding: 32px 0 15px 0; }
            .blog-single-title { font-size: 1.6rem; }
            .blog-single-content { padding: 16px 8px; margin-top: 0; }
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="blog-single-header">
    <h1 class="blog-single-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
    <div class="blog-single-meta">
        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($blog['author']); ?></span>
        &nbsp; | &nbsp;
        <span><i class="fas fa-calendar"></i> <?php echo $publish_date; ?></span>
        <?php if ($blog['topic']): ?>
            &nbsp; | &nbsp;
            <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($blog['topic']); ?></span>
        <?php endif; ?>
    </div>
    <?php if ($blog['cover_image']): ?>
        <img src="<?php echo $cover_image_url; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-single-cover">
    <?php endif; ?>
</div>

<div class="container">
    <div class="blog-single-content">
        <?php echo $blog['content']; // Content is rich HTML from TinyMCE ?>
    </div>
    <a href="blog.php" class="btn btn-outline-primary mb-5"><i class="fas fa-arrow-left"></i> Back to Blog</a>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
