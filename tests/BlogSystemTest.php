<?php
/**
 * Blog System Test File
 * Tests core blog functions from blog-functions.php
 * 
 * Usage: php BlogSystemTest.php
 */

echo "===============================================\n";
echo "  Blog System Test Suite\n";
echo "===============================================\n\n";

require_once __DIR__ . '/../includes/blog-functions.php';

$testsPassed = 0;
$testsFailed = 0;
$testDir = null;

function test($name, $condition, $message = '') {
    global $testsPassed, $testsFailed;
    if ($condition) {
        echo "[PASS] $name\n";
        $testsPassed++;
        return true;
    } else {
        echo "[FAIL] $name\n";
        if ($message) echo "       -> $message\n";
        $testsFailed++;
        return false;
    }
}

function cleanup() {
    global $testDir;
    if ($testDir && is_dir($testDir)) {
        array_map('unlink', glob("$testDir/*.*"));
        rmdir($testDir);
    }
}

function createTestPost($dir, $filename, $frontMatter, $body) {
    $content = "---\n";
    foreach ($frontMatter as $key => $value) {
        if (is_array($value)) {
            $content .= "$key: " . implode(', ', $value) . "\n";
        } elseif (is_bool($value)) {
            $content .= "$key: " . ($value ? 'true' : 'false') . "\n";
        } else {
            $content .= "$key: $value\n";
        }
    }
    $content .= "---\n\n";
    $content .= $body;
    file_put_contents($dir . '/' . $filename, $content);
}

function assertEqual($expected, $actual, $name) {
    return test($name, $expected === $actual, "Expected: " . json_encode($expected) . ", Got: " . json_encode($actual));
}

function assertTrue($value, $name) {
    return test($name, $value === true, "Expected true, got: " . json_encode($value));
}

function assertFalse($value, $name) {
    return test($name, $value === false, "Expected false, got: " . json_encode($value));
}

function assertNull($value, $name) {
    return test($name, $value === null, "Expected null, got: " . json_encode($value));
}

function assertNotNull($value, $name) {
    return test($name, $value !== null, "Expected not null, got null");
}

function assertContains($needle, $haystack, $name) {
    return test($name, strpos($haystack, $needle) !== false, "Expected '$haystack' to contain '$needle'");
}

function assertCount($expected, $array, $name) {
    return test($name, count($array) === $expected, "Expected count $expected, got " . count($array));
}

$testDir = sys_get_temp_dir() . '/blog_tests_' . uniqid();
mkdir($testDir, 0755, true);

echo "=== MT: parseFrontMatter Tests ===\n\n";

$validContent = "---
title: Test Post
slug: test-post
date: 2026-02-15
author: Test Author
image: test.jpg
excerpt: This is a test excerpt
tags: tag1, tag2, tag3
published: true
---

# Hello World

This is the body content.
";

$result = parseFrontMatter($validContent);
test('parseFrontMatter returns array', is_array($result), 'Expected array but got: ' . gettype($result));
assertTrue(isset($result['meta']) && isset($result['body']), 'Result has meta and body keys');
assertEqual('Test Post', $result['meta']['title'] ?? '', 'Valid front matter - title parsed');
assertEqual('test-post', $result['meta']['slug'] ?? '', 'Valid front matter - slug parsed');
assertEqual('2026-02-15', $result['meta']['date'] ?? '', 'Valid front matter - date parsed');
assertEqual('Test Author', $result['meta']['author'] ?? '', 'Valid front matter - author parsed');
assertEqual(['tag1', 'tag2', 'tag3'], $result['meta']['tags'] ?? [], 'Valid front matter - tags parsed as array');
assertTrue(($result['meta']['published'] ?? false) === true, 'Valid front matter - boolean true parsed');
assertContains('# Hello World', $result['body'] ?? '', 'Valid front matter - body content preserved');

echo "\n--- Missing front matter ---\n";
$noFrontMatter = "No front matter here.\n\nJust plain content.";
$result = parseFrontMatter($noFrontMatter);
assertEqual([], $result['meta'] ?? null, 'No front matter - returns empty meta');
assertEqual($noFrontMatter, $result['body'] ?? '', 'No front matter - body is entire content');

