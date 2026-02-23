<?php
/**
 * Router script for PHP built-in server
 * Handles clean URLs for the blog system
 */

// Parse the request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading/trailing slashes
$path = trim($requestUri, '/');

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

// Serve static files directly
$file = __DIR__ . $requestUri;
if (file_exists($file) && is_file($file)) {
    return false; // Let PHP serve the file directly
}

// 404 for everything else
http_response_code(404);
echo "404 Not Found";
