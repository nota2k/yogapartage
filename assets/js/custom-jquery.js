jQuery(function ($) {
  var SCROLL_THRESHOLD = 100;

  function getSiteHeader() {
    var $el = $("#site-header");
    if ($el.length) {
      return $el;
    }
    $el = $("header.wp-block-template-part").first();
    if ($el.length) {
      return $el;
    }
    return $(".wp-site-blocks").children("header").first();
  }

  var $header = getSiteHeader();

  if ($header.length) {
    var lastScrollY = window.scrollY || $(window).scrollTop();
    var ticking = false;

    function headerOuterHeight() {
      return $header.outerHeight() || 0;
    }

    function applyBodyOffset() {
      var h = headerOuterHeight();
      $("body").css("padding-top", h ? h + "px" : "");
    }

    function updateHeaderScroll() {
      var y = window.scrollY || $(window).scrollTop();

      if (y <= SCROLL_THRESHOLD) {
        $header.removeClass("is-header-offscreen");
      } else if (y > lastScrollY) {
        $header.addClass("is-header-offscreen");
      } else {
        $header.removeClass("is-header-offscreen");
      }

      lastScrollY = y;
      ticking = false;
    }

    function onScroll() {
      if (!ticking) {
        window.requestAnimationFrame(updateHeaderScroll);
        ticking = true;
      }
    }

    $header.addClass("site-header--scroll-behavior");
    applyBodyOffset();
    updateHeaderScroll();

    $(window).on("scroll", onScroll);
    $(window).on("resize", function () {
      applyBodyOffset();
    });
  }

  /* ——— Entrée au scroll : .anime-left-fade-in / .anime-right-fade-in (front + iframe éditeur) ——— */
  var ANIME_PAST_VIEWPORT_PX = 100;
  var animePastViewportTicking = false;
  var ANIME_SCROLL_SELECTOR =
    ".anime-left-fade-in.is-in-view, .anime-right-fade-in.is-in-view";
  var ANIME_SELECTOR = ".anime-left-fade-in, .anime-right-fade-in";

  function updateAnimePastViewport() {
    document.querySelectorAll(ANIME_SCROLL_SELECTOR).forEach(function (el) {
      var top = el.getBoundingClientRect().top;
      if (top < -ANIME_PAST_VIEWPORT_PX) {
        el.classList.add("is-past-viewport");
      } else {
        el.classList.remove("is-past-viewport");
      }
    });
    animePastViewportTicking = false;
  }

  function onScrollAnimePastViewport() {
    if (!animePastViewportTicking) {
      animePastViewportTicking = true;
      window.requestAnimationFrame(updateAnimePastViewport);
    }
  }

  function initAnimeFadeInScroll() {
    var observedByIo = new WeakSet();

    function reveal(el) {
      el.classList.add("is-in-view");
      window.requestAnimationFrame(function () {
        window.requestAnimationFrame(updateAnimePastViewport);
      });
    }

    var io = null;
    if ("IntersectionObserver" in window) {
      io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              reveal(entry.target);
              io.unobserve(entry.target);
            }
          });
        },
        {
          root: null,
          rootMargin: "0px 0px -5% 0px",
          threshold: 0.12,
        }
      );
    }

    function attachEl(el) {
      if (!el || !el.classList) {
        return;
      }
      if (el.classList.contains("is-in-view")) {
        return;
      }
      if (observedByIo.has(el)) {
        return;
      }
      observedByIo.add(el);
      if (!io) {
        reveal(el);
      } else {
        io.observe(el);
      }
    }

    function scanAnime() {
      document.querySelectorAll(ANIME_SELECTOR).forEach(attachEl);
    }

    scanAnime();

    if (document.body && window.MutationObserver) {
      var moScheduled = false;
      var mo = new MutationObserver(function () {
        if (moScheduled) {
          return;
        }
        moScheduled = true;
        window.requestAnimationFrame(function () {
          moScheduled = false;
          scanAnime();
        });
      });
      mo.observe(document.body, { childList: true, subtree: true });
    }

    $(window).on("scroll", onScrollAnimePastViewport);
    $(window).on("resize", updateAnimePastViewport);
    updateAnimePastViewport();
  }

  initAnimeFadeInScroll();

  /* ——— Champs contact : hauteur auto ——— */
  function autoGrow(oField) {
    if (oField.scrollHeight > oField.clientHeight) {
      oField.style.height = oField.scrollHeight + "px";
    }
  }

  $(".cf-block-input").on("keyup", function () {
    autoGrow(this);
  });

  /* ——— Menu mobile (bloc Navigation) : fermeture animée au clic sur un lien ——— */
  var NAV_MOBILE_MQ = window.matchMedia("(max-width: 599px)");
  var NAV_CLOSE_ANIM_MS = 280;

  function yogapartageNavigateFromNavLink(link) {
    var href = link.getAttribute("href");
    if (!href || href === "#") {
      return;
    }
    if (link.getAttribute("target") === "_blank") {
      window.open(href, "_blank");
      return;
    }
    if (href.charAt(0) === "#" && href.length > 1) {
      var id = decodeURIComponent(href.slice(1));
      var targetEl = document.getElementById(id);
      if (!targetEl && window.CSS && typeof CSS.escape === "function") {
        try {
          targetEl = document.querySelector('[name="' + CSS.escape(id) + '"]');
        } catch (err) {
          targetEl = null;
        }
      }
      if (targetEl) {
        if (window.history && window.history.pushState) {
          window.history.pushState(null, "", href);
        }
        targetEl.scrollIntoView({ behavior: "smooth" });
      } else {
        window.location.href = href;
      }
      return;
    }
    window.location.href = href;
  }

  function yogapartageCloseMobileNavWithAnimation(link) {
    var container = link.closest(".wp-block-navigation__responsive-container");
    if (!container || !container.classList.contains("is-menu-open")) {
      return false;
    }
    var closeBtn = container.querySelector(
      ".wp-block-navigation__responsive-container-close"
    );
    if (!closeBtn) {
      return false;
    }

    if (!NAV_MOBILE_MQ.matches) {
      closeBtn.click();
      yogapartageNavigateFromNavLink(link);
      return true;
    }
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      closeBtn.click();
      yogapartageNavigateFromNavLink(link);
      return true;
    }

    var done = false;
    var timeoutId;
    function finish() {
      if (done) {
        return;
      }
      done = true;
      window.clearTimeout(timeoutId);
      closeBtn.click();
      container.classList.remove("yogapartage-nav-closing");
      yogapartageNavigateFromNavLink(link);
    }

    container.classList.add("yogapartage-nav-closing");
    timeoutId = window.setTimeout(finish, NAV_CLOSE_ANIM_MS + 80);
    container.addEventListener(
      "animationend",
      function onAnimEnd(ev) {
        if (ev.target !== container) {
          return;
        }
        container.removeEventListener("animationend", onAnimEnd);
        finish();
      }
    );

    return true;
  }

  $(document).on(
    "click",
    ".wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation__responsive-container-content a[href]",
    function (e) {
      if (!NAV_MOBILE_MQ.matches) {
        return;
      }
      if (e.defaultPrevented) {
        return;
      }
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
        return;
      }
      if (yogapartageCloseMobileNavWithAnimation(this)) {
        e.preventDefault();
      }
    }
  );
});
