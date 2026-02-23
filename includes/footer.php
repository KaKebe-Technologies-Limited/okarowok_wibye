    <!--===== FOOTER AREA START =======-->

    <div class="footer9 _relative">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-12">
                        <div class="single-footer-items footer-logo-area">
                          <div class="footer-logo">
                            <a href="#" class="clan-name-logo-footer">
                              <img src="/assets/img/logo/rhino-head.png" alt="" class="logo-image-footer">
                              <!-- <span class="logo-text">Okarowok<br>Wibye Acel</span> -->
                            </a>
                          </div>
                            <div class="space20"></div>
                            <div class="heading1">
                              <p>Okarowok Wibye Acel Clan - Guardians of Lango heritage since 1887. Preserving culture, promoting unity, and building sustainable futures.</p>
                            </div>
                            <ul class="social-icon">
                                <li><a href="https://www.facebook.com/Okarowokwibyeacel"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                <li><a href="https://x.com/OkarWibyeAcel?t=aA4AO4abE_HrKAIMyUQWiA&s=08"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="https://wa.me/+256772614436"><i class="fa-brands fa-whatsapp"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer-items">
                            <h3>Useful Links</h3>

                            <ul class="menu-list">
                              <li><a href="about.html">About Us </a></li>
                              <li><a href="service.html">Our Activities</a></li>
                              <li><a href="gallery.html">Our Gallery</a></li>
                              <li><a href="/blog/">Blog & News</a></li>
                              <li><a href="index.html#contact">Contact Us</a></li>
                        </ul>
                        </div>
                </div>


                <div class="col-lg-3 col-md-6 col-12">
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
<!-- 
                      <div class="contact-box">
                        <div class="icon">
                          <img src="/assets/img/icons/footer1-icon4.png" alt="">
                        </div>
                        <div class="pera">
                          <a href="mailto:admin@techxen.org">www.techxen.org</a>
                        </div>
                      </div> -->

                  </div>
            </div>

            </div>

            <div class="space40"></div>
        </div>

        <div class="copyright-area">
        <div class="container">
            <div class="row align-items-center">
            <div class="col-md-12 text-center">
                    <div class="coppyright">
                    <p>Copyright @<span class="year"></span> Okarowok Wibye Acel Clan. All Rights Reserved</p>
                    </div>
            </div>
        </div>
        </div>
    </div>

    </div>

    <!--===== FOOTER AREA END =======-->

  <!--===== SCRIPTS =======-->
  <script src="/assets/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/aos.js"></script>
  <script src="/assets/js/fontawesome.js"></script>
  <script src="/assets/js/jquery.countup.js"></script>
  <script src="/assets/js/mobile-menu.js"></script>
  <script src="/assets/js/jquery.magnific-popup.js"></script>
  <script src="/assets/js/owl.carousel.min.js"></script>
  <script src="/assets/js/slick-slider.js"></script>
  <script src="/assets/js/gsap.min.js"></script>
  <script src="/assets/js/ScrollTrigger.min.js"></script>
  <script src="/assets/js/Splitetext.js"></script>
  <script src="/assets/js/text-animation.js"></script>
  <script src="/assets/js/swiper-bundle.js"></script>
  <script src="/assets/js/SmoothScroll.js"></script>
  <script src="/assets/js/jquery.lineProgressbar.js"></script>
  <script src="/assets/js/ripple-btn.js"></script>
  <script src="/assets/js/main.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Check for hash in URL after preloader finishes
      const handleHashScroll = function() {
        if (window.location.hash) {
          const target = document.querySelector(window.location.hash);
          if (target) {
            setTimeout(() => {
              target.scrollIntoView({ behavior: 'smooth' });
            }, 100); // Short delay after preloader
          }
        }
      };
    
      // Run when preloader completes
      document.getElementById('preloader').addEventListener('transitionend', handleHashScroll);
      
      // Fallback if transition doesn't fire
      setTimeout(handleHashScroll, 2000); 
    });
    </script>
    <script>
      window.addEventListener("scroll", () => {
        const header = document.getElementById("mainHeader");
        header.classList.toggle("sticky", window.scrollY > 50);
      });
    </script>
</body>
</html>
