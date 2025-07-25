@extends('layout.customerApp')

@section('content')

    @if(session('login_required'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.authSystem?.showModal('login')
            })
        </script>
    @endif
    <main id="main-content">
        <!-- Advertising Section -->
        <section class="ad-container">
            <div class="ad-grid">
                <!-- Main Slider -->
                <div class="main-slider">
                    <div class="slider-container">
                        <div class="slide active">
                            <img src="https://hanoicomputercdn.com/media/banner/06_Mayeb9b8b0ab1dcb53564ac9967a57fc00c.jpg" alt="ASUS Zenbook A14">
                        </div>
                        <div class="slide">
                            <img src="https://hanoicomputercdn.com/media/banner/13_May9b54ceeca0cd2021b6bb093b51f7ab63.jpg" alt="Special Deals">
                        </div>
                        <div class="slide">
                            <img src="https://hanoicomputercdn.com/media/banner/16_May7593fe402f40334c9764b7a1bf254d1a.jpg" alt="New Arrivals">
                        </div>
                    </div>
                    <div class="slider-arrow slider-prev">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="slider-arrow slider-next">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div class="slider-dots">
                        <div class="slider-dot active"></div>
                        <div class="slider-dot"></div>
                        <div class="slider-dot"></div>
                    </div>
                </div>

                <!-- Right Top Banner -->
                <div class="ad-banner ad-right-top">
                    <img src="https://hanoicomputercdn.com/media/banner/16_May98f8b00aeace4ca57d90782bb4226f86.png" alt="ROG Strix PC">
                </div>

                <!-- Right Bottom Banner -->
                <div class="ad-banner ad-right-bottom">
                    <img src="https://hanoicomputercdn.com/media/banner/16_May10fb15c77258a991b0028080a64fb42d.png" alt="PC Build Discount">
                </div>

                <!-- Bottom Left Banner -->
                <div class="ad-banner ad-bottom-left">
                    <img src="https://hanoicomputercdn.com/media/banner/06_Aug73bb545ad08cb464182dfc035dcb582f.png" alt="Laptop Discount">
                </div>

                <!-- Bottom Middle Banner -->
                <div class="ad-banner ad-bottom-middle">
                    <img src="https://hanoicomputercdn.com/media/banner/16_Mayfb5c81ed3a220004b71069645f112867.png" alt="Laptop Sale">
                </div>
            </div>
        </section>



        <!-- Category Navigation Section -->
        <section class="category-nav">
            <div class="category-header">
                <h2 class="category-title">LAPTOP - CHƠI GAME, HỌC TẬP</h2>
                <div class="category-buttons">
                    <a href="#" class="category-btn">LAPTOP ACER</a>
                    <a href="#" class="category-btn">LAPTOP ASUS</a>
                    <a href="#" class="category-btn">LAPTOP MSI</a>
                    <a href="#" class="category-btn">LAPTOPGIGABYTE</a>
                </div>
            </div>
        </section>

        <!-- Product Showcase Section theo the loai l -->
        <section class="product-showcase" data-slider="laptop">
            <div class="product-slider">
                <div class="slider-viewport">
                    <div class="product-container" id="productContainer-laptop">
                        <!-- Product 1 -->
                        @foreach($allProducts->where('category_id', 16)->chunk(4) as $chunk)
                            @foreach($chunk as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="{{ route('customer.view-detail', $product->id) }}">
                                            <img src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="rating-code">
                                            <div class="rating">
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <span class="review-count">(0)</span>
                                            </div>
                                            <div class="product-code">MÃ: {{$product->product_code}}</div>
                                        </div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="price-section">
                                            @if($product->has_discount)
                                                <div class="old-price-discount">
                                                        <span class="old-price" style="text-decoration: line-through;">
                                                            {{ number_format($product->price_out, 0, ',', '.') }} VNĐ
                                                        </span>
                                                    @if($product->discount_percent)
                                                        <span class="discount-info">-{{ $product->discount_percent }}%</span>
                                                        <br>

                                                    @endif
                                                </div>
                                            @endif

                                            <div class="current-price">
                                                {{ number_format($product->final_price, 0, ',', '.') }} VNĐ
                                            </div>
                                        </div>

                                        <div class="bottom-section">
                                            @if(($product->quantity ?? 0) > 0)
                                                <div class="stock-status in-stock">
                                                    <i class="fas fa-check"></i>
                                                    Sẵn hàng
                                                </div>
                                                <form action="{{ route('cart.add', $product->id ?? 1) }}" method="POST" class="add-to-cart-form">
                                                    @csrf
                                                    <input type="hidden" name="type" value="ADD_TO_CART">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="add-to-cart" data-product-id="{{ $product->id ?? 1 }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="stock-status out-of-stock">
                                                    <i class="fas fa-times"></i>
                                                    Hết hàng
                                                </div>
                                                <button class="add-to-cart" disabled>
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="slider-arrow slider-prev" data-slider="laptop">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="slider-arrow slider-next" data-slider="laptop">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </section>




        <section class="category-nav">
            <div class="category-header">
                <h2 class="category-title">CPU</h2>
                <div class="category-buttons">
                    <a href="javascript:void(0)" class="category-btn">CPU Intel</a>
                    <a href="javascript:void(0)" class="category-btn">CORE I3</a>
                    <a href="javascript:void(0)" class="category-btn">CORE I5</a>
                </div>

            </div>
        </section>

        <!-- Product Showcase Section theo the loai l -->
        <section class="product-showcase" data-slider="cpu">
            <div class="product-slider">
                <div class="slider-viewport">
                    <div class="product-container" id="productContainer-cpu">
                        <!-- Product 1 -->
                        @foreach($allProducts->where('category_id', 1)->chunk(4) as $chunk)
                            @foreach($chunk as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="{{ route('customer.view-detail', $product->id) }}">
                                            <img src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="rating-code">
                                            <div class="rating">
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <span class="review-count">(0)</span>
                                            </div>
                                            <div class="product-code">MÃ: {{$product->product_code}}</div>
                                        </div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="price-section">
                                            @if($product->has_discount)
                                                <div class="old-price-discount">
                                                        <span class="old-price" style="text-decoration: line-through;">
                                                            {{ number_format($product->price_out, 0, ',', '.') }} VNĐ
                                                        </span>
                                                    @if($product->discount_percent)
                                                        <span class="discount-info">-{{ $product->discount_percent }}%</span>
                                                        <br>

                                                    @endif
                                                </div>
                                            @endif

                                            <div class="current-price">
                                                {{ number_format($product->final_price, 0, ',', '.') }} VNĐ
                                            </div>
                                        </div>

                                        <div class="bottom-section">
                                            @if(($product->quantity ?? 0) > 0)
                                                <div class="stock-status in-stock">
                                                    <i class="fas fa-check"></i>
                                                    Sẵn hàng
                                                </div>
                                                <form action="{{ route('cart.add', $product->id ?? 1) }}" method="POST" class="add-to-cart-form">
                                                    @csrf
                                                    <input type="hidden" name="type" value="ADD_TO_CART">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="add-to-cart" data-product-id="{{ $product->id ?? 1 }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="stock-status out-of-stock">
                                                    <i class="fas fa-times"></i>
                                                    Hết hàng
                                                </div>
                                                <button class="add-to-cart" disabled>
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="slider-arrow slider-prev" data-slider="cpu">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="slider-arrow slider-next" data-slider="cpu">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </section>


        <section class="category-nav">
            <div class="category-header">
                <h2 class="category-title">RAM</h2>
                <div class="category-buttons">
                    <a href="javascript:void(0)" class="category-btn">RAM</a>
                    <a href="javascript:void(0)" class="category-btn">8GB</a>
                    <a href="javascript:void(0)" class="category-btn">16GB</a>
                </div>

            </div>
        </section>

        <!-- Product Showcase Section theo the loai l -->
        <section class="product-showcase" data-slider="ram">
            <div class="product-slider">
                <div class="slider-viewport">
                    <div class="product-container" id="productContainer-ram">
                        <!-- Product 1 -->
                        @foreach($allProducts->where('category_id', 3)->chunk(4) as $chunk)
                            @foreach($chunk as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="{{ route('customer.view-detail', $product->id) }}">
                                            <img src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="rating-code">
                                            <div class="rating">
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <span class="review-count">(0)</span>
                                            </div>
                                            <div class="product-code">MÃ: {{$product->product_code}}</div>
                                        </div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="price-section">
                                            @if($product->has_discount)
                                                <div class="old-price-discount">
                                                        <span class="old-price" style="text-decoration: line-through;">
                                                            {{ number_format($product->price_out, 0, ',', '.') }} VNĐ
                                                        </span>
                                                    @if($product->discount_percent)
                                                        <span class="discount-info">-{{ $product->discount_percent }}%</span>

                                                    @endif

                                                </div>
                                            @endif

                                            <div class="current-price">
                                                {{ number_format($product->final_price, 0, ',', '.') }} VNĐ
                                            </div>
                                        </div>

                                        <div class="bottom-section">
                                            @if(($product->quantity ?? 0) > 0)
                                                <div class="stock-status in-stock">
                                                    <i class="fas fa-check"></i>
                                                    Sẵn hàng
                                                </div>
                                                <form action="{{ route('cart.add', $product->id ?? 1) }}" method="POST" class="add-to-cart-form">
                                                    @csrf
                                                    <input type="hidden" name="type" value="ADD_TO_CART">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="add-to-cart" data-product-id="{{ $product->id ?? 1 }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="stock-status out-of-stock">
                                                    <i class="fas fa-times"></i>
                                                    Hết hàng
                                                </div>
                                                <button class="add-to-cart" disabled>
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="slider-arrow slider-prev" data-slider="ram">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="slider-arrow slider-next" data-slider="ram">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </section>




        <section class="category-nav">
            <div class="category-header">
                <h2 class="category-title">Main</h2>
                <div class="category-buttons">
                    <a href="javascript:void(0)" class="category-btn">Mainboard ASUS</a>
                    <a href="javascript:void(0)" class="category-btn">Mainboard ACER</a>
                </div>

            </div>
        </section>

        <!-- Product Showcase Section theo the loai l -->
        <section class="product-showcase" data-slider="motherboard">
            <div class="product-slider">
                <div class="slider-viewport">
                    <div class="product-container" id="productContainer-motherboard">
                        <!-- Product 1 -->
                        @foreach($allProducts->where('category_id', 4)->chunk(4) as $chunk)
                            @foreach($chunk as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="{{ route('customer.view-detail', $product->id) }}">
                                            <img src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="rating-code">
                                            <div class="rating">
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <span class="review-count">(0)</span>
                                            </div>
                                            <div class="product-code">MÃ: {{$product->product_code}}</div>
                                        </div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="price-section">
                                            @if($product->has_discount)
                                                <div class="old-price-discount">
                                                        <span class="old-price" style="text-decoration: line-through;">
                                                            {{ number_format($product->price_out, 0, ',', '.') }} VNĐ
                                                        </span>
                                                    @if($product->discount_percent)
                                                        <span class="discount-info">-{{ $product->discount_percent }}%</span>
                                                    @endif
                                                </div>
                                                <br>

                                            @endif

                                            <div class="current-price">
                                                {{ number_format($product->final_price, 0, ',', '.') }} VNĐ
                                            </div>
                                        </div>

                                        <div class="bottom-section">
                                            @if(($product->quantity ?? 0) > 0)
                                                <div class="stock-status in-stock">
                                                    <i class="fas fa-check"></i>
                                                    Sẵn hàng
                                                </div>
                                                <form action="{{ route('cart.add', $product->id ?? 1) }}" method="POST" class="add-to-cart-form">
                                                    @csrf
                                                    <input type="hidden" name="type" value="ADD_TO_CART">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="add-to-cart" data-product-id="{{ $product->id ?? 1 }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="stock-status out-of-stock">
                                                    <i class="fas fa-times"></i>
                                                    Hết hàng
                                                </div>
                                                <button class="add-to-cart" disabled>
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="slider-arrow slider-prev" data-slider="motherboard">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="slider-arrow slider-next" data-slider="motherboard">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </section>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const buttons = document.querySelectorAll('.category-btn');
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');

                buttons.forEach(button => {
                    button.addEventListener('click', function () {
                        const keyword = this.textContent.trim();
                        if (searchInput && searchForm) {
                            searchInput.value = keyword;  // Gán từ khóa vào input
                            searchForm.submit();          // Submit form
                        }
                    });
                });
            });
        </script>





    </main>
@endsection
