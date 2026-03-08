jQuery(function($) {
    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 600);
        }
    });

    const toggleBtn = $('#mobile-menu-toggle');
    const closeBtn = $('#mobile-menu-close');
    const mobileMenu = $('#mobile-menu');
    const overlay = $('#mobile-menu-overlay');

    function openMenu() {
        toggleBtn.attr('aria-expanded', 'true');
        mobileMenu.addClass('open').removeAttr('hidden');
        overlay.addClass('active').removeAttr('hidden');
    }

    function closeMenu() {
        toggleBtn.attr('aria-expanded', 'false');
        mobileMenu.removeClass('open').attr('hidden', true);
        overlay.removeClass('active').attr('hidden', true);
    }

    toggleBtn.on('click', function() {
        if (mobileMenu.hasClass('open')) {
        closeMenu();
        } else {
        openMenu();
        }
    });

    closeBtn.on('click', function() {
        closeMenu();
    });

    overlay.on('click', function() {
        closeMenu();
    });

    document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.testimonials-slider', {
        slidesPerView: 4,
        spaceBetween: 20,
        loop: true,
        autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        },
        breakpoints: {
        1024: { slidesPerView: 4 },
        768:  { slidesPerView: 2 },
        480:  { slidesPerView: 1 },
        },
        navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        }
    });
    });

    const fetchBtn = $('#fetch-reviews');
    fetchBtn.on('click',function(e) {
        e.preventDefault();
        var termId = carbonFieldsL10n.term_id; // Pass this via wp_localize_script

        jQuery.post(ajaxurl, {
            action: 'fetch_google_reviews',
            term_id: termId
        }, function(response) {
            alert(response.data);
        });
    });

});

jQuery(function($) {
  $('#fetch-reviews-btn').on('click', function(e) {
    e.preventDefault();

    var statusDiv = $('#fetch-reviews-status');
    statusDiv.html('<p>Step 1: Fetching Place ID…</p>');

    $.post(ajaxurl, {
      action: 'fetch_google_reviews',
      term_id: $('#your_term_id_field').val()  // assuming you have this value available
    }, function(response) {
      if (response.success) {
        statusDiv.append('<p>✅ Step 1: Place ID and Reviews fetched successfully.</p>');
        statusDiv.append('<p>✅ Step 2: Caching Reviews in transient.</p>');
        statusDiv.append('<p>✅ Process Complete!</p>');
      } else {
        statusDiv.append('<p style="color:red;">❌ ' + response.data + '</p>');
      }
    }).fail(function(xhr) {
      statusDiv.append('<p style="color:red;">❌ AJAX Request failed: ' + xhr.statusText + '</p>');
    });
  });
});