echo "\n--- Partial/missing fields ---\n";
$partialContent = "---
title: Partial Post
published: false
---

Body only.
";
$result = parseFrontMatter($partialContent);
assertEqual('Partial Post', $result['meta']['title'] ?? '', 'Partial - title present');
assertFalse(($result['meta']['published'] ?? true), 'Partial - boolean false parsed');
assertFalse(isset($result['meta']['author']), 'Partial - missing fields not present');

echo "\n--- Special characters ---\n";
$specialContent = "---
title: Special <chars> & \"quotes\"
slug: special-chars-url
date: 2026-02-15
author: Author
excerpt: Excerpt with 'single' and \"double\" quotes
tags: tag with spaces, another-tag
published: true
---

Content with <script>alert('xss')</script> and more.
";
$result = parseFrontMatter($specialContent);
assertEqual('Special <chars> & "quotes"', $result['meta']['title'] ?? '', 'Special chars - title preserved');
assertEqual(['tag with spaces', 'another-tag'], $result['meta']['tags'] ?? [], 'Special chars - tags with spaces');

echo "\n\n=== BF: getAllPosts Tests ===\n\n";

$testPostsDir = $testDir . '/posts';
mkdir($testPostsDir, 0755, true);

createTestPost($testPostsDir, '2026-02-10-post-three.md', [
    'title' => 'Post Three',
    'slug' => 'post-three',
    'date' => '2026-02-10',
    'author' => 'Author A',
    'published' => true
], 'Content three');

createTestPost($testPostsDir, '2026-02-20-post-one.md', [
    'title' => 'Post One',
    'slug' => 'post-one',
    'date' => '2026-02-20',
    'author' => 'Author B',
    'published' => true
], 'Content one');

createTestPost($testPostsDir, '2026-02-15-post-two.md', [
    'title' => 'Post Two',
    'slug' => 'post-two',
    'date' => '2026-02-15',
    'author' => 'Author C',
    'published' => false
], 'Content two (draft)');

$posts = getAllPosts($testPostsDir);
assertCount(3, $posts, 'getAllPosts - returns all posts (including drafts)');
assertEqual('Post One', $posts[0]['title'] ?? '', 'getAllPosts - sorted by date desc (newest first)');
assertEqual('Post Two', $posts[1]['title'] ?? '', 'getAllPosts - middle post correct');
assertEqual('Post Three', $posts[2]['title'] ?? '', 'getAllPosts - oldest post last');

echo "\n--- Limit and offset ---\n";
$posts = getAllPosts($testPostsDir);
$limited = array_slice($posts, 0, 2);
assertCount(2, $limited, 'getAllPosts - can limit results manually');

echo "\n--- Sort order ---\n";
$allPosts = getAllPosts($testPostsDir);
$sortedDesc = true;
for ($i = 0; $i < count($allPosts) - 1; $i++) {
    if (strtotime($allPosts[$i]['date']) < strtotime($allPosts[$i + 1]['date'])) {
        $sortedDesc = false;
        break;
    }
}
assertTrue($sortedDesc, 'getAllPosts - posts sorted by date descending');

echo "\n--- Empty directory ---\n";
$emptyDir = $testDir . '/empty';
mkdir($emptyDir, 0755, true);
$posts = getAllPosts($emptyDir);
assertCount(0, $posts, 'getAllPosts - returns empty array for empty directory');

echo "\n\n=== BF: getPublishedPosts Tests ===\n\n";

$published = getPublishedPosts($testPostsDir);
assertCount(2, $published, 'getPublishedPosts - returns only published posts');
assertEqual('Post One', $published[0]['title'] ?? '', 'getPublishedPosts - published post present');
assertEqual('Post Three', $published[1]['title'] ?? '', 'getPublishedPosts - another published post');

$hasDraft = false;
foreach ($published as $p) {
    if (empty($p['published']) || $p['published'] !== true) {
        $hasDraft = true;
        break;
    }
}
assertFalse($hasDraft, 'getPublishedPosts - no drafts in results');

