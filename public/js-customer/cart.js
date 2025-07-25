// Đánh dấu rằng file này đã được load
window.cartInitialized = true

console.log("=== CART.JS LOADED ===")

// Biến toàn cục
const selectedProducts = new Set()
let promoApplied = false
let isProcessing = false

// Lấy dữ liệu từ window object
const cartData = window.cartData || {}
const cartUrls = window.cartUrls || {}

console.log("Cart data from window:", cartData)
console.log("Cart URLs from window:", cartUrls)

// Đảm bảo hàm updateCartCount có sẵn
function ensureUpdateCartCount() {
    // Nếu chưa có hàm updateCartCount, tạo một hàm tạm thời
    if (typeof window.updateCartCount !== "function") {
        console.log("Creating temporary updateCartCount function")
        window.updateCartCount = (count) => {
            const cartCountElement = document.querySelector(".cart-count")
            if (cartCountElement) {
                cartCountElement.textContent = count

                // Add animation
                cartCountElement.style.transform = "scale(1.3)"
                cartCountElement.style.backgroundColor = "#4caf50"

                setTimeout(() => {
                    cartCountElement.style.transform = "scale(1)"
                    cartCountElement.style.backgroundColor = "#1a4b8c"
                }, 300)
            }
        }
    }
}

// Đảm bảo hàm showNotification có sẵn
function ensureShowNotification() {
    // Kiểm tra xem đã có hàm showNotification global chưa
    if (typeof window.showNotification !== "function") {
        console.log("Creating global showNotification function")

        window.showNotification = (message, type = "success") => {
            // Remove existing notifications
            document.querySelectorAll(".cart-notification").forEach((notification) => notification.remove())

            const notification = document.createElement("div")
            notification.className = `cart-notification ${type}`

            // Create notification content
            const content = document.createElement("div")
            content.style.display = "flex"
            content.style.alignItems = "center"
            content.style.gap = "10px"

            // Add icon based on type
            const icon = document.createElement("i")
            switch (type) {
                case "success":
                    icon.className = "fas fa-check-circle"
                    break
                case "error":
                    icon.className = "fas fa-exclamation-circle"
                    break
                case "warning":
                    icon.className = "fas fa-exclamation-triangle"
                    break
                default:
                    icon.className = "fas fa-info-circle"
            }

            const messageText = document.createElement("span")
            messageText.textContent = message

            content.appendChild(icon)
            content.appendChild(messageText)
            notification.appendChild(content)

            // Set notification styles
            notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 10000;
        max-width: 350px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      `

            // Set colors based on type
            switch (type) {
                case "success":
                    notification.style.background = "#4caf50"
                    notification.style.color = "white"
                    break
                case "error":
                    notification.style.background = "#e53935"
                    notification.style.color = "white"
                    break
                case "warning":
                    notification.style.background = "#ff9800"
                    notification.style.color = "white"
                    break
                default:
                    notification.style.background = "#2196f3"
                    notification.style.color = "white"
            }

            document.body.appendChild(notification)

            // Show notification
            setTimeout(() => {
                notification.style.transform = "translateX(0)"
            }, 100)

            // Hide notification after 4 seconds
            setTimeout(() => {
                notification.style.transform = "translateX(100%)"
                setTimeout(() => {
                    notification.remove()
                }, 300)
            }, 4000)

            // Allow manual close
            notification.addEventListener("click", () => {
                notification.style.transform = "translateX(100%)"
                setTimeout(() => {
                    notification.remove()
                }, 300)
            })
        }
    }
}

// Gọi hàm để đảm bảo các hàm global có sẵn
ensureUpdateCartCount()
ensureShowNotification()

// Tính tổng số lượng sản phẩm trong giỏ hàng
function calculateTotalCartCount() {
    let totalCount = 0
    Object.values(cartData).forEach((product) => {
        if (product && product.quantity) {
            totalCount += Number.parseInt(product.quantity)
        }
    })
    console.log("Calculated total cart count:", totalCount)
    return totalCount
}

// Cập nhật số lượng sản phẩm và gửi đến server
function updateQuantity(productId, change) {
    console.log("updateQuantity called:", productId, change)

    if (isProcessing) {
        console.log("Already processing, skipping...")
        return
    }

    const qtyElement = document.getElementById(`qty-${productId}`)
    if (!qtyElement) {
        console.error("Quantity element not found for product:", productId)
        return
    }

    const currentQty = Number.parseInt(qtyElement.textContent)
    const newQty = currentQty + change

    console.log("Current qty:", currentQty, "New qty:", newQty)

    if (newQty < 1) {
        console.log("New quantity is less than 1, skipping...")
        window.showNotification("Số lượng không thể nhỏ hơn 1", "warning")
        return
    }

    // Đánh dấu đang xử lý
    isProcessing = true

    // Cập nhật UI ngay lập tức để phản hồi nhanh
    const originalQty = qtyElement.textContent
    qtyElement.textContent = newQty

    // Disable buttons tạm thời
    const minusBtn = document.querySelector(`.minus-btn[data-product-id="${productId}"]`)
    const plusBtn = document.querySelector(`.plus-btn[data-product-id="${productId}"]`)
    if (minusBtn) minusBtn.disabled = true
    if (plusBtn) plusBtn.disabled = true

    // Cập nhật cartData nếu tồn tại
    if (cartData[productId]) {
        cartData[productId].quantity = newQty
    }

    // Cập nhật tổng tiền nếu sản phẩm được chọn
    if (selectedProducts.has(productId.toString())) {
        updateTotals()
    }

    // Gửi AJAX request đến server
    const updateUrl = cartUrls.update.replace(":id", productId)

    fetch(updateUrl, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
                document.querySelector('input[name="_token"]')?.value,
            Accept: "application/json",
        },
        body: JSON.stringify({
            quantity: newQty,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            return response.json()
        })
        .then((data) => {
            console.log("Server response:", data)
            if (data.success) {
                console.log("Quantity updated successfully on server")
                window.showNotification("Cập nhật số lượng thành công", "success")

                // Cập nhật cart count trong header
                const totalCount = calculateTotalCartCount()
                console.log("Updating cart count to:", totalCount)
                window.updateCartCount(totalCount)
            } else {
                console.error("Server update failed:", data.message)
                // Khôi phục giá trị cũ
                qtyElement.textContent = originalQty
                if (cartData[productId]) {
                    cartData[productId].quantity = currentQty
                }
                if (selectedProducts.has(productId.toString())) {
                    updateTotals()
                }
                window.showNotification(data.message || "Cập nhật thất bại", "error")
            }
        })
        .catch((error) => {
            console.error("Error updating quantity:", error)
            // Khôi phục giá trị cũ nếu có lỗi
            qtyElement.textContent = originalQty
            if (cartData[productId]) {
                cartData[productId].quantity = currentQty
            }
            if (selectedProducts.has(productId.toString())) {
                updateTotals()
            }
            window.showNotification("Có lỗi xảy ra khi cập nhật", "error")
        })
        .finally(() => {
            // Enable lại buttons
            if (minusBtn) minusBtn.disabled = false
            if (plusBtn) plusBtn.disabled = false
            isProcessing = false
        })

    console.log("Quantity update request sent")
}

// Xóa sản phẩm khỏi giỏ hàng
function removeItem(productId) {
    console.log("removeItem called:", productId)

    if (isProcessing) {
        console.log("Already processing, skipping...")
        return
    }

    if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
        isProcessing = true

        const itemElement = document.querySelector(`.cart-item[data-product-id="${productId}"]`)
        if (itemElement) {
            itemElement.style.opacity = "0.5"
            itemElement.style.pointerEvents = "none"
        }

        // Xóa khỏi selectedProducts ngay lập tức
        selectedProducts.delete(productId.toString())
        updateSelectedProducts()

        // Gửi AJAX request đến server
        const deleteUrl = cartUrls.delete.replace(":id", productId)

        fetch(deleteUrl, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
                    document.querySelector('input[name="_token"]')?.value,
                Accept: "application/json",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`)
                }
                return response.json()
            })
            .then((data) => {
                console.log("Server response:", data)
                if (data.success) {
                    // Xóa khỏi DOM và cartData
                    if (itemElement) {
                        itemElement.style.transition = "all 0.3s ease"
                        itemElement.style.transform = "translateX(-100%)"
                        setTimeout(() => {
                            itemElement.remove()
                        }, 300)
                    }

                    // Xóa khỏi cartData
                    delete cartData[productId]

                    // Cập nhật cart count trong header
                    const totalCount = calculateTotalCartCount()
                    console.log("Updating cart count after remove to:", totalCount)
                    window.updateCartCount(totalCount)

                    // Cập nhật số lượng sản phẩm trong trang cart
                    const cartCount = Object.keys(cartData).length
                    const cartCountElement = document.querySelector(".cart-count")
                    if (cartCountElement) {
                        cartCountElement.textContent = `${cartCount} sản phẩm trong giỏ hàng`
                    }

                    // Kiểm tra nếu giỏ hàng trống
                    if (Object.keys(cartData).length === 0) {
                        setTimeout(() => {
                            location.reload() // Reload để hiển thị empty cart
                        }, 500)
                    }

                    window.showNotification("Xóa sản phẩm thành công", "success")
                    console.log("Item removed successfully")
                } else {
                    console.error("Server delete failed:", data.message)
                    // Khôi phục trạng thái nếu server báo lỗi
                    if (itemElement) {
                        itemElement.style.opacity = "1"
                        itemElement.style.pointerEvents = "auto"
                    }
                    window.showNotification(data.message || "Xóa sản phẩm thất bại", "error")
                }
            })
            .catch((error) => {
                console.error("Error removing item:", error)
                // Khôi phục trạng thái nếu có lỗi
                if (itemElement) {
                    itemElement.style.opacity = "1"
                    itemElement.style.pointerEvents = "auto"
                }
                window.showNotification("Có lỗi xảy ra khi xóa sản phẩm", "error")
            })
            .finally(() => {
                isProcessing = false
            })
    }
}

