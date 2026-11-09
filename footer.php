    </main>

    <?php
    // React (src/components/Footer.js) replaces this markup; the fallback keeps
    // NAP and key links in the server HTML.
    $apex_contact = apex_contact();
    ?>
    <div id="apex-footer">
      <footer class="bg-apex-night py-16 text-sm text-white/70">
        <div class="apex-container">
          <address class="not-italic">
            <strong class="text-white"><?php echo esc_html($apex_contact['name']); ?></strong><br>
            <?php echo esc_html($apex_contact['street']); ?>, <?php echo esc_html($apex_contact['city'] . ', ' . $apex_contact['region'] . ' ' . $apex_contact['postalCode']); ?><br>
            <a href="<?php echo esc_attr($apex_contact['phoneHref']); ?>"><?php echo esc_html($apex_contact['phone']); ?></a> ·
            <a href="mailto:<?php echo esc_attr($apex_contact['email']); ?>"><?php echo esc_html($apex_contact['email']); ?></a>
          </address>
        </div>
      </footer>
    </div>

    <?php wp_footer(); ?>
  </body>
</html>
