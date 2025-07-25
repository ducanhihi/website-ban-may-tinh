// Auth System JavaScript - Enhanced for Laravel Integration
class AuthSystem {
    constructor() {
        this.init()
    }

    init() {
        this.bindEvents()
        this.waitForHeader()
        this.initializeFormSwitching()
        this.checkModalExists()
    }

    waitForHeader() {
        const checkHeader = () => {
            const accountBtn = document.getElementById("accountDropdownBtn")
            if (accountBtn) {
                console.log("Header loaded, binding events...")
                this.bindHeaderEvents()
            } else {
                console.log("Waiting for header...")
                setTimeout(checkHeader, 100)
            }
        }
        checkHeader()
    }

    bindEvents() {
        // Listen for header loaded event
        document.addEventListener("headerLoaded", () => {
            console.log("Header loaded event received")
            this.onHeaderReady()
        })
    }

    bindHeaderEvents() {
        // Bind events specifically for header elements
        const accountBtn = document.getElementById("accountDropdownBtn")
        const loginOption = document.getElementById("loginOption")
        const registerOption = document.getElementById("registerOption")

        if (accountBtn) {
            accountBtn.addEventListener("click", (e) => {
                e.preventDefault()
                console.log("Account button clicked")
                this.toggleAccountDropdown()
            })
        }

        // Only bind these if user is guest (elements exist)
        if (loginOption) {
            loginOption.addEventListener("click", (e) => {
                e.preventDefault()
                console.log("Login option clicked")
                this.closeAccountDropdown()
                this.showModal("login")
            })
        }

        if (registerOption) {
            registerOption.addEventListener("click", (e) => {
                e.preventDefault()
                console.log("Register option clicked")
                this.closeAccountDropdown()
                this.showModal("signup")
            })
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
            if (!e.target.closest(".account-dropdown")) {
                this.closeAccountDropdown()
            }
        })

