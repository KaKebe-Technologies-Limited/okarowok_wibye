<?php
session_start();
require_once __DIR__ . '/functions.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'save':
        requireLogin();
        handleSave();
        break;
    case 'delete':
        requireLogin();
        handleDelete();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        header('Location: ' . ADMIN_PATH . '/index.php');
        exit;
}

function handleLogin(): void {
    require_once __DIR__ . '/config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        
        if (password_verify($password, ADMIN_PASSWORD)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_login_time'] = time();
            header('Location: ' . ADMIN_PATH . '/dashboard.php');
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid password. Please try again.';
            header('Location: ' . ADMIN_PATH . '/index.php');
            exit;
        }
    }
    
    header('Location: ' . ADMIN_PATH . '/index.php');
    exit;
}

function handleSave(): void {
    $title = trim($_POST['title'] ?? '');
    $slug = !empty($_POST['slug']) ? trim($_POST['slug']) : generateSlug($title);
    $date = trim($_POST['date'] ?? date('Y-m-d'));
    $author = trim($_POST['author'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $content = $_POST['content'] ?? '';
    $published = isset($_POST['published']);
    $oldSlug = $_POST['old_slug'] ?? '';
    
    if (empty($title)) {
        $_SESSION['error'] = 'Title is required.';
        header('Location: ' . ADMIN_PATH . '/post.php');
        exit;
    }
    
    if (empty($content)) {
        $_SESSION['error'] = 'Content is required.';
        header('Location: ' . ADMIN_PATH . '/post.php');
        exit;
    }
    
    if (!empty($oldSlug) && $oldSlug !== $slug) {
        deletePost($oldSlug);
    }
    
    $data = [
        'title' => $title,
        'slug' => $slug,
        'date' => $date,
        'author' => $author,
        'image' => $image,
        'excerpt' => $excerpt,
        'tags' => $tags,
        'published' => $published,
        'content' => $content
    ];
    
    if (savePost($data)) {
        clearCache();
        $_SESSION['success'] = 'Post saved successfully.';
        header('Location: ' . ADMIN_PATH . '/dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to save post. Please try again.';
        header('Location: ' . ADMIN_PATH . '/post.php' . ($slug ? '?slug=' . urlencode($slug) : ''));
        exit;
    }
}

function handleDelete(): void {
    $slug = $_GET['slug'] ?? '';
    
    if (empty($slug)) {
        $_SESSION['error'] = 'No post specified for deletion.';
        header('Location: ' . ADMIN_PATH . '/dashboard.php');
        exit;
    }
    
    if (deletePost($slug)) {
        clearCache();
        $_SESSION['success'] = 'Post deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete post. The post may not exist.';
    }
    
    header('Location: ' . ADMIN_PATH . '/dashboard.php');
    exit;
}

function handleLogout(): void {
    session_destroy();
    header('Location: ' . ADMIN_PATH . '/index.php');
    exit;
}
