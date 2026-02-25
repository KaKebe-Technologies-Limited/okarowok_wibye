<?php
$pageTitle = '404 - Page Not Found';
$pageDescription = 'The page you are looking for does not exist.';
require_once __DIR__ . '/includes/header.php';
?>

<!--=====HERO AREA START=======-->
<div class="common-hero error">
  <div class="container">
    <div class="row align-items-center text-center">
      <div class="col-lg-6 m-auto">
        <div class="main-heading">
          <h1>404</h1>
          <div class="space16"></div>
          <span class="span">
            <img src="/assets/img/icons/span1.png" alt="" />
            <a href="/">Home</a>
            <span class="arrow"><i class="fa-regular fa-angle-right"></i></span>
            404 Error
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
<!--=====HERO AREA END=======-->

<!--=====404 SECTION START=======-->
<div class="error-page sp" style="padding: 80px 0; background: #f8f9fa;">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 m-auto text-center">
        <div class="error-content" style="padding: 40px;">
          <div class="error-icon" style="font-size: 80px; color: #e94560; margin-bottom: 24px; display: block; line-height: 1;">
            <i class="fa-regular fa-face-frown-open"></i>
          </div>
          <h2 style="font-size: 36px; font-weight: 700; color: #333; margin-bottom: 16px;">Page Not Found</h2>
          <div class="space16"></div>
          <p style="font-size: 18px; color: #666; max-width: 500px; margin: 0 auto 32px;">Sorry, the page you are looking for doesn't exist. It may have been moved or deleted.</p>
          <div class="space24"></div>
          <div class="error-buttons" style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="/" class="theme-btn1">
              Back to Home <span><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <a href="/blog/" class="theme-btn2">
              Visit Our Blog <span><i class="fa-solid fa-arrow-right"></i></span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--=====404 SECTION END=======-->

<?php
require_once __DIR__ . '/includes/footer.php';
?>
