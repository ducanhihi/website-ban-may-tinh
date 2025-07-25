// Main Content JavaScript - Updated with cart count sync

document.addEventListener("DOMContentLoaded", () => {
    // Remove preload class after page loads
    window.addEventListener("load", () => {
        document.body.classList.remove("preload")
    })

    // Category button functionality
    const categoryButtons = document.querySelectorAll(".category-btn")

    categoryButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault()
            console.log("Selected category:", this.textContent)
        })
    })

    // Product Slider functionality
    // Multiple Product Sliders functionality
    class ProductSlider {
        constructor(sliderElement, sliderId) {
            this.sliderElement = sliderElement
            this.sliderId = sliderId
            this.productContainer = sliderElement.querySelector(`#productContainer-${sliderId}`)
            this.prevBtn = sliderElement.querySelector(`.slider-prev[data-slider="${sliderId}"]`)
            this.nextBtn = sliderElement.querySelector(`.slider-next[data-slider="${sliderId}"]`)
            this.productCards = sliderElement.querySelectorAll(".product-card")

            this.currentIndex = 0
            this.cardsPerSlide = 5
            this.maxIndex = 0
            this.isAnimating = false

            this.init()
        }

        init() {
            this.updateCardsPerSlide()
            this.bindEvents()
            this.setupIntersectionObserver()
        }

        updateCardsPerSlide() {
            if (window.innerWidth > 1200) {
                this.cardsPerSlide = 5
            } else if (window.innerWidth > 992) {
                this.cardsPerSlide = 4
            } else if (window.innerWidth > 768) {
                this.cardsPerSlide = 3
            } else if (window.innerWidth > 576) {
                this.cardsPerSlide = 2
            } else {
                this.cardsPerSlide = 1
            }

            this.maxIndex = Math.max(0, this.productCards.length - this.cardsPerSlide)

            if (this.currentIndex > this.maxIndex) {
                this.currentIndex = this.maxIndex
            }

            this.updateSliderPosition()
        }

        updateSliderPosition() {
            if (this.isAnimating || !this.productContainer) return

            this.isAnimating = true
            this.productContainer.classList.add("loading")

            const cardWidth = this.productCards[0]?.offsetWidth || 240
            const gap = 15
            const translateX = this.currentIndex * (cardWidth + gap)
            this.productContainer.style.transform = `translateX(-${translateX}px)`
            setTimeout(() => {
                this.isAnimating = false
                this.productContainer.classList.remove("loading")
            }, 600)
        }

        bindEvents() {
            if (this.prevBtn) {
                this.prevBtn.addEventListener("click", () => {
                    if (this.currentIndex > 0 && !this.isAnimating) {
                        this.currentIndex--
                        this.updateSliderPosition()
                    }
                })
            }

            if (this.nextBtn) {
                this.nextBtn.addEventListener("click", () => {
                    if (this.currentIndex < this.maxIndex && !this.isAnimating) {
                        this.currentIndex++
                        this.updateSliderPosition()
                    }
                })
            }
        }

        setupIntersectionObserver() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px",
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = "1"
                        entry.target.style.transform = "translateY(0)"
                    }
                })
            }, observerOptions)

            this.productCards.forEach((card) => {
                card.style.opacity = "0"
                card.style.transform = "translateY(20px)"
                card.style.transition = "opacity 0.6s ease, transform 0.6s ease"
                observer.observe(card)
            })
        }

        handleResize() {
            this.updateCardsPerSlide()
        }
    }