// Chọn/bỏ chọn tất cả sản phẩm
function selectAllProducts() {
    console.log("selectAllProducts called")

    const selectAllCheckbox = document.getElementById("selectAll")
    const productCheckboxes = document.querySelectorAll(".product-checkbox:not(:disabled)")

    console.log("Found checkboxes:", productCheckboxes.length)

    productCheckboxes.forEach((checkbox) => {
        checkbox.checked = selectAllCheckbox.checked
        const productId = checkbox.dataset.productId

        if (selectAllCheckbox.checked) {
            selectedProducts.add(productId)
        } else {
            selectedProducts.delete(productId)
        }
    })

    updateSelectedProducts()
}

// Cập nhật danh sách sản phẩm được chọn
function updateSelectedProducts() {
    console.log("updateSelectedProducts called")

    // Cập nhật selectedProducts set
    selectedProducts.clear()
    document.querySelectorAll(".product-checkbox:checked").forEach((checkbox) => {
        selectedProducts.add(checkbox.dataset.productId)
    })

    console.log("Selected products:", Array.from(selectedProducts))

    // Cập nhật visual state cho các cart items
    document.querySelectorAll(".cart-item").forEach((item) => {
        const productId = item.dataset.productId
        if (selectedProducts.has(productId)) {
            item.classList.add("selected")
        } else {
            item.classList.remove("selected")
        }
    })

    // Cập nhật số lượng sản phẩm được chọn
    const selectedCountElement = document.getElementById("selectedCount")
    const checkoutCountElement = document.getElementById("checkoutCount")

    if (selectedCountElement) selectedCountElement.textContent = selectedProducts.size
    if (checkoutCountElement) checkoutCountElement.textContent = selectedProducts.size

    // Cập nhật danh sách sản phẩm được chọn
    updateSelectedProductsList()

    // Cập nhật tổng tiền
    updateTotals()

    // Cập nhật trạng thái nút checkout
    updateCheckoutButtons()

    // Cập nhật hidden input cho form checkout
    updateHiddenInputs()
}

