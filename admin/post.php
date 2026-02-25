<?php
session_start();
require_once __DIR__ . '/functions.php';
requireLogin();

$slug = $_GET['slug'] ?? '';
$post = null;
$isEditing = false;

if (!empty($slug)) {
    $post = getPostBySlugAdmin($slug);
    if ($post) {
        $isEditing = true;
    }
}

$title = $post['title'] ?? '';
$postSlug = $post['slug'] ?? '';
$date = $post['date'] ?? date('Y-m-d');
$author = $post['author'] ?? '';
$image = $post['image'] ?? '';
$excerpt = $post['excerpt'] ?? '';
$tags = is_array($post['tags'] ?? '') ? implode(', ', $post['tags']) : ($post['tags'] ?? '');
$published = $post['published'] ?? false;
$content = $post['body'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditing ? 'Edit Post' : 'New Post'; ?> - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/parsedown@1.9.0/Parsedown.min.js"></script>
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
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-danger {
            background: #dc3545;
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }
        .form-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .form-group label .required {
            color: #e94560;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Figtree', sans-serif;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #e94560;
            box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.1);
        }
        .form-group textarea {
            min-height: 300px;
            resize: vertical;
        }
        .slug-field {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .slug-field input {
            flex: 1;
        }
        .slug-field .btn {
            white-space: nowrap;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .checkbox-group label {
            margin: 0;
            cursor: pointer;
        }
        .preview-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
        .preview-section h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .preview-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            min-height: 200px;
            color: #333;
            line-height: 1.7;
        }
        .preview-content h1, .preview-content h2, .preview-content h3 {
            margin-top: 20px;
            margin-bottom: 12px;
            color: #333;
        }
        .preview-content p {
            margin-bottom: 16px;
        }
        .preview-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 16px 0;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .help-text {
            font-size: 12px;
            color: #777;
            margin-top: 6px;
        }
        .image-upload-wrapper {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .image-preview {
            width: 100%;
            max-width: 300px;
            min-height: 150px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8f9fa;
        }
        .image-preview img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .image-preview .placeholder {
            text-align: center;
            color: #999;
            padding: 20px;
        }
        .image-preview .placeholder i {
            font-size: 32px;
            margin-bottom: 8px;
            display: block;
        }
        .image-upload-wrapper .btn {
            align-self: flex-start;
        }
        .image-upload-wrapper input[type="text"] {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1><i class="fas fa-<?php echo $isEditing ? 'edit' : 'plus'; ?>"></i> <?php echo $isEditing ? 'Edit Post' : 'New Post'; ?></h1>
        <div class="header-actions">
            <a href="<?php echo ADMIN_PATH; ?>/dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-card">
            <form method="POST" action="<?php echo ADMIN_PATH; ?>/process.php?action=save" id="postForm">
                <?php if ($isEditing): ?>
                    <input type="hidden" name="old_slug" value="<?php echo htmlspecialchars($postSlug); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required oninput="generateSlugFromTitle()">
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date <span class="required">*</span></label>
                        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="slug">Slug <span class="required">*</span></label>
                        <div class="slug-field">
                            <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($postSlug); ?>" required>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="generateSlugFromTitle()">Generate</button>
                        </div>
                        <div class="help-text">URL-friendly version of the title</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="image">Featured Image</label>
                        <div class="image-upload-wrapper">
                            <div class="image-preview" id="imagePreview">
                                <?php if (!empty($image)): ?>
                                    <img src="/assets/img/blog/<?php echo htmlspecialchars($image); ?>" alt="Preview" id="previewImg">
                                <?php else: ?>
                                    <div class="placeholder" id="imagePlaceholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Click or drag to upload</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="imageFile" accept="image/jpeg,image/png,image/gif,image/webp" style="display: none;">
                            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" placeholder="Image filename" readonly>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('imageFile').click()">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="insertImageInContent()">
                                <i class="fas fa-image"></i> Insert in Content
                            </button>
                            <div class="help-text">Upload an image or enter filename manually</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($tags); ?>" placeholder="tag1, tag2, tag3">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <input type="text" id="excerpt" name="excerpt" value="<?php echo htmlspecialchars($excerpt); ?>" placeholder="Short description for the post">
                </div>
                
                <div class="form-group">
                    <label for="content">Content (Markdown) <span class="required">*</span></label>
                    <textarea id="content" name="content" required oninput="updatePreview()"><?php echo htmlspecialchars($content); ?></textarea>
                </div>
                
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="published" name="published" <?php echo $published ? 'checked' : ''; ?>>
                    <label for="published">Publish this post</label>
                </div>
                
                <div class="preview-section">
                    <h3><i class="fas fa-eye"></i> Preview</h3>
                    <div class="preview-content" id="preview">
                        <?php echo renderMarkdown($content); ?>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="<?php echo ADMIN_PATH; ?>/dashboard.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Post</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function generateSlugFromTitle() {
            var title = document.getElementById('title').value;
            var slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            document.getElementById('slug').value = slug;
        }
        
        function updatePreview() {
            var content = document.getElementById('content').value;
            var preview = document.getElementById('preview');
            
            if (typeof Parsedown !== 'undefined') {
                var parsedown = new Parsedown();
                preview.innerHTML = parsedown.setSafeMode(true).makeHtml(content);
            } else {
                preview.innerHTML = '<p style="color: #999;">Preview loading...</p>';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
        
        // Image upload handling
        const imageFileInput = document.getElementById('imageFile');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        imageFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Show loading state
            const placeholder = document.getElementById('imagePlaceholder');
            if (placeholder) {
                placeholder.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            }
            
            const formData = new FormData();
            formData.append('image', file);
            
            fetch('<?php echo ADMIN_PATH; ?>/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Extract filename from URL
                    const filename = data.url.split('/').pop();
                    imageInput.value = filename;
                    
                    // Update preview
                    imagePreview.innerHTML = '<img src="' + data.url + '" alt="Preview" id="previewImg">';
                } else {
                    alert('Upload failed: ' + data.error);
                    // Restore placeholder
                    imagePreview.innerHTML = '<div class="placeholder" id="imagePlaceholder"><i class="fas fa-cloud-upload-alt"></i><span>Click or drag to upload</span></div>';
                }
            })
            .catch(error => {
                alert('Upload error: ' + error);
                imagePreview.innerHTML = '<div class="placeholder" id="imagePlaceholder"><i class="fas fa-cloud-upload-alt"></i><span>Click or drag to upload</span></div>';
            });
        });

        function insertImageInContent() {
            const filename = imageInput.value;
            if (!filename) {
                alert('Please upload an image first');
                return;
            }
            
            const imageUrl = '/assets/img/blog/' + filename;
            const markdownImage = '![' + filename + '](' + imageUrl + ')';
            
            // Insert at cursor position in content textarea
            const contentTextarea = document.getElementById('content');
            const cursorPos = contentTextarea.selectionStart;
            const textBefore = contentTextarea.value.substring(0, cursorPos);
            const textAfter = contentTextarea.value.substring(cursorPos);
            
            contentTextarea.value = textBefore + '\n' + markdownImage + '\n' + textAfter;
            contentTextarea.focus();
            
            // Update preview
            updatePreview();
        }
    </script>
</body>
</html>