// Initialize all product sliders
    const sliders = []
    const sliderElements = document.querySelectorAll(".product-showcase[data-slider]")

    sliderElements.forEach((sliderElement) => {
        const sliderId = sliderElement.getAttribute("data-slider")
        const slider = new ProductSlider(sliderElement, sliderId)
        sliders.push(slider)
    })

    document.addEventListener("keydown", (e) => {
        sliders.forEach((slider) => {
            if (e.key === "ArrowLeft" && slider.currentIndex > 0 && !slider.isAnimating) {
                slider.currentIndex--
                slider.updateSliderPosition()
            } else if (e.key === "ArrowRight" && slider.currentIndex < slider.maxIndex && !slider.isAnimating) {
                slider.currentIndex++
                slider.updateSliderPosition()
            }
        })
    })

    // Debounced resize handler
    let resizeTimeout
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimeout)
        resizeTimeout = setTimeout(updateCardsPerSlide, 250)
    })

    // Main Slider functionality
    const slides = document.querySelectorAll(".slide")
    const dots = document.querySelectorAll(".slider-dot")
    const mainPrevBtn = document.querySelector(".main-slider .slider-prev")
    const mainNextBtn = document.querySelector(".main-slider .slider-next")

    let slideCurrentIndex = 0
    const slideCount = slides.length
    let slideInterval

    function startSlideInterval() {
        slideInterval = setInterval(nextSlide, 5000)
    }

    function stopSlideInterval() {
        clearInterval(slideInterval)
    }

    function updateSlider() {
        slides.forEach((slide, index) => {
            slide.classList.toggle("active", index === slideCurrentIndex)
        })

        dots.forEach((dot, index) => {
            dot.classList.toggle("active", index === slideCurrentIndex)
        })
    }

    function nextSlide() {
        slideCurrentIndex = (slideCurrentIndex + 1) % slideCount
        updateSlider()
    }

    function prevSlide() {
        slideCurrentIndex = (slideCurrentIndex - 1 + slideCount) % slideCount
        updateSlider()
    }

    // Event listeners for main slider
    if (mainPrevBtn) {
        mainPrevBtn.addEventListener("click", () => {
            prevSlide()
            stopSlideInterval()
            startSlideInterval()
        })
    }

    if (mainNextBtn) {
        mainNextBtn.addEventListener("click", () => {
            nextSlide()
            stopSlideInterval()
            startSlideInterval()
        })
    }

    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            slideCurrentIndex = index
            updateSlider()
            stopSlideInterval()
            startSlideInterval()
        })
    })

    // Pause slider on hover
    const mainSlider = document.querySelector(".main-slider")
    if (mainSlider) {
        mainSlider.addEventListener("mouseenter", stopSlideInterval)
        mainSlider.addEventListener("mouseleave", startSlideInterval)
    }

    // Start the slider
    if (slides.length > 0) {
        startSlideInterval()
    }

    // Enhanced Update cart count function with localStorage sync
    function updateCartCount(count) {
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

            // Save to localStorage for other pages
            try {
                localStorage.setItem("cartCount", count)
                console.log("Main-home: Cart count saved to localStorage:", count)
            } catch (e) {
                console.error("Could not save cart count to localStorage:", e)
            }
        }
    }

    // Make updateCartCount globally available
    window.updateCartCount = updateCartCount

    // Initialize cart count - Ưu tiên dữ liệu từ server
    function initializeCartCount() {
        // 1. Sử dụng serverCartCount từ header nếu có
        if (typeof window.serverCartCount !== "undefined") {
            console.log("Main-home: Using server cart count:", window.serverCartCount)
            updateCartCount(window.serverCartCount)
            return
        }

        // 2. Fallback về localStorage
        try {
            const savedCount = localStorage.getItem("cartCount")
            if (savedCount !== null) {
                console.log("Main-home: Using cart count from localStorage:", savedCount)
                updateCartCount(Number.parseInt(savedCount, 10))
                return
            }
        } catch (e) {
            console.error("Could not read cart count from localStorage:", e)
        }

        // 3. Fallback cuối cùng về 0
        console.log("Main-home: No cart count found, defaulting to 0")
        updateCartCount(0)
    }

    // Add to cart functionality - Pure AJAX



    // Enhanced notification function
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

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault()
            const target = document.querySelector(this.getAttribute("href"))
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                })
            }
        })
    })

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1"
                entry.target.style.transform = "translateY(0)"
            }
        })
    }, observerOptions)

    // Observe product cards for scroll animations
    productCards.forEach((card) => {
        card.style.opacity = "0"
        card.style.transform = "translateY(20px)"
        card.style.transition = "opacity 0.6s ease, transform 0.6s ease"
        observer.observe(card)
    })

    // Initialize everything when page loads
    setTimeout(() => {
        initializeCartCount()
        console.log("Main-home initialization completed")
    }, 100) // Small delay to ensure header is loaded first
})