// Cập nhật hidden inputs cho form checkout
function updateHiddenInputs() {
    const selectedProductsInput = document.getElementById("selectedProductsInput")
    if (!selectedProductsInput) return

    // Chuyển Set thành mảng và nối thành chuỗi ngăn cách bởi dấu phẩy
    const selectedProductIds = Array.from(selectedProducts)
        .filter((productId) => cartData[productId] && cartData[productId].inStock)
        .join(",")

    selectedProductsInput.value = selectedProductIds
    console.log("Updated hidden input:", selectedProductIds)
}

// Cập nhật danh sách sản phẩm được chọn trong summary
function updateSelectedProductsList() {
    const listContainer = document.getElementById("selectedProductsList")
    if (!listContainer) return

    if (selectedProducts.size === 0) {
        listContainer.innerHTML = '<p class="no-selection">Chưa có sản phẩm nào được chọn</p>'
        return
    }

    let html = ""
    selectedProducts.forEach((productId) => {
        const product = cartData[productId]
        if (product) {
            html += `
                <div class="selected-item">
                    <span>${product.name}</span>
                    <span>x${product.quantity}</span>
                </div>
            `
        }
    })

    listContainer.innerHTML = html
}

// Áp dụng mã giảm giá
function applyPromo() {
    console.log("applyPromo called")

    const promoCodeInput = document.getElementById("promoCode")
    const promoSuccess = document.getElementById("promoSuccess")

    if (!promoCodeInput || !promoSuccess) {
        console.error("Promo elements not found")
        return
    }

    const promoCode = promoCodeInput.value.trim().toUpperCase()
    console.log("Promo code:", promoCode)

    if (promoCode === "TECH10") {
        promoApplied = true
        promoSuccess.style.display = "block"
        window.showNotification("Mã giảm giá đã được áp dụng", "success")
        console.log("Promo applied successfully")
    } else if (promoCode === "") {
        window.showNotification("Vui lòng nhập mã giảm giá", "warning")
    } else {
        promoApplied = false
        promoSuccess.style.display = "none"
        window.showNotification("Mã giảm giá không hợp lệ", "error")
        console.log("Invalid promo code")
    }

    updateTotals()
}

