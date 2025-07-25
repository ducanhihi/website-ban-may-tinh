document.addEventListener("DOMContentLoaded", function () {
    const addToCartButtons = document.querySelectorAll(".add-to-cart");

    addToCartButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const container =
                this.closest(".product-card") ||
                this.closest(".product-detail-form") ||
                document;

            const productName =
                container.querySelector(".product-name")?.textContent || "";
            const productId = this.dataset.productId;

            const qtyInput = container.querySelector('input[name="quantity"]');
            let quantity = 1;
            if (qtyInput) {
                const val = parseInt(qtyInput.value, 10) || 1;
                const min = parseInt(qtyInput.min, 10) || 1;
                const max = parseInt(qtyInput.max, 10) || val;
                quantity = Math.min(Math.max(val, min), max);
            }

            if (this.disabled) {
                return window.showNotification("Sản phẩm hiện tại hết hàng", "error");
            }

            this.disabled = true;
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            const requestData = {
                type: "ADD_TO_CART",
                quantity: quantity,
                _token: token,
            };

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
                        window.showNotification(
                            "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng",
                            "warning"
                        );
                        return Promise.reject("Unauthorized");
                    }
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP ${response.status}`);
                    }
                    return data;
                })
                .then((data) => {
                    window.showNotification(
                        data.message ||
                        `Đã thêm "${productName}" (${quantity} sp) vào giỏ hàng`,
                        "success"
                    );

                    if (data.cartCount !== undefined) {
                        updateCartCount(data.cartCount);
                    } else {
                        const current = parseInt(
                            document.querySelector(".cart-count").textContent,
                            10
                        );
                        updateCartCount(current + quantity);
                    }

                    this.style.transform = "scale(1.2)";
                    setTimeout(() => (this.style.transform = "scale(1)"), 200);
                    container.classList.add("added-to-cart");
                    setTimeout(() => container.classList.remove("added-to-cart"), 1000);
                })
                .catch((error) => {
                    if (error !== "Unauthorized") {
                        console.error("Add to cart error:", error);
                        window.showNotification(
                            error.message || "Có lỗi khi thêm sản phẩm vào giỏ hàng",
                            "error"
                        );
                    }
                })
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = originalContent;
                });
        });
    });
});
