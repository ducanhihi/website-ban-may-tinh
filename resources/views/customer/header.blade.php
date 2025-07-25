<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="{{ asset('css-customer/header.css') }}" rel="stylesheet">
<script src="{{ asset('js-customer/header.js') }}"></script>

<link href="{{ asset('css-customer/auth.css') }}" rel="stylesheet">
<script src="{{ asset('js-customer/auth.js') }}"></script>

<script>
    window.isUserLoggedIn = @json(auth()->check());
</script>

<div class="top-bar">
    <div class="top-bar-content">
        <div class="service-list">
            <div class="service-item">
                <i class="fas fa-tools"></i>
                <span>dịch vụ bảo hành...</span>
            </div>
            <div class="service-item">
                <i class="fas fa-tools"></i>
                <span>Bảo hành - sửa chữa</span>
            </div>
            <div class="service-item">
                <i class="fas fa-mobile-alt"></i>
                <span>Linh kiện</span>
            </div>
            <div class="service-item">
                <i class="fas fa-laptop"></i>
                <span>Laptop</span>
            </div>
            <div class="service-item">
                <i class="fas fa-desktop"></i>
                <span>PC</span>
            </div>
        </div>
    </div>
</div>

<!-- Logo and Search -->
<div class="header-main">
    <div class="header-content">
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <a href="{{ route('customer.main-home') }}">
                <img src="https://media.istockphoto.com/id/1136548762/vi/vec-to/t%E1%BB%A9c-gi%E1%BA%ADn-%C4%91en-ho%E1%BA%B7c-c%C3%A1-m%E1%BA%ADp-h%E1%BB%95-h%C3%ACnh-b%C3%B3ng-linh-v%E1%BA%ADt-nh%C3%A2n-v%E1%BA%ADt-vector-minh-h%E1%BB%8Da-c%C3%B4-l%E1%BA%ADp-tr%C3%AAn-n%E1%BB%81n-tr%E1%BA%AFng.jpg?s=612x612&w=0&k=20&c=VLMhZW3gJWqGA_Z-3p6aQeX_Bz1FJeNOPf-0RDtevZc=" alt="SharkWare Logo">
            </a>
        </div>
        <div class="search-bar">
            <form action="{{ route('customer.search') }}" method="GET" id="searchForm">
                <input type="text" class="search-input" name="query" placeholder="Nhập tên sản phẩm, từ khóa cần tìm" id="searchInput">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="search-results" id="searchResults" style="display: none;">
                <!-- Search results will be displayed here -->
            </div>
        </div>
        <div class="header-right">
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61558865521786" class="social-link facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-link youtube">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="social-link instagram">
                    <i class="fab fa-instagram"></i>
                </a>

            </div>
            <div class="service-links">
                <!-- Updated route to PC Builder -->
                <a href="{{ route('pc-builder.main-builder-pc') }}" class="service-link">
                    <i class="fas fa-tools"></i>
                    <span>Xây dựng cấu hình</span>
                </a>
                <a href="{{ route('customer.view-orders') }}" class="service-link">
                    <i class="fas fa-search"></i>
                    <span>Tra cứu đơn hàng</span>
                </a>
            </div>

            <a href="{{ route('customer.cart') }}" class="cart" id="cartBtn">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">{{ $cartCount ?? 0 }}</span>
                <div class="cart-text">Giỏ hàng</div>
            </a>

            <!-- Account Dropdown với Laravel Auth Logic -->
            <div class="account-dropdown">
                @guest
                    <button class="account-btn" id="accountDropdownBtn">
                        <i class="fas fa-user"></i>
                        <span>Tài khoản</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <!-- Guest Dropdown Menu -->
                    <div class="account-dropdown-menu" id="guestDropdownMenu">
                        <a href="#" class="dropdown-item" id="loginOption">
                            <i class="fas fa-sign-in-alt"></i>
                            Đăng nhập
                        </a>
                        <a href="#" class="dropdown-item" id="registerOption">
                            <i class="fas fa-user-plus"></i>
                            Đăng ký
                        </a>
                    </div>
                @endguest

                @auth
                    <button class="account-btn authenticated" id="accountDropdownBtn">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ Auth::user()->name ?? 'User' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <!-- Authenticated Dropdown Menu -->
                    <div class="account-dropdown-menu authenticated-menu" id="authDropdownMenu">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                <span class="user-email">{{ Auth::user()->email ?? 'user@example.com' }}</span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('customer.show') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            Thông tin cá nhân
                        </a>
                        <a href="{{ route('customer.view-orders') }}" class="dropdown-item">
                            <i class="fas fa-shopping-bag"></i>
                            Đơn hàng của tôi
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-heart"></i>
                            Sản phẩm yêu thích
                        </a>
                        <a href="{{ route('customer.show') }}" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            Cài đặt
                        </a>
                        <!-- Add PC Builder link in user menu -->
                        <a href="{{ route('pc-builder.my-builds') }}" class="dropdown-item">
                            <i class="fas fa-desktop"></i>
                            Cấu hình PC của tôi
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope"></i>
                            Tin nhắn
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Menu Navigation -->