// Cập nhật tổng tiền
function updateTotals() {
    let subtotal = 0

    selectedProducts.forEach((productId) => {
        const product = cartData[productId]
        if (product && product.inStock) {
            subtotal += product.price * product.quantity
        }
    })

    console.log("Calculated subtotal:", subtotal)

    const shipping = subtotal > 500000 ? 0 : subtotal > 0 ? 30000 : 0
    const tax = subtotal * 0.1
    const discount = promoApplied ? subtotal * 0.1 : 0
    const total = subtotal + shipping  - discount

    // Cập nhật giao diện
    const subtotalElement = document.getElementById("subtotal")
    const shippingElement = document.getElementById("shipping")
    const taxElement = document.getElementById("tax")
    const totalElement = document.getElementById("total")

    if (subtotalElement) subtotalElement.textContent = `${formatCurrency(subtotal)} VNĐ`
    if (shippingElement) {
        shippingElement.textContent =
            shipping === 0 ? (subtotal > 0 ? "Miễn phí" : "0 VNĐ") : `${formatCurrency(shipping)} VNĐ`
    }
    if (taxElement) taxElement.textContent = `${formatCurrency(tax)} VNĐ`
    if (totalElement) totalElement.textContent = `${formatCurrency(total)} VNĐ`

    // Hiển thị/ẩn dòng giảm giá
    const discountRow = document.getElementById("discountRow")
    const discountElement = document.getElementById("discount")

    if (discount > 0 && discountRow && discountElement) {
        discountRow.style.display = "flex"
        discountElement.textContent = `-${formatCurrency(discount)} VNĐ`
    } else if (discountRow) {
        discountRow.style.display = "none"
    }

    // Hiển thị thông tin vận chuyển
    const shippingInfo = document.getElementById("shippingInfo")
    const shippingText = document.getElementById("shippingText")

    if (subtotal > 0 && shippingInfo && shippingText) {
        shippingInfo.style.display = "block"
        if (shipping === 0) {
            shippingText.textContent = "Miễn phí vận chuyển"
        } else {
            const remaining = 500000 - subtotal
            shippingText.textContent = `Mua thêm ${formatCurrency(remaining)} VNĐ để được miễn phí vận chuyển`
        }
    } else if (shippingInfo) {
        shippingInfo.style.display = "none"
    }
}

