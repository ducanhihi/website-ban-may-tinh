        $(function () {
    // LOẠI BỎ phần quantity buttons - để main-home.js xử lý
    // $('.js-minus').on('click', function(e) { ... });  // XÓA ĐOẠN NÀY
    // $('.js-plus').on('click', function(e) { ... });   // XÓA ĐOẠN NÀY

    // CHỈ GIỮ LẠI phần Add to Cart functionality
    $("#btnAddToCart").on('click', function(e) {
        e.preventDefault();

        // Get product info
        const productName = $(".product-title").text().trim();
        const productId = $(this).data('product-id');

        // Get quantity from input
        const quantity = parseInt($('input[name="quantity"]').val()) || 1;
        const maxQuantity = parseInt($('input[name="quantity"]').attr('max')) || quantity;

        // Validate quantity
        if (quantity > maxQuantity) {
            if (typeof window.showNotification === 'function') {
                window.showNotification(`Số lượng không được vượt quá ${maxQuantity}`, "error");
            }
            return;
        }

        // Disable button and show loading
        if (this.disabled) {
            if (typeof window.showNotification === 'function') {
                window.showNotification("Sản phẩm hiện tại hết hàng", "error");
            }
            return;
        }

        $(this).prop('disabled', true);
        const originalContent = $(this).html();
        $(this).html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang thêm...');

        // Prepare request data
        const token = $('meta[name="csrf-token"]').attr('content');
        const requestData = {
            type: "ADD_TO_CART",
            quantity: quantity,
            _token: token,
        };

        // Make AJAX request
        fetch(`/add/${productId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify(requestData),
        })
            .then(async (response) => {
                const data = await response.json();

                if (response.status === 401) {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(
                            "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng",
                            "warning"
                        );
                    }
                    return Promise.reject("Unauthorized");
                }
                if (!response.ok) {
                    throw new Error(data.message || `HTTP ${response.status}`);
                }
                return data;
            })
            .then((data) => {
                if (typeof window.showNotification === 'function') {
                    window.showNotification(
                        data.message || `Đã thêm "${productName}" (${quantity} sp) vào giỏ hàng`,
                        "success"
                    );
                }

                if (typeof window.updateCartCount === 'function') {
                    if (data.cartCount !== undefined) {
                        window.updateCartCount(data.cartCount);
                    } else {
                        const current = parseInt($(".cart-count").text(), 10) || 0;
                        window.updateCartCount(current + quantity);
                    }
                }

                // Visual feedback
                $(this).css('transform', 'scale(1.2)');
                setTimeout(() => $(this).css('transform', 'scale(1)'), 200);
            })
            .catch((error) => {
                if (error !== "Unauthorized") {
                    console.error("Add to cart error:", error);
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(
                            error.message || "Có lỗi khi thêm sản phẩm vào giỏ hàng",
                            "error"
                        );
                    }
                }
            })
            .finally(() => {
                $(this).prop('disabled', false).html(originalContent);
            });
    });

    // Các phần khác giữ nguyên (description toggle, star rating, review form, image gallery)
    $('#descriptionToggle').click(function() {
    var $content = $('#descriptionContent');
    var $button = $(this);

    if ($content.hasClass('show')) {
    $button.html('<i class="fas fa-chevron-down mr-1"></i> Xem thêm');
} else {
    $button.html('<i class="fas fa-chevron-up mr-1"></i> Thu gọn');
}
});

    // Star rating functionality
    $('.star-label').on('click', function() {
    var rating = $(this).find('input').val();
    var ratingTexts = {
    1: 'Rất tệ',
    2: 'Tệ',
    3: 'Bình thường',
    4: 'Tốt',
    5: 'Rất tốt'
};

    $('#ratingText').text(ratingTexts[rating] + ' (' + rating + ' sao)');

    $('.star-icon').removeClass('active');
    for(var i = 1; i <= rating; i++) {
    $('[data-rating="' + i + '"]').addClass('active');
}
});

    $('.star-label').on('mouseenter', function() {
    var rating = $(this).find('input').val();
    $('.star-icon').removeClass('hover');
    for(var i = 1; i <= rating; i++) {
    $('[data-rating="' + i + '"]').addClass('hover');
}
});

    $('.rating-stars').on('mouseleave', function() {
    $('.star-icon').removeClass('hover');
});

    // Review form submission
    $('#reviewForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();
    var submitBtn = $(this).find('button[type="submit"]');
    var originalText = submitBtn.html();

    submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...').prop('disabled', true);

    $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: formData,
    success: function(response) {
    if(response.success) {
    $('#reviewModal').modal('hide');

    if (typeof window.showNotification === 'function') {
    window.showNotification(response.message || 'Đánh giá đã được gửi thành công!', 'success');
}

    setTimeout(function() {
    location.reload();
}, 2000);
}
},
    error: function(xhr) {
    var errorMessage = 'Có lỗi xảy ra khi gửi đánh giá!';
    if(xhr.responseJSON && xhr.responseJSON.message) {
    errorMessage = xhr.responseJSON.message;
}

    if (typeof window.showNotification === 'function') {
    window.showNotification(errorMessage, 'error');
}
},
    complete: function() {
    submitBtn.html(originalText).prop('disabled', false);
}
});
});

    // Image gallery functionality
    if (typeof $.fn.slick !== 'undefined') {
    $('#sliderSyncingNav').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    fade: true,
    asNavFor: '#sliderSyncingThumb'
});

    $('#sliderSyncingThumb').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    asNavFor: '#sliderSyncingNav',
    dots: false,
    centerMode: false,
    focusOnSelect: true,
    responsive: [
{
    breakpoint: 992,
    settings: {
    slidesToShow: 4
}
},
{
    breakpoint: 768,
    settings: {
    slidesToShow: 3
}
}
    ]
});
}
});

