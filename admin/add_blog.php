<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once 'includes/db.php';

$error = $success = "";
$title = $seo_title = $slug = $meta_description = $meta_keywords = $topic = $author = $content = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize
    $title = trim($_POST['title']);
    $seo_title = trim($_POST['seo_title']);
    $slug = trim($_POST['slug']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $topic = trim($_POST['topic']);
    $author = trim($_POST['author']);
    $content = $_POST['content']; // Don't trim - keep HTML!

    // Validate required fields
    if (!$title || !$topic || !$author || !$content) {
        $error = "Please fill all required fields.";
    } else {
        // Handle file upload
        $cover_image = "";
        if (!empty($_FILES['cover_image']['name'])) {
            $target_dir = "uploads/";
            $filename = time() . "_" . basename($_FILES["cover_image"]["name"]);
            $target_file = $target_dir . $filename;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["cover_image"]["tmp_name"]);
            if ($check === false) {
                $error = "File is not an image.";
            } elseif ($_FILES["cover_image"]["size"] > 2*1024*1024) {
                $error = "Image file too large (max 2MB).";
            } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $error = "Only JPG, JPEG, PNG, GIF, WEBP files allowed.";
            } elseif (!move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                $error = "Sorry, there was an error uploading your file.";
            } else {
                $cover_image = $filename;
            }
        }

        // Generate slug if not entered
        if (!$slug) {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }
        // Make slug unique
        $checkSlug = $conn->prepare("SELECT id FROM blogs WHERE slug = ?");
        $checkSlug->bind_param("s", $slug);
        $checkSlug->execute();
        $checkSlug->store_result();
        if ($checkSlug->num_rows > 0) {
            $slug .= '-' . time();
        }
        $checkSlug->close();

        if (!$error) {
            $stmt = $conn->prepare("INSERT INTO blogs (cover_image, title, seo_title, slug, meta_description, meta_keywords, content, author, topic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $cover_image, $title, $seo_title, $slug, $meta_description, $meta_keywords, $content, $author, $topic);
            if ($stmt->execute()) {
                $success = "Blog post added successfully!";
                // Reset form values
                $title = $seo_title = $slug = $meta_description = $meta_keywords = $topic = $author = $content = "";
            } else {
                $error = "Database error: Blog post not saved.";
            }
            $stmt->close();
        }
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<!-- Place the TinyMCE script in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/c6dnzoialg8zo3sb0ymi2pq3fwr09mpe8pqy4vtef212k4gf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea[name="content"]',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    height: 320,
    menubar: false,
    branding: false
  });
</script>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 px-4 admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4"><i class="fas fa-plus"></i> Add Blog</h2>
        <a href="blogs.php" class="btn btn-secondary">Back to Blogs</a>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($title); ?>">
                </div>
                <div class="form-group">
                    <label>SEO Title</label>
                    <input type="text" name="seo_title" class="form-control" value="<?php echo htmlspecialchars($seo_title); ?>">
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($slug); ?>" placeholder="auto-generated if blank">
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?php echo htmlspecialchars($meta_description); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Meta Keywords (comma separated)</label>
                    <input type="text" name="meta_keywords" class="form-control" value="<?php echo htmlspecialchars($meta_keywords); ?>">
                </div>
                <div class="form-group">
                    <label>Topic <span class="text-danger">*</span></label>
                    <input type="text" name="topic" class="form-control" required value="<?php echo htmlspecialchars($topic); ?>">
                </div>
                <div class="form-group">
                    <label>Author <span class="text-danger">*</span></label>
                    <input type="text" name="author" class="form-control" required value="<?php echo htmlspecialchars($author); ?>">
                </div>
                <div class="form-group">
                    <label>Content <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" rows="8" required><?php echo htmlspecialchars($content); ?></textarea>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" class="form-control-file">
                    <small class="text-muted">JPG, PNG, GIF, WEBP (max 2MB, recommended: 1000x500px)</small>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Blog</button>
    </form>
</main>

<?php include('includes/footer.php'); ?>