echo "\n--- All drafts ---\n";
$draftDir = $testDir . '/drafts';
mkdir($draftDir, 0755, true);
createTestPost($draftDir, 'draft.md', [
    'title' => 'Draft Post',
    'slug' => 'draft-post',
    'date' => '2026-02-01',
    'published' => false
], 'Draft content');
$published = getPublishedPosts($draftDir);
assertCount(0, $published, 'getPublishedPosts - returns empty when all are drafts');

echo "\n\n=== BF: getPostBySlug Tests ===\n\n";

$post = getPostBySlug('post-one', $testPostsDir);
assertNotNull($post, 'getPostBySlug - finds valid slug');
assertEqual('Post One', $post['title'] ?? '', 'getPostBySlug - returns correct post data');

$post = getPostBySlug('non-existent', $testPostsDir);
assertNull($post, 'getPostBySlug - returns null for invalid slug');

$post = getPostBySlug('post-two', $testPostsDir);
assertNull($post, 'getPostBySlug - returns null for unpublished post (filtered out)');

echo "\n\n=== BF: getPaginatedPosts Tests ===\n\n";

$pagination = getPaginatedPosts(1, 2, $testPostsDir);
assertEqual(2, $pagination['totalPages'] ?? 0, 'getPaginatedPosts - totalPages correct (2 posts, 2 per page)');
assertEqual(1, $pagination['currentPage'] ?? 0, 'getPaginatedPosts - currentPage correct');
assertEqual(2, $pagination['totalPosts'] ?? 0, 'getPaginatedPosts - totalPosts correct');
assertCount(2, $pagination['posts'] ?? [], 'getPaginatedPosts - returns correct number of posts per page');

$pagination = getPaginatedPosts(2, 2, $testPostsDir);
assertEqual(1, $pagination['currentPage'] ?? 0, 'getPaginatedPosts - page 2 capped to max pages');
assertCount(0, $pagination['posts'] ?? [], 'getPaginatedPosts - page 2 has no posts (only 2 total)');

$pagination = getPaginatedPosts(0, 2, $testPostsDir);
assertEqual(1, $pagination['currentPage'] ?? 0, 'getPaginatedPosts - page 0 defaults to 1');

$pagination = getPaginatedPosts(1, 3, $testPostsDir);
assertEqual(1, $pagination['totalPages'] ?? 0, 'getPaginatedPosts - 2 posts with 3 per page = 1 page');

echo "\n\n=== CF: renderMarkdown Tests ===\n\n";

$html = renderMarkdown('# Hello World');
assertContains('<h1>Hello World</h1>', $html, 'renderMarkdown - headings');

$html = renderMarkdown('**bold text**');
assertContains('<strong>bold text</strong>', $html, 'renderMarkdown - bold');

$html = renderMarkdown('*italic text*');
assertContains('<em>italic text</em>', $html, 'renderMarkdown - italic');

$html = renderMarkdown('[Link Text](https://example.com)');
assertContains('<a href="https://example.com">Link Text</a>', $html, 'renderMarkdown - links');

$html = renderMarkdown('![Alt](image.jpg)');
assertContains('<img src="image.jpg" alt="Alt">', $html, 'renderMarkdown - images');

$html = renderMarkdown("Line 1\n\nLine 2");
assertContains('<br />', $html, 'renderMarkdown - line breaks');

$html = renderMarkdown("- Item 1\n- Item 2\n- Item 3");
assertContains('<li>Item 1</li>', $html, 'renderMarkdown - lists');

$html = renderMarkdown("> Quote text");
assertContains('<blockquote>', $html, 'renderMarkdown - blockquotes');

$html = renderMarkdown('`inline code`');
assertContains('<code>inline code</code>', $html, 'renderMarkdown - inline code');

echo "\n\n=== CF: getPostsByTag Tests ===\n\n";

$tagPostsDir = $testDir . '/tags';
mkdir($tagPostsDir, 0755, true);

createTestPost($tagPostsDir, '2026-02-20-post-a.md', [
    'title' => 'Post A',
    'slug' => 'post-a',
    'date' => '2026-02-20',
    'published' => true,
    'tags' => ['php', 'testing']
], 'Content A');

