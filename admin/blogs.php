<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once 'includes/db.php';

// Handle delete (POST request for security)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $del_id = intval($_POST['delete_id']);
    // Delete image file if exists
    $img_stmt = $conn->prepare("SELECT cover_image FROM blogs WHERE id = ?");
    $img_stmt->bind_param("i", $del_id);
    $img_stmt->execute();
    $img_stmt->bind_result($del_img);
    if ($img_stmt->fetch() && $del_img && file_exists("uploads/$del_img")) {
        @unlink("uploads/$del_img");
    }
    $img_stmt->close();
    // Delete blog record
    $del_stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $del_stmt->bind_param("i", $del_id);
    $del_stmt->execute();
    $del_stmt->close();
    // Redirect to self to clear POST and refresh list
    header("Location: blogs.php?deleted=1");
    exit;
}

// FILTERS
$filters = [];
$params = [];
$sql = "SELECT * FROM blogs WHERE 1";

// Date range
if (!empty($_GET['from'])) {
    $filters[] = "created_at >= ?";
    $params[] = $_GET['from'] . " 00:00:00";
}
if (!empty($_GET['to'])) {
    $filters[] = "created_at <= ?";
    $params[] = $_GET['to'] . " 23:59:59";
}
// Author
if (!empty($_GET['author'])) {
    $filters[] = "author = ?";
    $params[] = $_GET['author'];
}
// Topic
if (!empty($_GET['topic'])) {
    $filters[] = "topic = ?";
    $params[] = $_GET['topic'];
}
// Keyword search (title, content, SEO fields)
if (!empty($_GET['search'])) {
    $filters[] = "(title LIKE ? OR content LIKE ? OR seo_title LIKE ? OR meta_description LIKE ? OR meta_keywords LIKE ?)";
    for ($i=0; $i<5; $i++) $params[] = '%' . $_GET['search'] . '%';
}
if ($filters) {
    $sql .= " AND " . implode(" AND ", $filters);
}
$sql .= " ORDER BY created_at DESC";

// Prepare/execute
$stmt = $conn->prepare($sql);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// For author/topic dropdowns
$authors = $conn->query("SELECT DISTINCT author FROM blogs WHERE author IS NOT NULL AND author != ''");
$topics = $conn->query("SELECT DISTINCT topic FROM blogs WHERE topic IS NOT NULL AND topic != ''");
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 px-4 admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4"><i class="fas fa-blog"></i> Blog Management</h2>
        <!-- Floating Add Button (mobile) -->
        <a href="add_blog.php" class="btn btn-success shadow rounded-circle d-md-none"
           style="position:fixed;bottom:30px;right:30px;z-index:999;width:60px;height:60px;display:flex;align-items:center;justify-content:center;font-size:2rem;">
            <i class="fas fa-plus"></i>
        </a>
        <!-- Regular Add Button (desktop) -->
        <a href="add_blog.php" class="btn btn-success d-none d-md-block">
            <i class="fas fa-plus"></i> Add Blog
        </a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Blog deleted successfully.</div>
    <?php endif; ?>

    <!-- Filters -->
    <form class="mb-4" method="get">
        <div class="form-row align-items-end">
            <div class="col-md-2 mb-2">
                <label>From</label>
                <input type="date" name="from" class="form-control" value="<?php echo htmlspecialchars($_GET['from'] ?? ''); ?>">
            </div>
            <div class="col-md-2 mb-2">
                <label>To</label>
                <input type="date" name="to" class="form-control" value="<?php echo htmlspecialchars($_GET['to'] ?? ''); ?>">
            </div>
            <div class="col-md-2 mb-2">
                <label>Author</label>
                <select name="author" class="form-control">
                    <option value="">All</option>
                    <?php while($a = $authors->fetch_assoc()): ?>
                        <option <?php if(isset($_GET['author']) && $_GET['author']==$a['author']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($a['author']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Topic</label>
                <select name="topic" class="form-control">
                    <option value="">All</option>
                    <?php while($t = $topics->fetch_assoc()): ?>
                        <option <?php if(isset($_GET['topic']) && $_GET['topic']==$t['topic']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($t['topic']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label>Search</label>
                <input type="text" name="search" class="form-control" placeholder="Keyword..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-1 mb-2">
                <button class="btn btn-primary btn-block" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>

    <!-- Blog List Table -->
    <div class="table-responsive">
    <table class="table table-hover table-bordered bg-white shadow-sm">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Cover</th>
                <th>Title <i class="fas fa-info-circle" title="SEO fields in tooltip"></i></th>
                <th>Topic</th>
                <th>Author</th>
                <th>Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td>
                    <?php if($row['cover_image']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['cover_image']); ?>"
                             style="width:60px;height:40px;object-fit:cover;border-radius:4px;" alt="cover">
                    <?php else: ?>
                        <span class="text-muted small">No Image</span>
                    <?php endif; ?>
                </td>
                <td>
                    <span title="SEO Title: <?php echo htmlspecialchars($row['seo_title'] ?? ''); ?>&#10;Slug: <?php echo htmlspecialchars($row['slug'] ?? ''); ?>&#10;Meta Description: <?php echo htmlspecialchars($row['meta_description'] ?? ''); ?>&#10;Meta Keywords: <?php echo htmlspecialchars($row['meta_keywords'] ?? ''); ?>">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($row['topic']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                <td class="text-center">
                    <a href="edit_blog.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></a>
                    <form action="blogs.php" method="post" style="display:inline;" onsubmit="return confirm('Delete this blog?');">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</main>

<?php include('includes/footer.php'); ?>
