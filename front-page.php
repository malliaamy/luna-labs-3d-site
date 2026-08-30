<?php
$home = add_query_arg([
    'desktopAngle' => (float) get_theme_mod('luna_desktop_angle', -5),
    'mobileAngle' => (float) get_theme_mod('luna_mobile_angle', -50),
    'scrollRotation' => (float) get_theme_mod('luna_scroll_rotation', 83),
], get_template_directory_uri() . '/lab-home/index.html?v=17');
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#080906">
  <?php wp_head(); ?>
  <style>html,body,iframe{width:100%;height:100%;margin:0;border:0}body{overflow:hidden;background:#080906}iframe{display:block}body:has(#wpadminbar) iframe{height:calc(100% - var(--wp-admin--admin-bar--height,32px))}</style>
</head>
<body>
<iframe id="luna-home" src="<?php echo esc_url($home); ?>" title="Luna Labs 3D"></iframe>
<script>
(() => {
  const frame = document.getElementById('luna-home');
  const syncPrices = async doc => {
    const products = {
      '/product/dragon-dice-tower': 'dragon-dice-tower',
      '/product/black-gold-dice-set': 'black-gold-dice-set',
      '/product/chopper-one-piece-figurine-stl': 'chopper-one-piece-figurine-stl'
    };
    await Promise.all(Object.entries(products).map(async ([href, slug]) => {
      try {
        const response = await fetch('/wp-json/wc/store/v1/products?slug=' + encodeURIComponent(slug), { credentials: 'same-origin' });
        const [product] = await response.json();
        const card = doc.querySelector('a[href="' + href + '"], a[href="' + href + '/"]');
        const price = card && card.querySelector('div > span:last-child');
        if (!price || !product || !product.prices) return;
        const minor = Number(product.prices.currency_minor_unit || 2);
        const amount = href === '/product/dragon-dice-tower' ? 150 : Number(product.prices.price) / Math.pow(10, minor);
        price.textContent = new Intl.NumberFormat('en-IE', {
          style: 'currency',
          currency: product.prices.currency_code || 'EUR',
          minimumFractionDigits: 0,
          maximumFractionDigits: 2
        }).format(amount);
      } catch (error) {}
    }));
  };
  const wireNavigation = doc => {
    if (!doc || !doc.documentElement || doc.documentElement.dataset.wpNav) return;
    doc.documentElement.dataset.wpNav = '1';
    doc.addEventListener('click', event => {
      const target = event.target && event.target.nodeType === 1 ? event.target : event.target && event.target.parentElement;
      const link = target && typeof target.closest === 'function' ? target.closest('a[href]') : null;
      if (!link) return;
      const url = new URL(link.getAttribute('href') || link.href, frame.contentWindow.location.href);
      const routes = {'#work':'/work/','#shop':'/shop/','#commissions':'/services/','#top':'/'};
      const label = (link.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase();
      const labelledRoutes = {
        'about':'/about/',
        'start a project':'/services/',
        'build your quote':'/services/',
        'customize colours in 3d':'/product/dragon-dice-tower/',
        'see how it was made':'/work/dragon-dice-tower/'
      };
      const legacyProduct = /(^|\.)lunalabs3d\.com$/i.test(url.hostname) && url.pathname.startsWith('/product/');
      if (url.origin !== location.origin && !legacyProduct && !labelledRoutes[label]) return;
      const destination = labelledRoutes[label] || (url.pathname.endsWith('/lab-home/index.html') ? (routes[url.hash] || '/') : url.pathname + url.search + url.hash);
      event.preventDefault();
      event.stopImmediatePropagation();
      parent.location.assign(destination);
    }, true);
  };
  const navigationPoll = setInterval(() => {
    try { wireNavigation(frame.contentDocument); } catch (error) {}
  }, 50);
  window.addEventListener('pagehide', () => clearInterval(navigationPoll), { once: true });
  frame.addEventListener('load', () => {
    const doc = frame.contentDocument;
    if (!doc) return;
    if (frame.contentWindow.location.href === 'about:blank') return;
    clearInterval(navigationPoll);
    wireNavigation(doc);
    const dragonPrice = doc.querySelector('a[href="/product/dragon-dice-tower"] div > span:last-child, a[href="/product/dragon-dice-tower/"] div > span:last-child');
        if (dragonPrice) dragonPrice.textContent = '€150';
        syncPrices(doc);
        if (!doc.getElementById('luna-shared-footer-css')) {
          var fcss = doc.createElement('link');
          fcss.id = 'luna-shared-footer-css';
          fcss.rel = 'stylesheet';
          fcss.href = '<?php echo esc_url(get_template_directory_uri()); ?>/assets/css/footer-shared.css?v=<?php echo LUNA_THEME_VERSION; ?>';
          doc.head.appendChild(fcss);
        }
        let cartCount = 0;
        const paintBadge = () => {
          if (!cartCount) return;
          Array.prototype.forEach.call(doc.querySelectorAll('a'), a => {
            if (a.querySelector('.luna-cart-count')) return;
            const label = (a.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase();
            if (label !== 'shop') return;
            const b = doc.createElement('span');
            b.className = 'luna-cart-count';
            b.textContent = cartCount;
            b.setAttribute('style', 'display:inline-flex;align-items:center;justify-content:center;min-width:17px;height:17px;padding:0 5px;margin-left:6px;border-radius:999px;background:#c8ff3d;color:#080906;font-size:8px;font-weight:800;line-height:1;vertical-align:2px');
            a.appendChild(b);
          });
          const trigger = doc.querySelector('.mobile-menu-trigger');
          if (trigger && !trigger.querySelector('.luna-cart-count')) {
            const mb = doc.createElement('span');
            mb.className = 'luna-cart-count';
            mb.textContent = cartCount;
            mb.setAttribute('style', 'display:inline-flex;align-items:center;justify-content:center;min-width:17px;height:17px;padding:0 5px;margin:0 7px;border-radius:999px;background:#080906;color:#c8ff3d;font-size:9px;font-weight:800;line-height:1;vertical-align:1px');
            const glyph = trigger.querySelector('.menu-glyph');
            if (glyph) trigger.insertBefore(mb, glyph); else trigger.appendChild(mb);
          }
        };
        fetch('/wp-json/wc/store/v1/cart', { credentials: 'same-origin', cache: 'no-store' })
          .then(r => r.json())
          .then(c => { cartCount = (c && c.items_count) || 0; paintBadge(); if (doc.body && frame.contentWindow.MutationObserver) new frame.contentWindow.MutationObserver(paintBadge).observe(doc.body, { childList: true, subtree: true }); })
          .catch(() => {});
  });
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
