document.addEventListener("DOMContentLoaded", () => {
    // Load header component
    fetch("components/header.html")
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("header-container").innerHTML = data
            initializeHeader()
        })
        .catch((error) => console.error("Error loading header:", error))

    function initializeHeader() {
        // Mobile menu functionality
        const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
        const serviceLinks = document.querySelector(".service-links")

        if (mobileMenuBtn && serviceLinks) {
            mobileMenuBtn.addEventListener("click", () => {
                if (serviceLinks.style.display === "flex") {
                    serviceLinks.style.display = "none"
                } else {
                    serviceLinks.style.display = "flex"
                    serviceLinks.style.position = "absolute"
                    serviceLinks.style.top = "60px"
                    serviceLinks.style.right = "15px"
                    serviceLinks.style.backgroundColor = "white"
                    serviceLinks.style.padding = "15px"
                    serviceLinks.style.borderRadius = "8px"
                    serviceLinks.style.boxShadow = "0 4px 20px rgba(0,0,0,0.15)"
                    serviceLinks.style.flexDirection = "column"
                    serviceLinks.style.zIndex = "1001"
                    serviceLinks.style.minWidth = "200px"
                }
            })

            // Close mobile menu when clicking outside
            document.addEventListener("click", (e) => {
                if (!mobileMenuBtn.contains(e.target) && !serviceLinks.contains(e.target)) {
                    serviceLinks.style.display = "none"
                }
            })
        }

        // Search functionality
        const searchInput = document.querySelector(".search-input")
        const searchBtn = document.querySelector(".search-btn")

        function performSearch() {
            const query = searchInput.value.trim()
            if (query) {
                console.log("Searching for:", query)
                alert(`Tìm kiếm: "${query}"`)
            }
        }

        if (searchBtn) {
            searchBtn.addEventListener("click", performSearch)
        }

        if (searchInput) {
            searchInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    performSearch()
                }
            })
        }

        // Cart functionality
        const cartCount = document.querySelector(".cart-count")
        if (cartCount) {
            window.updateCartCount = (count) => {
                cartCount.textContent = count
            }
        }

        const cartBtn = document.getElementById('cart-btn');
        if (cartBtn) {
            cartBtn.addEventListener('click', () => {
                fetch('/check-auth')
                    .then(response => response.json())
                    .then(data => {
                        if (data.authenticated) {
                            // Nếu đã đăng nhập thì chuyển hướng đến giỏ hàng
                            window.location.href = '/customer/cart';
                        } else {
                            // Nếu chưa đăng nhập, hiển thị modal login
                            showLoginModal();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }

        function showLoginModal() {
            // Viết logic hiện modal login của bạn ở đây
            // Ví dụ: document.getElementById('login-modal').style.display = 'block';
            alert('Bạn chưa đăng nhập. Vui lòng đăng nhập để tiếp tục.');
        }

    }
})


