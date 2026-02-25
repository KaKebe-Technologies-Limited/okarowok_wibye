<?php
/**
 * Router script for PHP built-in server
 * Handles clean URLs for the blog system
 */

// Parse the request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading/trailing slashes
$path = trim($requestUri, '/');

// Route: /?$ → index.html
if ($path === '' || $path === '/') {
    include __DIR__ . '/index.html';
    exit;
}

// Route: /about/?$ → about.html
if ($path === 'about' || $path === 'about/') {
    include __DIR__ . '/about.html';
    exit;
}

// Route: /gallery/?$ → gallery.html
if ($path === 'gallery' || $path === 'gallery/') {
    include __DIR__ . '/gallery.html';
    exit;
}

// Route: /activities/?$ → activities.html
if ($path === 'activities' || $path === 'activities/') {
    include __DIR__ . '/activities.html';
    exit;
}

// Route: /project/?$ → project.html
if ($path === 'project' || $path === 'project/') {
    include __DIR__ . '/project.html';
    exit;
}

// Route: /blog/page/([0-9]+)/?$ → blog/index.php?page=$1
if (preg_match('#^blog/page/(\d+)/?$#', $path, $matches)) {
    $_GET['page'] = $matches[1];
    include __DIR__ . '/blog/index.php';
    exit;
}

// Route: /blog/([a-z0-9-]+)/?$ → blog/post.php?slug=$1
if (preg_match('#^blog/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    include __DIR__ . '/blog/post.php';
    exit;
}

// Route: /blog/tag/([a-z0-9-]+)/?$ → blog/index.php?tag=$1
if (preg_match('#^blog/tag/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['tag'] = $matches[1];
    include __DIR__ . '/blog/index.php';
    exit;
}

// Route: /blog/?$ → blog/index.php
if ($path === 'blog' || $path === 'blog/') {
    include __DIR__ . '/blog/index.php';
    exit;
}

// Admin routes
// Route: /admin → admin/index.php (login page)
if ($path === 'admin' || $path === 'admin/') {
    include __DIR__ . '/admin/index.php';
    exit;
}

// Route: /admin/dashboard → admin/dashboard.php
if ($path === 'admin/dashboard') {
    include __DIR__ . '/admin/dashboard.php';
    exit;
}

// Route: /admin/post → admin/post.php (new post)
if ($path === 'admin/post' || $path === 'admin/post/') {
    include __DIR__ . '/admin/post.php';
    exit;
}

// Route: /admin/process → admin/process.php (form handler)
if ($path === 'admin/process' || $path === 'admin/process/') {
    include __DIR__ . '/admin/process.php';
    exit;
}

// Route: /admin/upload → admin/upload.php (image upload handler)
if ($path === 'admin/upload' || $path === 'admin/upload/') {
    include __DIR__ . '/admin/upload.php';
    exit;
}

// Serve static files directly
$file = __DIR__ . $requestUri;
if (file_exists($file) && is_file($file)) {
    return false; // Let PHP serve the file directly
}

// 404 for everything else
http_response_code(404);
include __DIR__ . '/404.php';
exit;
