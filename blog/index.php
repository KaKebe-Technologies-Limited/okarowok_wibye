<?php
require_once __DIR__ . '/../includes/blog-functions.php';

// Get current page from query string
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Get tag filter from query string
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : null;

// Get posts - either all paginated or filtered by tag
if ($tag) {
    $filteredPosts = getPostsByTag($tag);
    $totalPosts = count($filteredPosts);
    $totalPages = max(1, (int) ceil($totalPosts / POSTS_PER_PAGE));
    $page = max(1, min($page, $totalPages));
    $posts = array_slice(array_values($filteredPosts), ($page - 1) * POSTS_PER_PAGE, POSTS_PER_PAGE);
    $currentPage = $page;
} else {
    $result = getPaginatedPosts($page);
    $posts = $result['posts'];
    $totalPages = $result['totalPages'];
    $currentPage = $result['currentPage'];
}

// Set page meta for header
$pageTitle = 'Blog & News - Okarowok Wibye Acel';
$pageDescription = 'Latest news, cultural insights, and updates from the Okarowok Wibye Acel clan.';

require_once __DIR__ . '/../includes/header.php';
?>

    <style>
      .blog.blog-page .blog2-box .image {
        height: 260px;
        overflow: hidden;
      }
      .blog.blog-page .blog2-box .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .blog.blog-page .blog2-box .heading1 {
        flex: 1;
        display: flex;
        flex-direction: column;
      }
      .blog.blog-page .blog2-box .heading1 p {
        flex: 1;
      }
    </style>

    <!--=====HERO AREA START=======-->

    <div class="common-hero">
      <div class="container">
        <div class="row align-items-center text-center">
          <div class="col-lg-6 m-auto">
            <div class="main-heading">
              <h1>Our Blog</h1>
              <div class="space16"></div>
              <span class="span">
                <img src="/assets/img/icons/span1.png" alt="" />
                <a href="/">Home</a>
                <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
                Blog
                <?php if ($tag): ?>
                  <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
                  <span class="active"><?= htmlspecialchars(ucfirst($tag)) ?></span>
                <?php endif; ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--=====HERO AREA END=======-->

    <!--=====BLOG AREA START=======-->

    <div class="blog blog-page sp">
      <div class="container">
        <div class="row">
          <?php if (empty($posts)): ?>
            <div class="col-12 text-center">
              <p>No blog posts found. Check back soon!</p>
            </div>
          <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <div class="col-lg-6">
              <div class="blog2-box">
                <div class="image">
                  <a href="/blog/<?= htmlspecialchars($post['slug']) ?>/">
                    <img src="/assets/img/blog/<?= htmlspecialchars($post['image'] ?? 'blog2-img1.png') ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                  </a>
                </div>
                <div class="heading1">
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
                  <h4><a href="/blog/<?= htmlspecialchars($post['slug']) ?>/"><?= htmlspecialchars($post['title']) ?></a></h4>
                  <div class="space16"></div>
                  <p><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
                  <div class="space16"></div>
                  <a class="learn" href="/blog/<?= htmlspecialchars($post['slug']) ?>/">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="space60"></div>
        <div class="row">
          <div class="col-12 m-auto">
            <div class="theme-pagination text-center">
              <ul>
                <?php 
                // Build query string for pagination (preserve tag if set)
                $querySuffix = $tag ? '&tag=' . urlencode($tag) : '';
                ?>
                <?php if ($currentPage > 1): ?>
                  <li><a href="/blog/?page=<?= $currentPage - 1 ?><?= $querySuffix ?>"><i class="fa-solid fa-angle-left"></i></a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li<?= $i === $currentPage ? ' class="active"' : '' ?>>
                    <a <?= $i === $currentPage ? 'class="active" ' : '' ?>href="/blog/?page=<?= $i ?><?= $querySuffix ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></a>
                  </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                  <li><a href="/blog/?page=<?= $currentPage + 1 ?><?= $querySuffix ?>"><i class="fa-solid fa-angle-right"></i></a></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>

    <!--=====BLOG AREA END=======-->

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
