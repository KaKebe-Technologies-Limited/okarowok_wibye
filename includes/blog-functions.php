<?php
/**
 * Okarowok Wibye Acel — Flat-File Blog Engine
 * 
 * Core functions for the PHP flat-file blog system.
 * No database required — posts are Markdown files in content/posts/
 */

// Load Parsedown
require_once __DIR__ . '/parsedown/Parsedown.php';

// Configuration constants
require_once __DIR__ . '/../admin/config.php';
define('POSTS_PER_PAGE', 6);

/**
 * Parse YAML-like front matter from a Markdown file.
 * 
 * Front matter format:
 * ---
 * title: Post Title
 * slug: post-slug
 * date: 2026-02-15
 * author: Author Name
 * image: image-filename.jpg
 * excerpt: Short description...
 * tags: tag1, tag2, tag3
 * published: true
 * ---
 * 
 * @param string $content Raw file content
 * @return array ['meta' => [...], 'body' => 'markdown content']
 */
function parseFrontMatter(string $content): array {
    $meta = [];
    $body = $content;
    
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $content, $matches)) {
        $frontMatter = $matches[1];
        $body = $matches[2];
        
        foreach (explode("\n", $frontMatter) as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $colonPos = strpos($line, ':');
            if ($colonPos === false) continue;
            
            $key = trim(substr($line, 0, $colonPos));
            $value = trim(substr($line, $colonPos + 1));
            
            // Handle boolean values
            if (strtolower($value) === 'true') $value = true;
            elseif (strtolower($value) === 'false') $value = false;
            
            // Handle tags as array
            if ($key === 'tags' && is_string($value)) {
                $value = array_map('trim', explode(',', $value));
            }
            
            $meta[$key] = $value;
        }
    }
    
    return ['meta' => $meta, 'body' => $body];
}

/**
 * Get all posts from the content/posts directory.
 * Returns posts sorted by date (newest first).
 * 
 * @param string|null $postsDir Override posts directory path
 * @return array Array of post data arrays
 */
function getAllPosts(?string $postsDir = null): array {
    $dir = $postsDir ?? POSTS_DIR;
    
    if (!is_dir($dir)) return [];
    
    $posts = [];
    $files = glob($dir . '/*.md');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $parsed = parseFrontMatter($content);
        
        if (empty($parsed['meta'])) continue;
        
        $post = $parsed['meta'];
        $post['body'] = $parsed['body'];
        $post['file'] = basename($file);
        
        // Generate slug from filename if not in front matter
        if (empty($post['slug'])) {
            $post['slug'] = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', basename($file, '.md'));
        }
        
        $posts[] = $post;
    }
    
    // Sort by date descending (newest first)
    usort($posts, function ($a, $b) {
        return strtotime($b['date'] ?? '0') - strtotime($a['date'] ?? '0');
    });
    
    return $posts;
}

/**
 * Get only published posts.
 * 
 * @param string|null $postsDir Override posts directory path
 * @return array Array of published post data
 */
function getPublishedPosts(?string $postsDir = null): array {
    $posts = getAllPosts($postsDir);
    return array_filter($posts, function ($post) {
        return !empty($post['published']) && $post['published'] === true;
    });
}

/**
 * Get a single post by its slug.
 * 
 * @param string $slug The post slug
 * @param string|null $postsDir Override posts directory path
 * @return array|null Post data or null if not found
 */
function getPostBySlug(string $slug, ?string $postsDir = null): ?array {
    $posts = getPublishedPosts($postsDir);
    foreach ($posts as $post) {
        if (($post['slug'] ?? '') === $slug) {
            return $post;
        }
    }
    return null;
}

/**
 * Get paginated posts.
 * 
 * @param int $page Current page number (1-based)
 * @param int $perPage Posts per page
 * @param string|null $postsDir Override posts directory path
 * @return array ['posts' => [...], 'totalPages' => int, 'currentPage' => int, 'totalPosts' => int]
 */
function getPaginatedPosts(int $page = 1, int $perPage = POSTS_PER_PAGE, ?string $postsDir = null): array {
    $allPosts = array_values(getPublishedPosts($postsDir));
    $totalPosts = count($allPosts);
    $totalPages = max(1, (int) ceil($totalPosts / $perPage));
    
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;
    
    return [
        'posts' => array_slice($allPosts, $offset, $perPage),
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'totalPosts' => $totalPosts,
    ];
}

/**
 * Render Markdown content to HTML using Parsedown.
 * 
 * @param string $markdown Raw Markdown string
 * @return string Rendered HTML
 */
function renderMarkdown(string $markdown): string {
    static $parsedown = null;
    if ($parsedown === null) {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
    }
    return $parsedown->text($markdown);
}

/**
 * Get posts filtered by tag.
 * 
 * @param string $tag Tag to filter by
 * @param string|null $postsDir Override posts directory path
 * @return array Filtered posts
 */
function getPostsByTag(string $tag, ?string $postsDir = null): array {
    $posts = getPublishedPosts($postsDir);
    return array_filter($posts, function ($post) use ($tag) {
        $tags = $post['tags'] ?? [];
        if (is_string($tags)) {
            $tags = array_map('trim', explode(',', $tags));
        }
        return in_array(strtolower($tag), array_map('strtolower', $tags));
    });
}

