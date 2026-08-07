(() => {
  const menu = document.querySelector('.mobile-menu-trigger');
  const panel = document.querySelector('#mobile-navigation');
  const mobileShop = panel?.querySelector('.mobile-shop-toggle');
  const mobileShopLinks = panel?.querySelector('.mobile-shop-links');
  const setMobileShop = open => {
    if (!mobileShop || !mobileShopLinks) return;
    mobileShop.setAttribute('aria-expanded', String(open));
    mobileShop.querySelector('i').textContent = open ? '−' : '+';
    mobileShopLinks.hidden = !open;
    mobileShopLinks.querySelectorAll('a').forEach(link => link.tabIndex = open && menu?.getAttribute('aria-expanded') === 'true' ? 0 : -1);
  };
  const setMenu = open => {
    menu?.setAttribute('aria-expanded', String(open));
    panel?.setAttribute('aria-hidden', String(!open));
    panel?.classList.toggle('is-open', open);
    panel?.querySelectorAll(':scope>a,.mobile-shop-heading>a,.mobile-shop-toggle').forEach(item => item.tabIndex = open ? 0 : -1);
    mobileShopLinks?.querySelectorAll('a').forEach(link => link.tabIndex = open && mobileShop?.getAttribute('aria-expanded') === 'true' ? 0 : -1);
  };
  menu?.addEventListener('click', () => setMenu(menu.getAttribute('aria-expanded') !== 'true'));
  mobileShop?.addEventListener('click', () => setMobileShop(mobileShop.getAttribute('aria-expanded') !== 'true'));

  const shopTrigger = document.querySelector('.nav-shop-trigger');
  const shopDropdown = document.querySelector('.nav-shop-dropdown');
  const setShopMenu = open => {
    shopTrigger?.setAttribute('aria-expanded', String(open));
    shopDropdown?.setAttribute('aria-hidden', String(!open));
    shopDropdown?.classList.toggle('is-open', open);
  };
  if (shopTrigger?.tagName === 'BUTTON') {
    shopTrigger.addEventListener('click', event => {
      event.stopPropagation();
      setShopMenu(shopTrigger.getAttribute('aria-expanded') !== 'true');
    });
  }
  document.addEventListener('click', event => {
    if (menu && panel && !menu.closest('.mobile-menu')?.contains(event.target)) setMenu(false);
    if (shopTrigger && shopDropdown && !shopTrigger.closest('.nav-shop-menu')?.contains(event.target)) setShopMenu(false);
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      setMenu(false);
      setShopMenu(false);
    }
  });

  document.querySelectorAll('[data-work-carousel]').forEach(carousel => {
    const rail = carousel.querySelector('.portfolio-rail');
    const slides = [...carousel.querySelectorAll('.portfolio-slide')];
    const markers = [...carousel.querySelectorAll('[data-work-marker]')];
    const filters = [...carousel.querySelectorAll('[data-work-filter]')];
    const current = carousel.querySelector('[data-work-current]');
    const activeNumber = carousel.querySelector('.portfolio-active-number');
    const activeKind = carousel.querySelector('.portfolio-active-kind');
    const activeTitle = carousel.querySelector('.portfolio-active-project h2');
    let activeIndex = 0;
    let visible = slides;
    let scrollFrame = 0;
    let dragging = false;
    let dragged = false;
    let blockClickUntil = 0;
    let dragStart = 0;
    let scrollStart = 0;

    const pad = value => String(value + 1).padStart(2, '0');
    const update = slide => {
      if (!slide || !visible.includes(slide)) return;
      activeIndex = visible.indexOf(slide);
      slides.forEach(item => item.classList.toggle('is-active', item === slide));
      markers.forEach((marker, index) => marker.classList.toggle('active', slides[index] === slide));
      const projectNumber = slides.indexOf(slide);
      if (current) current.textContent = pad(projectNumber);
      if (activeNumber) activeNumber.textContent = pad(projectNumber);
      if (activeKind) activeKind.textContent = slide.dataset.workKind || 'Studio object';
      if (activeTitle) activeTitle.textContent = slide.dataset.workTitle || '';
    };
    const nearest = () => {
      if (!rail || !visible.length) return;
      const center = rail.scrollLeft + rail.clientWidth / 2;
      update(visible.reduce((best, slide) => {
        const slideCenter = slide.offsetLeft + slide.offsetWidth / 2;
        const bestCenter = best.offsetLeft + best.offsetWidth / 2;
        return Math.abs(slideCenter - center) < Math.abs(bestCenter - center) ? slide : best;
      }, visible[0]));
    };
    const go = (index, behavior = 'smooth') => {
      if (!visible.length || !rail) return;
      const normalized = (index + visible.length) % visible.length;
      const slide = visible[normalized];
      rail.scrollTo({left: slide.offsetLeft - (rail.clientWidth - slide.offsetWidth) / 2, behavior});
      update(slide);
    };

    carousel.querySelectorAll('[data-work-previous]').forEach(button => button.addEventListener('click', () => go(activeIndex - 1)));
    carousel.querySelectorAll('[data-work-next]').forEach(button => button.addEventListener('click', () => go(activeIndex + 1)));
    markers.forEach((marker, index) => marker.addEventListener('click', () => {
      const slide = slides[index];
      const visibleIndex = visible.indexOf(slide);
      if (visibleIndex >= 0) go(visibleIndex);
    }));
    filters.forEach(filter => filter.addEventListener('click', () => {
      const selected = filter.dataset.workFilter;
      filters.forEach(item => item.classList.toggle('active', item === filter));
      slides.forEach((slide, index) => {
        const show = selected === 'all' || (slide.dataset.workTypes || '').split(' ').includes(selected);
        slide.hidden = !show;
        if (markers[index]) markers[index].hidden = !show;
      });
      visible = slides.filter(slide => !slide.hidden);
      activeIndex = 0;
      requestAnimationFrame(() => go(0, 'auto'));
    }));
    rail?.addEventListener('scroll', () => {
      cancelAnimationFrame(scrollFrame);
      scrollFrame = requestAnimationFrame(nearest);
    }, {passive: true});
    rail?.addEventListener('keydown', event => {
      if (event.key === 'ArrowLeft') { event.preventDefault(); go(activeIndex - 1); }
      if (event.key === 'ArrowRight') { event.preventDefault(); go(activeIndex + 1); }
    });
    rail?.addEventListener('pointerdown', event => {
      if (event.pointerType !== 'mouse' || event.button !== 0) return;
      dragging = true;
      dragged = false;
      dragStart = event.clientX;
      scrollStart = rail.scrollLeft;
      });
    rail?.addEventListener('pointermove', event => {
      if (!dragging) return;
      const distance = event.clientX - dragStart;
      if (Math.abs(distance) > 8) { dragged = true; rail.setPointerCapture(event.pointerId); }
      rail.scrollLeft = scrollStart - distance;
    });
    const endDrag = event => {
      if (!dragging) return;
      dragging = false;
      if (dragged) blockClickUntil = performance.now() + 300;
      if (rail.hasPointerCapture(event.pointerId)) rail.releasePointerCapture(event.pointerId);
      nearest();
    };
    rail?.addEventListener('pointerup', endDrag);
    rail?.addEventListener('pointercancel', endDrag);
    rail?.addEventListener('dragstart', event => event.preventDefault());
    rail?.addEventListener('click', event => {
      const slide = event.target.closest('.portfolio-slide');
      if (!slide) return;
      if (performance.now() < blockClickUntil) {
        event.preventDefault();
        event.stopPropagation();
      }
    }, true);
    requestAnimationFrame(() => go(0, 'auto'));
  });

  document.querySelectorAll('[data-shop-catalogue]').forEach(catalogue => {
    const filters = [...catalogue.querySelectorAll('[data-product-filter]')];
    const cards = [...catalogue.querySelectorAll('[data-product-categories]')];
    const count = catalogue.querySelector('[data-product-count]');
    filters.forEach(filter => filter.addEventListener('click', () => {
      const selected = filter.dataset.productFilter;
      filters.forEach(item => item.classList.toggle('active', item === filter));
      let visible = 0;
      cards.forEach(card => {
        const show = selected === 'all' || (card.dataset.productCategories || '').split(' ').includes(selected);
        card.hidden = !show;
        if (show) visible += 1;
      });
      if (count) count.textContent = `${visible} ${visible === 1 ? 'object' : 'objects'}`;
      catalogue.querySelector('#products')?.scrollIntoView({behavior: 'smooth', block: 'start'});
    }));
  });

  const form = document.querySelector('#luna-quote');
  const result = document.querySelector('#quote-result');
  if (form && result) {
    form.addEventListener('submit', async event => {
      event.preventDefault();
      const button = form.querySelector('button[type="submit"]');
      button.disabled = true;
      button.firstChild.textContent = 'Calculating…';
      try {
        const payload = Object.fromEntries(new FormData(form).entries());
        const response = await fetch('/wp-json/luna-labs/v1/quote', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(payload)
        });
        if (!response.ok) throw new Error('Quote request failed');
        const data = await response.json();
        const total = Number(data.totalPrice ?? data.total ?? data.price ?? 0);
        const deposit = Number(data.depositAmount ?? data.deposit ?? total / 2);
        if (!total) throw new Error('Invalid quote');
        result.hidden = false;
        result.innerHTML = `<small>Estimated total</small><small>Deposit due today</small><strong>€${total.toFixed(0)}</strong><span>€${deposit.toFixed(0)}</span><p>This is an estimate based on your answers. The final price may be adjusted after the description is reviewed.</p>${data.checkoutUrl ? `<a class="lime-button" href="${data.checkoutUrl}">Reserve with the deposit ↗</a>` : ''}`;
        result.scrollIntoView({behavior: 'smooth', block: 'nearest'});
      } catch {
        result.hidden = false;
        result.textContent = 'The price could not be calculated. Please email the studio for a quote.';
      } finally {
        button.disabled = false;
        button.firstChild.textContent = 'Get instant price';
      }
    });
  }
})();


