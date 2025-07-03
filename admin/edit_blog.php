<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once 'includes/db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: blogs.php");
    exit;
}

// Load blog
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();
$stmt->close();

if (!$blog) {
    echo "<div class='alert alert-danger'>Blog not found.</div>";
    exit;
}

// Init fields
$title = $blog['title'];
$seo_title = $blog['seo_title'];
$slug = $blog['slug'];
$meta_description = $blog['meta_description'];
$meta_keywords = $blog['meta_keywords'];
$topic = $blog['topic'];
$author = $blog['author'];
$content = $blog['content'];
$cover_image = $blog['cover_image'];

$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $seo_title = trim($_POST['seo_title']);
    $slug = trim($_POST['slug']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $topic = trim($_POST['topic']);
    $author = trim($_POST['author']);
    $content = $_POST['content'];

    // Validate
    if (!$title || !$topic || !$author || !$content) {
        $error = "Please fill all required fields.";
    } else {
        // File upload
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
                // Remove old image
                if ($cover_image && file_exists("uploads/$cover_image")) {
                    @unlink("uploads/$cover_image");
                }
                $cover_image = $filename;
            }
        }

        // Generate slug if not entered
        if (!$slug) {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }
        // Make slug unique (ignore current blog's own slug)
        $checkSlug = $conn->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
        $checkSlug->bind_param("si", $slug, $id);
        $checkSlug->execute();
        $checkSlug->store_result();
        if ($checkSlug->num_rows > 0) {
            $slug .= '-' . time();
        }
        $checkSlug->close();

        if (!$error) {
            $stmt = $conn->prepare("UPDATE blogs SET cover_image=?, title=?, seo_title=?, slug=?, meta_description=?, meta_keywords=?, content=?, author=?, topic=? WHERE id=?");
            $stmt->bind_param("sssssssssi", $cover_image, $title, $seo_title, $slug, $meta_description, $meta_keywords, $content, $author, $topic, $id);
            if ($stmt->execute()) {
                $success = "Blog post updated successfully!";
            } else {
                $error = "Database error: Blog post not updated.";
            }
            $stmt->close();
        }
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<!-- TinyMCE (Rich Text Editor) -->
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
        <h2 class="h4"><i class="fas fa-edit"></i> Edit Blog</h2>
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
                    <label>Cover Image</label><br>
                    <?php if($cover_image && file_exists("uploads/$cover_image")): ?>
                        <img src="uploads/<?php echo htmlspecialchars($cover_image); ?>" alt="Cover" style="width:140px;max-height:80px;object-fit:cover;border-radius:4px;margin-bottom:8px;">
                    <?php endif; ?>
                    <input type="file" name="cover_image" class="form-control-file">
                    <small class="text-muted">JPG, PNG, GIF, WEBP (max 2MB, recommended: 1000x500px). Upload to replace.</small>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Blog</button>
    </form>
</main>

<?php include('includes/footer.php'); ?>