createTestPost($tagPostsDir, '2026-02-19-post-b.md', [
    'title' => 'Post B',
    'slug' => 'post-b',
    'date' => '2026-02-19',
    'published' => true,
    'tags' => ['php', 'code']
], 'Content B');

createTestPost($tagPostsDir, '2026-02-18-post-c.md', [
    'title' => 'Post C',
    'slug' => 'post-c',
    'date' => '2026-02-18',
    'published' => true,
    'tags' => ['javascript']
], 'Content C');

$posts = getPostsByTag('php', $tagPostsDir);
assertCount(2, $posts, 'getPostsByTag - finds posts with tag');

$posts = getPostsByTag('PHP', $tagPostsDir);
assertCount(2, $posts, 'getPostsByTag - case insensitive');

$posts = getPostsByTag('javascript', $tagPostsDir);
assertCount(1, $posts, 'getPostsByTag - single post with tag');

$posts = getPostsByTag('nonexistent', $tagPostsDir);
assertCount(0, $posts, 'getPostsByTag - returns empty for non-existent tag');

echo "\n--- Tags as comma-separated string ---\n";
$stringTagDir = $testDir . '/stringtags';
mkdir($stringTagDir, 0755, true);
createTestPost($stringTagDir, 'test.md', [
    'title' => 'String Tags Post',
    'slug' => 'string-tags',
    'date' => '2026-02-15',
    'published' => true,
    'tags' => 'tag1, tag2, tag3'
], 'Content');
$posts = getPostsByTag('tag2', $stringTagDir);
assertCount(1, $posts, 'getPostsByTag - works with comma-separated string tags');

echo "\n\n=== CF: generateMetaTags Tests ===\n\n";

$post = [
    'title' => 'Test Title',
    'slug' => 'test-slug',
    'date' => '2026-02-15',
    'author' => 'Test Author',
    'excerpt' => 'Test excerpt for meta description',
    'image' => 'test-image.jpg'
];

$meta = generateMetaTags($post);
assertContains('og:type', $meta, 'generateMetaTags - includes Open Graph type');
assertContains('article:published_time', $meta, 'generateMetaTags - includes publication date');
assertContains('article:author', $meta, 'generateMetaTags - includes author');
assertContains('twitter:card', $meta, 'generateMetaTags - includes Twitter card');
assertContains('twitter:title', $meta, 'generateMetaTags - includes Twitter title');
assertContains('twitter:description', $meta, 'generateMetaTags - includes Twitter description');
assertContains('twitter:image', $meta, 'generateMetaTags - includes Twitter image');
assertContains('canonical', $meta, 'generateMetaTags - includes canonical URL');
assertContains('/blog/test-slug/', $meta, 'generateMetaTags - canonical URL uses slug');

echo "\n--- Missing fields ---\n";
$postMinimal = [
    'title' => 'Minimal Title',
    'slug' => 'minimal-slug'
];
$meta = generateMetaTags($postMinimal);
assertContains('Minimal Title', $meta, 'generateMetaTags - uses default values when missing');

echo "\n\n=== CF: generateJsonLd Tests ===\n\n";

$post = [
    'title' => 'JSON-LD Test Post',
    'slug' => 'jsonld-test',
    'date' => '2026-02-15',
    'author' => 'Test Author',
    'excerpt' => 'JSON-LD excerpt',
    'image' => 'jsonld-image.jpg'
];

$jsonLd = generateJsonLd($post);
assertContains('application/ld+json', $jsonLd, 'generateJsonLd - correct script type');
assertContains('BlogPosting', $jsonLd, 'generateJsonLd - correct schema type');
assertContains('JSON-LD Test Post', $jsonLd, 'generateJsonLd - includes title');
assertContains('JSON-LD excerpt', $jsonLd, 'generateJsonLd - includes description');
assertContains('2026-02-15', $jsonLd, 'generateJsonLd - includes date');
assertContains('Test Author', $jsonLd, 'generateJsonLd - includes author');
assertContains('Okarowok Wibye Acel', $jsonLd, 'generateJsonLd - includes publisher name');
assertContains('rhino-head.png', $jsonLd, 'generateJsonLd - includes publisher logo');