/* Luna: click-to-enlarge lightbox for product and Work project galleries */
(function () {
  var items = Array.prototype.slice.call(document.querySelectorAll(".product-gallery-item img, .project-gallery img"));
  if (!items.length) return;
  var box = null, imgEl = null, countEl = null, idx = 0;
  function largest(img) {
    var ss = img.getAttribute("srcset"), best = null, bw = -1;
    if (ss) ss.split(",").forEach(function (part) {
      var bits = part.trim().split(/\s+/), w = parseInt(bits[1] || "0", 10);
      if (w > bw) { bw = w; best = bits[0]; }
    });
    return best || img.currentSrc || img.src;
  }
  function render() {
    imgEl.src = largest(items[idx]);
    imgEl.alt = items[idx].alt || "";
    if (countEl) countEl.textContent = (idx + 1) + " / " + items.length;
  }
  function step(n) { idx = (idx + n + items.length) % items.length; render(); }
  function onKey(e) {
    if (!box) return;
    if (e.key === "Escape") close();
    else if (e.key === "ArrowRight" && items.length > 1) step(1);
    else if (e.key === "ArrowLeft" && items.length > 1) step(-1);
  }
  function close() {
    if (!box) return;
    document.removeEventListener("keydown", onKey);
    var b = box; box = null;
    b.classList.remove("is-open");
    document.body.style.overflow = "";
    setTimeout(function () { if (b.parentNode) b.parentNode.removeChild(b); }, 200);
  }
  function open(i) {
    if (box) return;
    idx = i;
    box = document.createElement("div");
    box.className = "ll-lightbox";
    box.setAttribute("role", "dialog");
    box.innerHTML = '<button type="button" class="ll-lightbox-close" aria-label="Close">\u2715</button>' +
      (items.length > 1 ? '<button type="button" class="ll-lightbox-nav ll-lightbox-prev" aria-label="Previous">\u2039</button><button type="button" class="ll-lightbox-nav ll-lightbox-next" aria-label="Next">\u203A</button>' : "") +
      '<img alt=""><div class="ll-lightbox-count"></div>';
    document.body.appendChild(box);
    imgEl = box.querySelector("img");
    countEl = box.querySelector(".ll-lightbox-count");
    render();
    document.body.style.overflow = "hidden";
    requestAnimationFrame(function () { if (box) box.classList.add("is-open"); });
    box.addEventListener("click", function (e) {
      var t = e.target;
      if (!t || typeof t.closest !== "function") { close(); return; }
      if (t.closest(".ll-lightbox-next")) { step(1); return; }
      if (t.closest(".ll-lightbox-prev")) { step(-1); return; }
      if (t === imgEl) return;
      close();
    });
    document.addEventListener("keydown", onKey);
  }
  items.forEach(function (img, i) {
    img.setAttribute("tabindex", "0");
    img.addEventListener("click", function () { open(i); });
    img.addEventListener("keydown", function (e) { if (e.key === "Enter" || e.key === " ") { e.preventDefault(); open(i); } });
  });
})();
