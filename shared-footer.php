<?php
/* Shared footer for the City of Koronadal portal and information pages. */
?>
<footer class="kd-shared-footer" aria-label="City Government footer">
  <div class="kd-shared-footer-inner">
    <div class="kd-shared-footer-top">
      <div class="kd-shared-footer-identity">
        <a href="./" class="kd-shared-footer-brand" aria-label="City of Koronadal portal home">
          <span class="kd-shared-footer-logo"><img src="Logo.png" alt="City of Koronadal official seal" loading="lazy"></span>
          <span class="kd-shared-footer-brand-copy">
            <strong>City Government of Koronadal</strong>
            <small>Koronadal, South Cotabato, Philippines</small>
          </span>
        </a>
        <p>Online city services, information, and assistance in one convenient place.</p>
      </div>
      <nav class="kd-shared-footer-nav" aria-label="Footer navigation">
        <span class="kd-shared-footer-nav-title">Quick links</span>
        <div class="kd-shared-footer-links">
          <a href="./"><i class="fas fa-house" aria-hidden="true"></i> Portal Home</a>
          <a href="city-officials"><i class="fas fa-users" aria-hidden="true"></i> City Officials</a>
          <a href="mayor-corner"><i class="fas fa-landmark" aria-hidden="true"></i> Mayor's Corner</a>
          <a href="emergency-contacts"><i class="fas fa-phone-volume" aria-hidden="true"></i> Emergency Contacts</a>
          <a href="mailto:info.koronadalcity@gmail.com"><i class="fas fa-envelope" aria-hidden="true"></i> Contact Us</a>
        </div>
      </nav>
    </div>
    <div class="kd-shared-footer-bottom">
      <span>&copy; <?= date('Y') ?> City Government of Koronadal. All rights reserved.</span>
      <span class="kd-shared-footer-official"><i class="fas fa-shield-halved" aria-hidden="true"></i> City Online Services Portal</span>
    </div>
  </div>
</footer>
