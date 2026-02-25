<?php
/**
 * Blog Sitemap Generator
 * 
 * Generates an XML sitemap for all blog posts.
 * Access this file directly to generate the sitemap XML.
 * 
 * Usage: php sitemap-blog.php or access via browser
 */

// Get the base URL - detect from environment or use default
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'okarowok-wibye.local';
$baseUrl = $protocol . '://' . $host;

// Include the blog functions
require_once __DIR__ . '/includes/blog-functions.php';

// Get all published posts
$posts = getPublishedPosts();

// Build XML content
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Add blog index page
$xml .= '  <url>' . "\n";
$xml .= '    <loc>' . htmlspecialchars($baseUrl . '/blog/') . '</loc>' . "\n";
$xml .= '    <changefreq>weekly</changefreq>' . "\n";
$xml .= '    <priority>0.8</priority>' . "\n";
$xml .= '  </url>' . "\n";

// Add each blog post
foreach ($posts as $post) {
    $slug = $post['slug'] ?? '';
    $date = $post['date'] ?? '';
    
    // Use file modification time as fallback for lastmod
    $filePath = POSTS_DIR . '/' . ($post['file'] ?? '');
    if (!empty($post['file']) && file_exists($filePath)) {
        $lastmod = date('c', filemtime($filePath));
    } elseif (!empty($date)) {
        // Use post date in ISO 8601 format
        $lastmod = date('c', strtotime($date));
    } else {
        // Current date as fallback
        $lastmod = date('c');
    }
    
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars($baseUrl . '/blog/' . $slug . '/') . '</loc>' . "\n";
    $xml .= '    <lastmod>' . $lastmod . '</lastmod>' . "\n";
    $xml .= '    <changefreq>monthly</changefreq>' . "\n";
    $xml .= '    <priority>0.6</priority>' . "\n";
    $xml .= '  </url>' . "\n";
}

$xml .= '</urlset>';

// Output the XML
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: max-age=3600, must-revalidate');
echo $xml;