        // ** NEW: Handle Cart Button Click **
        const cartBtn = document.getElementById("cartBtn")
        if (cartBtn) {
            cartBtn.addEventListener("click", (e) => {
                if (!this.isLoggedIn()) {
                    e.preventDefault() // Ngăn chuyển trang
                    this.showModal("login") // Hiện modal đăng nhập
                }
                // Nếu đã đăng nhập, link hoạt động bình thường
            })
        }
    }

    initializeFormSwitching() {
        // Wait for modal to be loaded
        setTimeout(() => {
            this.bindModalEvents()
        }, 500)
    }

    bindModalEvents() {
        // Close modal events
        const closeBtn = document.getElementById("authModalClose")
        const overlay = document.getElementById("authModalOverlay")

        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                console.log("Close button clicked")
                this.hideModal()
            })
        }

        if (overlay) {
            overlay.addEventListener("click", () => {
                console.log("Overlay clicked")
                this.hideModal()
            })
        }

        // ESC key to close modal
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                const modal = document.getElementById("authModal")
                if (modal && modal.classList.contains("show")) {
                    this.hideModal()
                }
            }
        })

        // Form switching links
        document.querySelectorAll(".js-animation-link").forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault()
                const target = link.getAttribute("data-target")
                if (target) {
                    this.switchForm(target.replace("#", ""))
                }
            })
        })

        // Password toggle buttons
        document.querySelectorAll(".password-toggle").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault()
                this.togglePassword(btn.dataset.target)
            })
        })

        // Password strength checker
        const passwordInput = document.getElementById("signupPassword")
        if (passwordInput) {
            passwordInput.addEventListener("input", (e) => {
                this.checkPasswordStrength(e.target.value)
            })
        }

        // Social login buttons
        document.querySelectorAll(".social-btn").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault()
                const provider = btn.classList.contains("facebook-btn") ? "facebook" : "google"
                this.handleSocialLogin(provider)
            })
        })
    }

    // Account Dropdown Methods
    toggleAccountDropdown() {
        const btn = document.getElementById("accountDropdownBtn")
        const guestMenu = document.getElementById("guestDropdownMenu")
        const authMenu = document.getElementById("authDropdownMenu")

        btn.classList.toggle("active")

        if (guestMenu) {
            guestMenu.classList.toggle("show")
        }
        if (authMenu) {
            authMenu.classList.toggle("show")
        }
    }

    closeAccountDropdown() {
        const btn = document.getElementById("accountDropdownBtn")
        const guestMenu = document.getElementById("guestDropdownMenu")
        const authMenu = document.getElementById("authDropdownMenu")

        if (btn) btn.classList.remove("active")
        if (guestMenu) guestMenu.classList.remove("show")
        if (authMenu) authMenu.classList.remove("show")
    }

    // Modal Methods
    showModal(formType = "login") {
        const modal = document.getElementById("authModal")
        if (!modal) {
            console.error("Auth modal not found")
            return
        }

        modal.classList.add("show")
        document.body.style.overflow = "hidden"
        this.switchForm(formType)
    }

    hideModal() {
        console.log("Hiding modal")
        const modal = document.getElementById("authModal")
        if (modal) {
            modal.classList.remove("show")
            document.body.style.overflow = ""
            this.clearForms()
        }
    }

    switchForm(formType) {
        // Hide all forms
        document.querySelectorAll('[data-target-group="idForm"]').forEach((form) => {
            form.style.display = "none"
            form.style.opacity = "0"
        })

        // Show selected form
        const targetForm = document.getElementById(formType)
        if (targetForm) {
            targetForm.style.display = "block"
            setTimeout(() => {
                targetForm.style.opacity = "1"
            }, 50)
        }
    }

    // Form Helper Methods
    togglePassword(targetId) {
        const input = document.getElementById(targetId)
        const btn = document.querySelector(`[data-target="${targetId}"]`)
        const icon = btn.querySelector("i")

        if (input.type === "password") {
            input.type = "text"
            icon.className = "fas fa-eye-slash"
        } else {
            input.type = "password"
            icon.className = "fas fa-eye"
        }
    }

    checkPasswordStrength(password) {
        const strengthIndicator = document.getElementById("passwordStrength")
        if (!strengthIndicator) return

        let strength = 0
        let message = ""

        if (password.length >= 8) strength++
        if (/[a-z]/.test(password)) strength++
        if (/[A-Z]/.test(password)) strength++
        if (/[0-9]/.test(password)) strength++
        if (/[^A-Za-z0-9]/.test(password)) strength++

        switch (strength) {
            case 0:
            case 1:
            case 2:
                strengthIndicator.className = "password-strength weak"
                message = "Mật khẩu yếu"
                break
            case 3:
            case 4:
                strengthIndicator.className = "password-strength medium"
                message = "Mật khẩu trung bình"
                break
            case 5:
                strengthIndicator.className = "password-strength strong"
                message = "Mật khẩu mạnh"
                break
        }

        strengthIndicator.textContent = password.length > 0 ? message : ""
    }

    handleSocialLogin(provider) {
        // Redirect to social login route
        window.location.href = `/auth/${provider}`
    }

    clearForms() {
        document.querySelectorAll(".auth-form form").forEach((form) => {
            form.reset()
        })

        // Clear password strength indicator
        const strengthIndicator = document.getElementById("passwordStrength")
        if (strengthIndicator) {
            strengthIndicator.textContent = ""
        }
    }

    onHeaderReady() {
        console.log("Header is ready, binding auth events...")
        this.bindHeaderEvents()
    }

    showToast(message, type = "success") {
        // Remove existing toasts
        document.querySelectorAll(".toast-notification").forEach((toast) => toast.remove())

        // Create new toast
        const toast = document.createElement("div")
        toast.className = `toast-notification ${type}`
        toast.textContent = message

        document.body.appendChild(toast)

        // Show toast
        setTimeout(() => {
            toast.classList.add("show")
        }, 100)

        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove("show")
            setTimeout(() => {
                toast.remove()
            }, 300)
        }, 3000)
    }

    checkModalExists() {
        setTimeout(() => {
            const modal = document.getElementById("authModal")
            const closeBtn = document.getElementById("authModalClose")
            console.log("Modal exists:", !!modal)
            console.log("Close button exists:", !!closeBtn)

            if (modal && closeBtn) {
                console.log("Modal and close button found, binding close event")
                closeBtn.addEventListener("click", () => {
                    console.log("Close button clicked directly")
                    modal.classList.remove("show")
                    document.body.style.overflow = ""
                })
            } else {
                console.log("Modal or close button not found, retrying...")
                this.checkModalExists()
            }
        }, 1000)
    }

    // Kiểm tra user đăng nhập hay chưa (dựa trên biến global window.isUserLoggedIn)
    isLoggedIn() {
        return window.isUserLoggedIn === true
    }
}

// Initialize auth system when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    window.authSystem = new AuthSystem()
})
