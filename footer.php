<!-- Footer -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <img
                src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/images/Popeye Logo Transparent Bg.webp"
                alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                class="footer-logo"
            >
            <p class="footer-brand-copy">Boutique East Africa journeys, tailored with care from the first idea to the final arrival.</p>
        </div>

        <div class="footer-columns">
            <div class="footer-column">
                <div class="footer-heading">
                    <span class="footer-icon" aria-hidden="true">

                    </span>
                    <h3>Social Media</h3>
                </div>
                <div class="footer-social">
                    <span class="footer-social-badge" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="1.8"/>
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                            <circle cx="17.2" cy="6.8" r="1.1" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="footer-social-label">Instagram</span>
                </div>
            </div>

            <div class="footer-column">
                <div class="footer-heading">
                    <span class="footer-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M21 16.9V20A2 2 0 0 1 18.8 22C9.3 21 3 14.7 2 5.2A2 2 0 0 1 4 3h3.1a2 2 0 0 1 2 1.7l.5 3.4a2 2 0 0 1-.6 1.8l-1.5 1.5a16 16 0 0 0 5.9 5.9l1.5-1.5a2 2 0 0 1 1.8-.6l3.4.5A2 2 0 0 1 21 16.9Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <h3>Phone</h3>
                </div>
                <a href="tel:+254726875876">Phone: +254 726 875876</a>
                <a href="tel:+254721378596">Phone: +254 721 378596</a>
            </div>

            <div class="footer-column">
                <div class="footer-heading">
                    <span class="footer-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 5H20V19H4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M4 7L12 13L20 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <h3>Contact</h3>
                </div>
                <a href="mailto:info@popeyetours.co.ke">info@popeyetours.co.ke</a>
                <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Privacy Policy</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> Popeye Tours. All rights reserved.</span>
            <a href="#" class="footer-to-top" aria-label="Back to top">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 14L12 8L18 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