// Cập nhật trạng thái nút checkout
function updateCheckoutButtons() {
    const checkoutBtn = document.getElementById("checkoutBtn")
    if (!checkoutBtn) return

    const hasValidProducts = Array.from(selectedProducts).some(
        (productId) => cartData[productId] && cartData[productId].inStock,
    )

    checkoutBtn.disabled = !hasValidProducts
}

// Format tiền tệ VNĐ
function formatCurrency(amount) {
    return new Intl.NumberFormat("vi-VN").format(Math.round(amount))
}

// Khởi tạo khi trang load
document.addEventListener("DOMContentLoaded", () => {
    console.log("=== CART.JS DOM LOADED ===")

    // Đảm bảo các hàm global có sẵn
    ensureUpdateCartCount()
    ensureShowNotification()

    // Kiểm tra các hàm global có sẵn
    console.log("Global updateCartCount available:", typeof window.updateCartCount === "function")
    console.log("Global showNotification available:", typeof window.showNotification === "function")

    // Test các element
    console.log("selectAll:", document.getElementById("selectAll"))
    console.log("minus buttons:", document.querySelectorAll(".minus-btn").length)
    console.log("plus buttons:", document.querySelectorAll(".plus-btn").length)
    console.log("remove buttons:", document.querySelectorAll(".remove-btn").length)
    console.log("product checkboxes:", document.querySelectorAll(".product-checkbox").length)

    // Gán event listeners
    const selectAllCheckbox = document.getElementById("selectAll")
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", selectAllProducts)
        console.log("Select all listener added")
    }

    // Quantity buttons
    document.querySelectorAll(".minus-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault()
            const productId = this.dataset.productId
            console.log("Minus button clicked for product:", productId)
            updateQuantity(productId, -1)
        })
    })

    document.querySelectorAll(".plus-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault()
            const productId = this.dataset.productId
            console.log("Plus button clicked for product:", productId)
            updateQuantity(productId, 1)
        })
    })

    // Remove buttons
    document.querySelectorAll(".remove-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault()
            const productId = this.dataset.productId
            console.log("Remove button clicked for product:", productId)
            removeItem(productId)
        })
    })

    // Product checkboxes
    document.querySelectorAll(".product-checkbox").forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            console.log("Checkbox changed for product:", this.dataset.productId)
            updateSelectedProducts()
        })
    })

    // Promo button
    const applyPromoBtn = document.getElementById("applyPromoBtn")
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener("click", applyPromo)
        console.log("Apply promo listener added")
    }

    // Promo code input - Enter key
    const promoCodeInput = document.getElementById("promoCode")
    if (promoCodeInput) {
        promoCodeInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault()
                applyPromo()
            }
        })
    }

    console.log("=== ALL EVENT LISTENERS ADDED ===")

    // Khởi tạo trạng thái
    updateSelectedProducts()

// Cập nhật cart count ban đầu
    // Khởi tạo cart count ban đầu (Chỉ khi có dữ liệu cartData)
    if (window.cartData && Object.keys(window.cartData).length > 0) {
        const totalCount = calculateTotalCartCount()
        console.log("Initial cart count (from cartData):", totalCount)
        window.updateCartCount(totalCount)
    }

    console.log("=== CART INITIALIZATION COMPLETED ===")
})
