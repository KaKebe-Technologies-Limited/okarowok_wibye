<?php
session_start();
require_once __DIR__ . '/functions.php';
requireLogin();

$posts = getAllPostsAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Figtree', sans-serif;
            background: #f5f7fa;
        }
        .admin-header {
            background: #fff;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            color: #333;
            font-size: 24px;
            font-weight: 700;
        }
        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
            font-family: 'Figtree', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            color: #fff;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 69, 96, 0.3);
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }
        .btn-danger {
            background: #dc3545;
            color: #fff;
            padding: 8px 12px;
            font-size: 12px;
        }
        .btn-success {
            background: #28a745;
            color: #fff;
            padding: 8px 12px;
            font-size: 12px;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
            padding: 8px 12px;
            font-size: 12px;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .card-header {
            padding: 24px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h2 {
            color: #333;
            font-size: 20px;
            font-weight: 700;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 16px 24px;
            text-align: left;
        }
        th {
            background: #f8f9fa;
            color: #555;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background: #fafafa;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-published {
            background: #d4edda;
            color: #155724;
        }
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            color: #777;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: #ddd;
        }
        .empty-state p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1><i class="fas fa-cog"></i> Admin Panel</h1>
        <div class="header-actions">
            <a href="<?php echo ADMIN_PATH; ?>/post.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Post</a>
            <a href="<?php echo ADMIN_PATH; ?>/process.php?action=logout" class="btn btn-secondary"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-file-alt"></i> All Posts</h2>
                <span style="color: #777; font-size: 14px;"><?php echo count($posts); ?> post(s)</span>
            </div>
            
            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No posts found. Create your first post!</p>
                    <a href="<?php echo ADMIN_PATH; ?>/post.php" class="btn btn-primary">Create Post</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($post['title'] ?? 'Untitled'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($post['date'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($post['author'] ?? ''); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo (!empty($post['published']) && $post['published'] === true) ? 'status-published' : 'status-draft'; ?>">
                                            <?php echo (!empty($post['published']) && $post['published'] === true) ? 'Published' : 'Draft'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo ADMIN_PATH; ?>/post.php?slug=<?php echo urlencode($post['slug'] ?? ''); ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="<?php echo ADMIN_PATH; ?>/process.php?action=delete&slug=<?php echo urlencode($post['slug'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');"><i class="fas fa-trash"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