<style>
    /* Search Results Styling */
    .search-bar {
        position: relative;
        flex: 1;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 400px;
        overflow-y: auto;
    }

    .search-result-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
    }

    .search-result-item:hover {
        background-color: #f9f9f9;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        margin-right: 15px;
        border-radius: 4px;
    }

    .search-result-info {
        flex: 1;
    }

    .search-result-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .search-result-price {
        color: #e53e3e;
        font-weight: 600;
    }

    .search-result-category {
        font-size: 0.8rem;
        color: #666;
    }

    .search-no-results {
        padding: 15px;
        text-align: center;
        color: #666;
    }

    .search-view-all {
        display: block;
        text-align: center;
        padding: 10px;
        background: #f3f4f6;
        color: #374151;
        font-weight: 600;
        text-decoration: none;
        border-radius: 0 0 8px 8px;
    }

    .search-view-all:hover {
        background: #e5e7eb;
    }
</style>

<!-- Script khởi tạo cart count từ server -->
<script>
    // Khởi tạo cart count từ server
    window.serverCartCount = {{ $cartCount ?? 0 }};

    console.log('Header: Server cart count loaded:', window.serverCartCount);

    // Đảm bảo hàm updateCartCount có sẵn
    if (typeof window.updateCartCount !== "function") {
        window.updateCartCount = function(count) {
            const cartCountElement = document.querySelector(".cart-count");
            if (cartCountElement) {
                cartCountElement.textContent = count;

                // Add animation
                cartCountElement.style.transform = "scale(1.3)";
                cartCountElement.style.backgroundColor = "#4caf50";

                setTimeout(() => {
                    cartCountElement.style.transform = "scale(1)";
                    cartCountElement.style.backgroundColor = "#1a4b8c";
                }, 300);

                // Lưu vào localStorage để các trang khác có thể sử dụng
                try {
                    localStorage.setItem("cartCount", count);
                    console.log("Cart count saved to localStorage:", count);
                } catch (e) {
                    console.error("Could not save cart count to localStorage:", e);
                }
            }
        };

        console.log("Header: updateCartCount function created");
    }

    // Khởi tạo cart count khi header load
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Header: DOM loaded, initializing cart count from server:", window.serverCartCount);

        // Cập nhật cart count từ server
        if (typeof window.updateCartCount === "function") {
            window.updateCartCount(window.serverCartCount);
        }

        // Live search functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput.addEventListener('focus', function() {
            if (searchInput.value.length >= 2) {
                searchResults.style.display = 'block';
            }
        });

        searchInput.addEventListener('blur', function() {
            // Delay hiding to allow clicking on results
            setTimeout(() => {
                searchResults.style.display = 'none';
            }, 200);
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            if (searchInput.value.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetchSearchResults(searchInput.value);
            }, 300);
        });

        function fetchSearchResults(query) {
            fetch(`/api/search?query=${encodeURIComponent(query)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        function displaySearchResults(data) {
            searchResults.style.display = 'block';

            if (data.length === 0) {
                searchResults.innerHTML = '<div class="search-no-results">Không tìm thấy sản phẩm nào</div>';
                return;
            }

            let html = '';

            data.forEach(product => {
                const price = product.final_price || product.price_out;
                const formattedPrice = new Intl.NumberFormat('vi-VN').format(price);
                const imageUrl = product.image_url || '/images/no-image.png';

                html += `
                    <a href="/customer/view-detail/${product.id}" class="search-result-item">
                        <img src="${imageUrl}" alt="${product.name}" class="search-result-image" onerror="this.src='/images/no-image.png'">
                        <div class="search-result-info">
                            <div class="search-result-name">${product.name}</div>
                            <div class="search-result-price">${formattedPrice} VNĐ</div>
                            <div class="search-result-category">${product.category ? product.category.name : ''}</div>
                        </div>
                    </a>
                `;
            });

            html += `<a href="/search?query=${encodeURIComponent(searchInput.value)}" class="search-view-all">Xem tất cả kết quả</a>`;

            searchResults.innerHTML = html;
        }

        console.log("Header: Cart count initialization completed");
    });
</script>
