<?php
// Default page meta values — override these before including header.php
if (!isset($pageTitle)) $pageTitle = 'Okarowok Wibye Acel';
if (!isset($pageDescription)) $pageDescription = 'Official website of the Okarowok Wibye Acel clan';
if (!isset($pageImage)) $pageImage = '/assets/img/icons/rhino-head.png';
if (!isset($extraStyles)) $extraStyles = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
  <meta keywords="Lango clan, Ugandan heritage, cultural preservation, Oculi Abwango, clan totems">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($pageImage); ?>">
  <meta property="og:type" content="website">

  <!--=====FAVICON=======-->
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/logo/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/logo/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/logo/favicon-16x16.png">
  <link rel="manifest" href="/assets/img/logo/site.webmanifest">

  <!--=====CSS=======-->
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/fontawesome.css"/>
  <link rel="stylesheet" href="/assets/css/magnific-popup.css"/>
  <link rel="stylesheet" href="/assets/css/nice-select.css"/>
  <link rel="stylesheet" href="/assets/css/slick-slider.css"/>
  <link rel="stylesheet" href="/assets/css/owl.carousel.min.css"/>
  <link rel="stylesheet" href="/assets/css/swiper-bundle.css"/>
  <link rel="stylesheet" href="/assets/css/aos.css"/>
  <link rel="stylesheet" href="/assets/css/mobile-menu.css"/>
  <link rel="stylesheet" href="/assets/css/main.css"/>

  <style>
    /* Floating header effect */
    .header-area {
      transition: all 0.3s ease;
    }
    
    .header-area.sticky {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: white;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    /* Header top bar */

    .header-top .pera p {
      color: #fff;
      font-size: 14px;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .header-top .pera img {
      height: 16px;
    }

    /* Desktop Header */
    .site-logo .clan-name-logo {
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .site-logo .logo-image {
      height: 50px;
      width: auto;
    }

    .main-menu-ex ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      gap: 30px;
    }

    .main-menu-ex ul li a {
      color: #333;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .main-menu-ex ul li a:hover {
      color: #c49b63;
    }

    /* CTA Button */
    .header1-buttons {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .header1-buttons .contact-btn {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header1-buttons .contact-btn .headding p {
      font-size: 12px;
      color: #666;
      margin: 0;
    }

    .header1-buttons .contact-btn .headding a {
      color: #333;
      font-weight: 600;
      text-decoration: none;
    }


    /* Mobile Header */
    .mobile-header {
      background: white;
      padding: 15px 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .mobile-header-elements {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .mobile-logo .clan-name-logo-mobile {
      text-decoration: none;
    }

    .mobile-logo .logo-image-mobile {
      height: 40px;
    }

    .mobile-nav-icon i {
      font-size: 24px;
      cursor: pointer;
    }

    /* Mobile Sidebar */
    .mobile-sidebar {
      position: fixed;
      top: 0;
      left: -300px;
      width: 300px;
      height: 100vh;
      background: white;
      z-index: 9999;
      transition: left 0.3s ease;
      overflow-y: auto;
      padding: 20px;
    }

    .mobile-sidebar.active {
      left: 0;
    }

    .mobile-sidebar .logo-m {
      margin-bottom: 20px;
    }

    .mobile-sidebar .logo-m a {
      color: #333;
      font-size: 20px;
      font-weight: 700;
      text-decoration: none;
    }

    .mobile-sidebar .menu-close {
      position: absolute;
      top: 20px;
      right: 20px;
      cursor: pointer;
    }

    .mobile-sidebar .menu-close i {
      font-size: 24px;
    }

    .mobile-sidebar .mobile-nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .mobile-sidebar .mobile-nav ul li {
      padding: 12px 0;
      border-bottom: 1px solid #eee;
    }

    .mobile-sidebar .mobile-nav ul li a {
      color: #333;
      text-decoration: none;
      font-weight: 500;
    }

    .mobile-sidebar .mobile-button {
      margin-top: 20px;
    }

    .mobile-sidebar .mobile-button .menu-btn2 {
      background: #c49b63;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .mobile-sidebar .single-footer-items {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #eee;
    }

    .mobile-sidebar .single-footer-items h3 {
      font-size: 16px;
      margin-bottom: 15px;
    }

    .mobile-sidebar .contact-box {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }

    .mobile-sidebar .contact-box .icon img {
      height: 20px;
    }

    .mobile-sidebar .contact-box .pera a {
      color: #666;
      text-decoration: none;
      font-size: 14px;
    }

    .mobile-sidebar .contact-infos {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #eee;
    }

    .mobile-sidebar .contact-infos h3 {
      font-size: 16px;
      margin-bottom: 15px;
    }

    .mobile-sidebar .social-icon {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      gap: 15px;
    }

    .mobile-sidebar .social-icon li a {
      color: #333;
      font-size: 18px;
    }

    /* Overlay */
    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9998;
      display: none;
    }

    .sidebar-overlay.active {
      display: block;
    }
  </style>

  <?php echo $extraStyles; ?>

  <!--=====JQUERY=======-->
  <script src="/assets/js/jquery-3-7-1.min.js"></script>
</head>

<body class="body tg-heading-subheading animation-style3">

  <!--=====progress START=======-->
  <div class="paginacontainer"> 
    <div class="progress-wrap">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
      </svg>
    </div>
  </div> 
  <!--=====progress END=======-->

  <!--=====HEADER START=======-->
  <div class="header-top">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="pera">
            <p>
              <img src="/assets/img/icons/header-top-span.png" alt="">Cultural Guardianship and Social Empowerment for Generations to Come
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <header>
    <div class="header-area header-area1 header-area-all d-none d-lg-block" id="header">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="header-elements">
              <div class="site-logo">
                <a href="/" class="d-flex clan-name-logo text-center">
                  <img src="/assets/img/logo/rhino-head.png" alt="" class="logo-image">
                </a>
              </div>

              <div class="main-menu-ex main-menu-ex1">
                <ul>
                  <li><a href="/">Home</a></li>
                  <li class="dropdown-menu-parrent"><a href="/about/">About</a></li>
                  <li class="dropdown-menu-parrent"><a href="/activities/">Activities</a></li>
                  <li class="dropdown-menu-parrent"><a href="/gallery/">Gallery</a></li>
                  <li class="dropdown-menu-parrent"><a href="/blog/">Blog</a></li>
                </ul>
              </div>

              <div class="header1-buttons">
                <div class="contact-btn">
                  <div class="icon">
                    <img src="/assets/img/icons/header1-icon.png" alt="">
                  </div>
                  <div class="headding">
                    <p>Make a Call</p>
                    <a href="tel:+256764820075">+256 772 614436</a>
                  </div>
                </div>
                <div class="button">
                  <a class="theme-btn1" href="/#contact">Get in Touch <span><i class="fa-solid fa-arrow-right"></i></span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!--=====HEADER END=======-->

  <!--=====Mobile header start=======-->
  <div class="mobile-header d-block d-lg-none">
    <div class="container-fluid">
      <div class="col-12">
        <div class="mobile-header-elements">
          <div class="mobile-logo">
            <a href="/" class="clan-name-logo-mobile">
              <img src="/assets/img/logo/rhino-head.png" alt="" class="logo-image-mobile">
            </a>
          </div>
          <div class="mobile-nav-icon">
            <i class="fa-duotone fa-bars-staggered"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="sidebar-overlay"></div>

  <div class="mobile-sidebar d-block d-lg-none">
    <div class="logo-m">
      <a href="/" class="clan-name-logo-mobile">Okarowok Wibye Acel</a>
    </div>
    <div class="menu-close">
      <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="mobile-nav">
      <ul>
        <li class="has-dropdown"><a href="/">Home</a></li>
        <li><a href="/about/">About Us</a></li>
        <li class="has-dropdown"><a href="/activities/">Activities</a></li>
        <li class="has-dropdown"><a href="/gallery/">Gallery</a></li>
        <li class="has-dropdown"><a href="/blog/">Blog</a></li>
      </ul>

      <div class="mobile-button">
        <a class="menu-btn2" href="/#contact">Get in Touch <span><i class="fa-solid fa-arrow-right"></i></span></a>
      </div>

      <div class="single-footer-items">
        <h3>Contact Us</h3>
        <div class="contact-box">
          <div class="icon">
            <img src="/assets/img/icons/footer1-icon1.png" alt="">
          </div>
          <div class="pera">
            <a href="tel:+256772614436">+256 772 614436</a>
          </div>
        </div>
        <div class="contact-box">
          <div class="icon">
            <img src="/assets/img/icons/footer1-icon2.png" alt="">
          </div>
          <div class="pera">
            <a href="tel:+256774927372">+256 774 927372</a>
          </div>
        </div>
        <div class="contact-box">
          <div class="icon">
            <img src="/assets/img/icons/footer1-icon3.png" alt="">
          </div>
          <div class="pera">
            <a href="mailto:info@okarowok.ac.ug">info@okarowok.ac.ug</a>
          </div>
        </div>
      </div>

      <div class="contact-infos">
        <h3>Our Location</h3>
        <ul class="social-icon">
          <li><a href="https://www.facebook.com/Okarowokwibyeacel"><i class="fa-brands fa-facebook"></i></a></li>
          <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
          <li><a href="https://x.com/OkarWibyeAcel?t=aA4AO4abE_HrKAIMyUQWiA&s=08"><i class="fa-brands fa-x-twitter"></i></a></li>
          <li><a href="https://wa.me/+256772614436"><i class="fa-brands fa-whatsapp"></i></a></li>
          <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!--=====Mobile header end=======-->

  <script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
      const mobileNavIcon = document.querySelector('.mobile-nav-icon');
      const mobileSidebar = document.querySelector('.mobile-sidebar');
      const menuClose = document.querySelector('.menu-close');
      const sidebarOverlay = document.querySelector('.sidebar-overlay');

      if (mobileNavIcon && mobileSidebar) {
        mobileNavIcon.addEventListener('click', function() {
          mobileSidebar.classList.add('active');
          if (sidebarOverlay) sidebarOverlay.classList.add('active');
        });
      }

      if (menuClose && mobileSidebar) {
        menuClose.addEventListener('click', function() {
          mobileSidebar.classList.remove('active');
          if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        });
      }

      if (sidebarOverlay && mobileSidebar) {
        sidebarOverlay.addEventListener('click', function() {
          mobileSidebar.classList.remove('active');
          sidebarOverlay.classList.remove('active');
        });
      }

      // Sticky header effect
      const header = document.getElementById('header');
      if (header) {
        window.addEventListener('scroll', function() {
          if (window.scrollY > 100) {
            header.classList.add('sticky');
          } else {
            header.classList.remove('sticky');
          }
        });
      }
    });
  </script>
</body>
</html>