/**
 * Generate HTML meta tags for a blog post (for use in header.php).
 * 
 * @param array $post Post data array
 * @return string HTML meta tags
 */
function generateMetaTags(array $post): string {
    $title = htmlspecialchars($post['title'] ?? 'Blog Post');
    $description = htmlspecialchars($post['excerpt'] ?? '');
    $image = '/assets/img/blog/' . htmlspecialchars($post['image'] ?? '');
    $slug = htmlspecialchars($post['slug'] ?? '');
    $date = htmlspecialchars($post['date'] ?? '');
    $author = htmlspecialchars($post['author'] ?? 'Okarowok Editorial');
    
    $tags = '';
    $tags .= '<meta property="og:type" content="article">' . "\n";
    $tags .= '<meta property="article:published_time" content="' . $date . '">' . "\n";
    $tags .= '<meta property="article:author" content="' . $author . '">' . "\n";
    $tags .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $tags .= '<meta name="twitter:title" content="' . $title . '">' . "\n";
    $tags .= '<meta name="twitter:description" content="' . $description . '">' . "\n";
    $tags .= '<meta name="twitter:image" content="' . $image . '">' . "\n";
    $tags .= '<link rel="canonical" href="/blog/' . $slug . '/">' . "\n";
    
    return $tags;
}

/**
 * Generate JSON-LD structured data for a blog post.
 * 
 * @param array $post Post data array
 * @return string JSON-LD script tag
 */
function generateJsonLd(array $post): string {
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post['title'] ?? '',
        'description' => $post['excerpt'] ?? '',
        'image' => '/assets/img/blog/' . ($post['image'] ?? ''),
        'datePublished' => $post['date'] ?? '',
        'author' => [
            '@type' => 'Organization',
            'name' => $post['author'] ?? 'Okarowok Editorial',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Okarowok Wibye Acel',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => '/assets/img/icons/rhino-head.png',
            ],
        ],
    ];
    
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Get the cached posts index, or rebuild it if stale.
 * 
 * @return array Array of post metadata (without body content)
 */
function getCachedPostsIndex(): array {
    $cacheFile = CACHE_DIR . '/posts-index.json';
    $postsDir = POSTS_DIR;
    
    // Check if cache exists and is fresh
    if (file_exists($cacheFile)) {
        $cacheTime = filemtime($cacheFile);
        $needsRefresh = false;
        
        // Check if any .md file is newer than cache
        $files = glob($postsDir . '/*.md');
        foreach ($files as $file) {
            if (filemtime($file) > $cacheTime) {
                $needsRefresh = true;
                break;
            }
        }
        
        // Also check if files were deleted (count mismatch)
        if (!$needsRefresh) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (count($cached) !== count($files)) {
                $needsRefresh = true;
            }
        }
        
        if (!$needsRefresh) {
            return json_decode(file_get_contents($cacheFile), true);
        }
    }
    
    // Rebuild cache
    $posts = getPublishedPosts();
    $index = array_map(function ($post) {
        // Strip body content from cache index
        unset($post['body']);
        return $post;
    }, $posts);
    
    $index = array_values($index);
    
    // Write cache (create directory if needed)
    if (!is_dir(CACHE_DIR)) {
        mkdir(CACHE_DIR, 0755, true);
    }
    file_put_contents($cacheFile, json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
    return $index;
}

/**
 * Get cached rendered HTML for a post, or render and cache it.
 * 
 * @param string $slug Post slug
 * @return string|null Rendered HTML or null if post not found
 */
function getCachedPostHtml(string $slug): ?string {
    $cacheFile = CACHE_DIR . '/posts/' . $slug . '.html';
    $post = getPostBySlug($slug);
    
    if ($post === null) return null;
    
    // Find the source file to check freshness
    $sourceFile = POSTS_DIR . '/' . ($post['file'] ?? '');
    
    if (file_exists($cacheFile) && file_exists($sourceFile)) {
        if (filemtime($cacheFile) >= filemtime($sourceFile)) {
            return file_get_contents($cacheFile);
        }
    }
    
    // Render and cache
    $html = renderMarkdown($post['body'] ?? '');
    
    $cacheDir = CACHE_DIR . '/posts';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    file_put_contents($cacheFile, $html);
    
    return $html;
}

/**
 * Get adjacent posts (previous and next) for navigation.
 * 
 * @param string $currentSlug Current post slug
 * @return array ['prev' => array|null, 'next' => array|null]
 */
function getAdjacentPosts(string $currentSlug): array {
    $posts = array_values(getPublishedPosts());
    $prev = null;
    $next = null;
    
    foreach ($posts as $i => $post) {
        if (($post['slug'] ?? '') === $currentSlug) {
            $prev = $posts[$i + 1] ?? null; // Older post (posts are sorted newest first)
            $next = $posts[$i - 1] ?? null; // Newer post
            break;
        }
    }
    
    return ['prev' => $prev, 'next' => $next];
}
