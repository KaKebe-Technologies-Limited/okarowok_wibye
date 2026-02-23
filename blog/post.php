<?php
require_once __DIR__ . '/../includes/blog-functions.php';

// Get slug from query string (set by .htaccess rewrite)
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$post = getPostBySlug($slug);

// 404 if post not found
if ($post === null) {
    http_response_code(404);
    $pageTitle = 'Post Not Found - Okarowok Wibye Acel';
    $pageDescription = 'The requested blog post could not be found.';
    require_once __DIR__ . '/../includes/header.php';
    ?>

    <!--=====HERO AREA START=======-->

    <div class="common-hero">
      <div class="container">
        <div class="row align-items-center text-center">
          <div class="col-lg-6 m-auto">
            <div class="main-heading">
              <h1>Post Not Found</h1>
              <div class="space16"></div>
              <span class="span">
                <img src="/assets/img/icons/span1.png" alt="" />
                <a href="/">Home</a>
                <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
                <a href="/blog/">Blog</a>
                <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
                Not Found
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--=====HERO AREA END=======-->

    <div class="blog2 sp">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 m-auto text-center">
            <div class="space40"></div>
            <h2>404 — Post Not Found</h2>
            <div class="space16"></div>
            <p>Sorry, the blog post you are looking for could not be found. It may have been moved or deleted.</p>
            <div class="space16"></div>
            <a class="theme-btn1" href="/blog/">
              Back to Blog <span><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <div class="space40"></div>
          </div>
        </div>
      </div>
    </div>

    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Set page meta for header
$pageTitle = htmlspecialchars($post['title']) . ' - Okarowok Wibye Acel Blog';
$pageDescription = htmlspecialchars($post['excerpt'] ?? '');
$pageImage = '/assets/img/blog/' . htmlspecialchars($post['image'] ?? '');

// Generate extra meta tags for SEO
$extraStyles = generateMetaTags($post);

// Get rendered HTML (with caching)
$postHtml = getCachedPostHtml($slug);
if ($postHtml === null) {
    $postHtml = renderMarkdown($post['body'] ?? '');
}

// Get adjacent posts for navigation
$adjacent = getAdjacentPosts($slug);

require_once __DIR__ . '/../includes/header.php';
?>

    <!--=====HERO AREA START=======-->

    <div class="common-hero">
      <div class="container">
        <div class="row align-items-center text-center">
          <div class="col-lg-6 m-auto">
            <div class="main-heading">
              <h1><?= htmlspecialchars($post['title']) ?></h1>
              <div class="space16"></div>
              <span class="span">
                <img src="/assets/img/icons/span1.png" alt="" />
                <a href="/">Home</a>
                <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
                <a href="/blog/">Blog</a>
                <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
                <?= htmlspecialchars($post['title']) ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--=====HERO AREA END=======-->

    <!--=====BLOG DETAILS START=======-->

    <div class="blog-details sp">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 m-auto">

            <!-- Featured Image -->
            <?php if (!empty($post['image'])): ?>
            <div class="blog-details-img mb-4">
              <img src="/assets/img/blog/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="img-fluid w-100 rounded">
            </div>
            <?php endif; ?>

            <!-- Post Meta -->
            <div class="blog-details-meta mb-3">
              <div class="tags">
                <a class="date" href="#">
                  <img src="/assets/img/icons/date.png" alt="">
                  <?= date('d M, Y', strtotime($post['date'])) ?>
                </a>
                <a class="date outhor" href="#">
                  <img src="/assets/img/icons/blog-icon2.png" alt="">
                  <?= htmlspecialchars($post['author'] ?? 'Okarowok Editorial') ?>
                </a>
              </div>
            </div>

            <!-- Post Title -->
            <h2 class="blog-details-title mb-4"><?= htmlspecialchars($post['title']) ?></h2>

            <!-- Post Body (rendered Markdown) -->
            <div class="blog-details-content">
              <?= $postHtml ?>
            </div>

            <!-- Tags -->
            <?php if (!empty($post['tags'])): ?>
            <div class="blog-details-tags mt-4 mb-4">
              <strong>Tags:</strong>
              <?php
              $tags = is_array($post['tags']) ? $post['tags'] : explode(',', $post['tags']);
              foreach ($tags as $tag):
                  $tag = trim($tag);
              ?>
                <a href="/blog/?tag=<?= urlencode($tag) ?>" class="badge bg-secondary text-decoration-none me-1"><?= htmlspecialchars($tag) ?></a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Post Navigation (Previous / Next) -->
            <div class="blog-post-navigation mt-5 mb-4">
              <div class="row">
                <div class="col-6">
                  <?php if ($adjacent['prev']): ?>
                    <a href="/blog/<?= htmlspecialchars($adjacent['prev']['slug']) ?>/" class="text-decoration-none">
                      <small>← Previous</small><br>
                      <strong><?= htmlspecialchars($adjacent['prev']['title']) ?></strong>
                    </a>
                  <?php endif; ?>
                </div>
                <div class="col-6 text-end">
                  <?php if ($adjacent['next']): ?>
                    <a href="/blog/<?= htmlspecialchars($adjacent['next']['slug']) ?>/" class="text-decoration-none">
                      <small>Next →</small><br>
                      <strong><?= htmlspecialchars($adjacent['next']['title']) ?></strong>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!--=====BLOG DETAILS END=======-->

    <!-- JSON-LD Structured Data -->
    <?= generateJsonLd($post) ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
