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
  if (!$header.length) {
    return;
  }

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

  /* ——— Champs contact : hauteur auto ——— */
  function autoGrow(oField) {
    if (oField.scrollHeight > oField.clientHeight) {
      oField.style.height = oField.scrollHeight + "px";
    }
  }

  $(".cf-block-input").on("keyup", function () {
    autoGrow(this);
  });
});
