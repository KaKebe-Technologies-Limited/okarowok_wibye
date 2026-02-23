<?php
require_once __DIR__ . '/../includes/blog-functions.php';
require_once __DIR__ . '/config.php';

function isLoggedIn(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_PATH . '/index.php');
        exit;
    }
}

function generateSlug(string $title): string {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/\s+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

function getAllPostsAdmin(): array {
    $postsDir = POSTS_DIR;
    
    if (!is_dir($postsDir)) return [];
    
    $posts = [];
    $files = glob($postsDir . '/*.md');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $parsed = parseFrontMatter($content);
        
        if (empty($parsed['meta'])) continue;
        
        $post = $parsed['meta'];
        $post['body'] = $parsed['body'];
        $post['file'] = basename($file);
        
        if (empty($post['slug'])) {
            $post['slug'] = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', basename($file, '.md'));
        }
        
        $posts[] = $post;
    }
    
    usort($posts, function ($a, $b) {
        return strtotime($b['date'] ?? '0') - strtotime($a['date'] ?? '0');
    });
    
    return $posts;
}

function getPostBySlugAdmin(string $slug): ?array {
    $posts = getAllPostsAdmin();
    foreach ($posts as $post) {
        if (($post['slug'] ?? '') === $slug) {
            return $post;
        }
    }
    return null;
}

function savePost(array $data): bool {
    $title = trim($data['title'] ?? '');
    $slug = !empty($data['slug']) ? trim($data['slug']) : generateSlug($title);
    $date = trim($data['date'] ?? date('Y-m-d'));
    $author = trim($data['author'] ?? '');
    $image = trim($data['image'] ?? '');
    $excerpt = trim($data['excerpt'] ?? '');
    $tags = trim($data['tags'] ?? '');
    $published = isset($data['published']) ? 'true' : 'false';
    $body = $data['content'] ?? '';
    
    $filename = $date . '-' . $slug . '.md';
    $filepath = POSTS_DIR . '/' . $filename;
    
    $frontMatter = <<<YAML
---
title: {$title}
slug: {$slug}
date: {$date}
author: {$author}
image: {$image}
excerpt: {$excerpt}
tags: {$tags}
published: {$published}
---

YAML;
    
    $content = $frontMatter . $body;
    
    return file_put_contents($filepath, $content) !== false;
}

function deletePost(string $slug): bool {
    $posts = getAllPostsAdmin();
    
    foreach ($posts as $post) {
        if (($post['slug'] ?? '') === $slug && isset($post['file'])) {
            $filepath = POSTS_DIR . '/' . $post['file'];
            if (file_exists($filepath)) {
                return unlink($filepath);
            }
        }
    }
    
    return false;
}

function clearCache(): void {
    $cacheDir = CACHE_DIR;
    
    if (!is_dir($cacheDir)) return;
    
    $files = glob($cacheDir . '/posts/*.html');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    
    $indexFile = $cacheDir . '/posts-index.json';
    if (file_exists($indexFile)) {
        unlink($indexFile);
    }
}
