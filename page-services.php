<?php get_header(); ?>
<section class="page-hero services-hero">
  <div class="page-kicker"><span>Commission 02</span><span>Idea · Review · Make</span></div>
  <h1>Let's make<span>it real.</span></h1>
  <p>Bring the idea and tell us how it should be made. Luna Labs can review the brief and shape the right path from digital sculpt to painted object.</p>
</section>

<section class="service-ledger">
  <article><span class="service-ledger-index">01</span><div class="process-orbit orbit-sculpt" aria-hidden="true"><span></span></div><h2>3D Modelling</h2><p>Characters, props and functional objects designed from scratch or adapted from an existing idea, with print-ready geometry built into the process.</p><small>Watertight digital model</small></article>
  <article><span class="service-ledger-index">02</span><div class="process-orbit orbit-print" aria-hidden="true"><span></span></div><h2>3D Printing</h2><p>High-detail resin production for Luna Labs models or your own prepared files, supported, monitored, cleaned and checked in-studio.</p><small>Clean physical print</small></article>
  <article><span class="service-ledger-index">03</span><div class="process-orbit orbit-paint" aria-hidden="true"><span></span></div><h2>Model Painting</h2><p>Acrylic brushwork, airbrushed gradients and hand-finished details, protected with varnish for a complete display-ready result.</p><small>Finished and varnished model</small></article>
</section>

<div id="quote" aria-hidden="true"></div>

<section class="commission-request-section" id="commission-request" aria-labelledby="commission-request-title">
  <div class="commission-request-heading">
    <div><p>Commission request · Quick form</p><h2 id="commission-request-title">Request your<span>piece.</span></h2></div>
    <div class="commission-request-notice"><strong>Request only</strong><p>Submitting this form does not confirm an order or reserve a place. Luna Labs will review the brief and reply with availability, final scope, price, timing and any required deposit.</p></div>
  </div>
  <div class="commission-request-shell">
    <div class="commission-request-step" aria-hidden="true"><span>01</span><div class="quote-orbit"><i></i></div><p>Brief → review → confirmation</p></div>
    <form class="commission-request-form" id="luna-commission-request" enctype="multipart/form-data">
      <div class="luna-honeypot" aria-hidden="true"><label for="request-website">Leave this field empty</label><input id="request-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
      <div class="request-field"><label for="request-name">Your name</label><input id="request-name" name="name" type="text" autocomplete="name" minlength="2" maxlength="100" required></div>
      <div class="request-field"><label for="request-email">Email address</label><input id="request-email" name="email" type="email" inputmode="email" autocomplete="email" maxlength="190" required></div>
      <div class="request-field request-field-wide"><label for="request-project-type">Project type</label><select id="request-project-type" name="projectType" required><option value="">Choose one</option><option value="arch-model">Architectural model</option><option value="scene">Scene / diorama</option><option value="wedding-statue">Wedding statue</option><option value="penholder">Penholder</option><option value="figurine">Figurine</option><option value="dice-tower">Dice tower</option><option value="dice-set">Dice set</option><option value="keychain">Keychain</option><option value="other">Other custom piece</option></select></div>
      <div class="request-field request-field-wide"><label for="request-description">What would you like made?</label><textarea id="request-description" name="description" rows="5" minlength="20" maxlength="2000" placeholder="Describe the piece, size, finish and the details that matter most." required></textarea></div>
      <div class="request-field request-field-wide request-references"><label for="request-reference-images">Reference images <span>optional</span></label><input id="request-reference-images" name="referenceImages[]" type="file" accept="image/jpeg,image/png,image/webp" multiple><small id="request-reference-help">Upload up to 5 JPG, PNG or WebP images (15 MB each, 50 MB total).</small><span class="request-upload-summary" id="request-upload-summary" aria-live="polite">No images selected</span><div class="request-reference-link"><label for="request-reference">Or add a reference link</label><input id="request-reference" name="referenceUrl" type="url" inputmode="url" autocomplete="url" maxlength="500" placeholder="https://"></div></div>
      <div class="request-field request-field-wide request-deadline"><label for="request-deadline">Needed by <span>optional</span></label><input id="request-deadline" name="deadline" type="date"></div>
      <label class="request-consent"><input name="consent" type="checkbox" value="1" required><span>I agree that Luna Labs may use these details to review and respond to my request. See the <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">privacy policy</a>.</span></label>
      <button class="commission-request-submit" type="submit">Send commission request<span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></button>
      <div class="commission-request-status" id="commission-request-status" role="status" aria-live="polite" hidden></div>
    </form>
  </div>
</section>

<section class="commission-contact" aria-labelledby="commission-contact-title">
  <div class="commission-contact-intro"><span>Prefer to speak first?</span><h2 id="commission-contact-title">Contact the studio directly.</h2><p>Send a request or get in touch if your project needs a conversation before the studio prepares a tailored price.</p></div>
  <a href="mailto:<?php echo esc_attr(get_theme_mod('luna_email', 'lunalabs3d@gmail.com')); ?>"><span>Email</span><strong><?php echo esc_html(get_theme_mod('luna_email', 'lunalabs3d@gmail.com')); ?></strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
  <a href="tel:<?php echo esc_attr(get_theme_mod('luna_phone', '+356 7771 8303')); ?>"><span>Phone</span><strong><?php echo esc_html(get_theme_mod('luna_phone', '+356 7771 8303')); ?></strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
  <a href="<?php echo esc_url(get_theme_mod('luna_instagram', 'https://www.instagram.com/lunalabs3d/')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram — @lunalabs3d (opens in a new tab)"><span>Instagram</span><strong>@lunalabs3d</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
</section>

<section class="brief-guide">
  <div><span>Before you submit</span><h2>A useful brief contains four things.</h2></div>
  <ol><li><span>01</span><strong>What it is</strong><p>Character, prop, tabletop object, replacement part or gift.</p></li><li><span>02</span><strong>How it will be used</strong><p>Display, gameplay, production file or finished physical piece.</p></li><li><span>03</span><strong>Scale and deadline</strong><p>Approximate size, important dates and delivery destination.</p></li><li><span>04</span><strong>References</strong><p>Images, sketches and any details that absolutely must survive.</p></li></ol>
</section>
<?php get_footer(); ?>
