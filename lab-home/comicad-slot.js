(() => {
  const mountComicad = () => {
    if (document.querySelector('.comicad-slot')) return;

    const ticker = document.querySelector('.shop-category-ticker');
    if (!ticker) return;

    const slot = document.createElement('div');
    slot.className = 'comicad-slot';
    slot.setAttribute('aria-label', 'Advertisement');

    const frame = document.createElement('iframe');
    frame.className = 'comicad-frame';
    frame.title = 'Advertisement';
    frame.src = 'comicad-banner.html';
    frame.loading = 'lazy';
    frame.scrolling = 'no';
    frame.referrerPolicy = 'unsafe-url';

    slot.appendChild(frame);
    ticker.insertAdjacentElement('afterend', slot);
  };

  new MutationObserver(mountComicad).observe(document.documentElement, {
    childList: true,
    subtree: true,
  });
  window.addEventListener('DOMContentLoaded', mountComicad, { once: true });
  mountComicad();
})();