$decoded = json_decode(strip_tags($jsonLd), true);
assertTrue(is_array($decoded), 'generateJsonLd - produces valid JSON');

echo "\n--- Minimal post ---\n";
$postMinimal = ['title' => 'Only Title'];
$jsonLd = generateJsonLd($postMinimal);
$decoded = json_decode(strip_tags($jsonLd), true);
assertNotNull($decoded, 'generateJsonLd - works with minimal data');

echo "\n\n=== CF: getCachedPostsIndex Tests ===\n\n";

$cacheTestDir = $testDir . '/cacheposts';
mkdir($cacheTestDir, 0755, true);

createTestPost($cacheTestDir, '2026-02-20-cached-post.md', [
    'title' => 'Cached Post',
    'slug' => 'cached-post',
    'date' => '2026-02-20',
    'published' => true,
    'tags' => ['test']
], 'Cached content here');

$originalCacheDir = CACHE_DIR;
$originalPostsDir = POSTS_DIR;

define('CACHE_DIR', $testDir . '/cache');
define('POSTS_DIR', $cacheTestDir);

if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

$index = getCachedPostsIndex();
assertCount(1, $index, 'getCachedPostsIndex - returns posts index');
assertTrue(!isset($index[0]['body']), 'getCachedPostsIndex - body removed from cache');

echo "\n--- Cache read ---\n";
$index2 = getCachedPostsIndex();
assertCount(1, $index2, 'getCachedPostsIndex - reads from cache');

echo "\n--- Cache invalidation on new file ---\n";
sleep(1);
createTestPost($cacheTestDir, '2026-02-21-new-post.md', [
    'title' => 'New Post',
    'slug' => 'new-post',
    'date' => '2026-02-21',
    'published' => true
], 'New content');
$index3 = getCachedPostsIndex();
assertCount(2, $index3, 'getCachedPostsIndex - refreshes cache on new file');

echo "\n\n=== CF: getCachedPostHtml Tests ===\n\n";

$slug = 'cached-post';
$html = getCachedPostHtml($slug);
assertNotNull($html, 'getCachedPostHtml - renders HTML for valid slug');
assertTrue(strlen($html) > 0, 'getCachedPostHtml - returns non-empty HTML');

echo "\n--- Cache read ---\n";
$html2 = getCachedPostHtml($slug);
assertEqual($html, $html2, 'getCachedPostHtml - reads from cache');

echo "\n--- Invalid slug ---\n";
$html = getCachedPostHtml('non-existent-post');
assertNull($html, 'getCachedPostHtml - returns null for invalid slug');

echo "\n--- Cache refresh on file update ---\n";
$cacheFile = CACHE_DIR . '/posts/' . $slug . '.html';
sleep(1);
touch($cacheTestDir . '/2026-02-20-cached-post.md');
$html3 = getCachedPostHtml($slug);
assertNotNull($html3, 'getCachedPostHtml - regenerates on file update');

define('CACHE_DIR', $originalCacheDir);
define('POSTS_DIR', $originalPostsDir);

echo "\n\n=== Real Posts Directory Tests ===\n\n";

$realPostsDir = __DIR__ . '/../content/posts';
if (is_dir($realPostsDir)) {
    $realPosts = getAllPosts($realPostsDir);
    assertTrue(count($realPosts) > 0, 'Real posts directory contains posts');
    
    $published = getPublishedPosts($realPostsDir);
    assertTrue(count($published) > 0, 'Real posts have published posts');
    
    if (count($published) > 0) {
        $firstPost = $published[0];
        $bySlug = getPostBySlug($firstPost['slug'], $realPostsDir);
        assertNotNull($bySlug, 'getPostBySlug works with real posts');
        
        $rendered = renderMarkdown($firstPost['body'] ?? '');
        assertTrue(strlen($rendered) > 0, 'renderMarkdown works with real content');
    }
} else {
    echo "[SKIP] Real posts directory not found\n";
}

cleanup();

echo "\n===============================================\n";
echo "  Test Results: $testsPassed passed, $testsFailed failed\n";
echo "===============================================\n";

exit($testsFailed > 0 ? 1 : 0);
