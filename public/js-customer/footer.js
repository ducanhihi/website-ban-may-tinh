// Footer JavaScript
document.addEventListener("DOMContentLoaded", () => {
    // Load footer component
    fetch("components/footer.html")
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("footer-container").innerHTML = data
            initializeFooter()
        })
        .catch((error) => console.error("Error loading footer:", error))

    function initializeFooter() {
        // Social media links functionality
        const socialLinks = document.querySelectorAll(".social-btn")

        socialLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault()
                const platform = this.classList[1] // Get the platform class (facebook, youtube, etc.)
                console.log(`Opening ${platform} page`)

                // Here you would typically open the actual social media links
                // For demo purposes, just show an alert
                alert(`Chuyển đến trang ${platform.toUpperCase()}`)
            })
        })

        // Footer links functionality
        const footerLinks = document.querySelectorAll(".footer-links a")

        footerLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault()
                const linkText = this.textContent
                console.log(`Clicked footer link: ${linkText}`)

                // Here you would typically navigate to the actual pages
                // For demo purposes, just show an alert
                alert(`Chuyển đến trang: ${linkText}`)
            })
        })

        // Member website link
        const websiteLink = document.querySelector(".website-link")
        if (websiteLink) {
            websiteLink.addEventListener("click", (e) => {
                e.preventDefault()
                console.log("Opening member website")
                alert("Chuyển đến website thành viên: dichvutoi.vn")
            })
        }

        // Contact phone numbers - make them clickable
        const contactItems = document.querySelectorAll(".contact-item")
        contactItems.forEach((item) => {
            const phoneMatch = item.textContent.match(/(\d{8,})/g)
            if (phoneMatch) {
                item.style.cursor = "pointer"
                item.addEventListener("click", () => {
                    const phone = phoneMatch[0]
                    if (confirm(`Gọi đến số ${phone}?`)) {
                        window.location.href = `tel:${phone}`
                    }
                })
            }
        })

        // Animate service highlights on scroll
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

        // Observe service highlights for scroll animations
        const serviceHighlights = document.querySelectorAll(".service-highlight")
        serviceHighlights.forEach((highlight, index) => {
            highlight.style.opacity = "0"
            highlight.style.transform = "translateY(30px)"
            highlight.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`
            observer.observe(highlight)
        })

        // Payment method icons hover effect
        const paymentIcons = document.querySelectorAll(".payment-icons img")
        paymentIcons.forEach((icon) => {
            icon.addEventListener("mouseenter", function () {
                this.style.transform = "scale(1.1)"
            })

            icon.addEventListener("mouseleave", function () {
                this.style.transform = "scale(1)"
            })
        })

        // Certification badges hover effect
        const certificationBadges = document.querySelectorAll(".certifications img")
        certificationBadges.forEach((badge) => {
            badge.addEventListener("mouseenter", function () {
                this.style.transform = "scale(1.05)"
            })

            badge.addEventListener("mouseleave", function () {
                this.style.transform = "scale(1)"
            })
        })

        console.log("Footer initialized successfully")
    }
})
