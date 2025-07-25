<!-- Auth Modal -->
<div class="auth-modal" id="authModal">
    <div class="auth-modal-overlay" id="authModalOverlay"></div>
    <div class="auth-modal-content">
        <button class="auth-modal-close" id="authModalClose">
            <i class="fas fa-times"></i>
        </button>

        <!-- Login Form -->
        <div class="auth-form login-form" id="login" data-target-group="idForm">
            <div class="auth-header">
                <h2>Chào mừng trở lại</h2>
                <p>Đăng nhập để tiếp tục mua sắm</p>
            </div>

            <form class="js-validate" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="loginEmail">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                            </div>
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   id="loginEmail"
                                   placeholder="Email hoặc số điện thoại"
                                   required
                                   data-msg="Vui lòng nhập email hợp lệ."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                        </div>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="loginPassword">Mật khẩu</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                            </div>
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   id="loginPassword"
                                   placeholder="Mật khẩu"
                                   required
                                   data-msg="Mật khẩu không hợp lệ."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text password-toggle" data-target="loginPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" class="forgot-password js-animation-link"
                       data-target="#forgotPassword"
                       data-link-group="idForm"
                       data-animation-in="slideInUp">Quên mật khẩu?</a>
                </div>

                <div class="mb-2">
                    <button type="submit" class="auth-btn primary">Đăng nhập</button>
                </div>

                <div class="text-center mb-4">
                    <span class="small text-muted">Chưa có tài khoản?</span>
                    <a class="js-animation-link small text-primary" href="#"
                       data-target="#signup"
                       data-link-group="idForm"
                       data-animation-in="slideInUp">Đăng ký ngay</a>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div class="auth-form register-form" id="signup" style="display: none; opacity: 0;" data-target-group="idForm">
            <div class="auth-header">
                <h2>Chào mừng đến với SharkWare</h2>
                <p>Điền thông tin để bắt đầu</p>
            </div>

            <form class="js-validate" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="signupName">Họ và tên</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   id="signupName"
                                   placeholder="Họ và tên"
                                   required
                                   data-msg="Vui lòng nhập họ và tên."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                        </div>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="signupEmail">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                            </div>
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   id="signupEmail"
                                   placeholder="Email"
                                   required
                                   data-msg="Vui lòng nhập email hợp lệ."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                        </div>
                    </div>
                </div>

                <!-- Phone Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="signupPhone">Số điện thoại</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                            </div>
                            <input type="tel"
                                   class="form-control"
                                   name="phone"
                                   id="signupPhone"
                                   placeholder="Số điện thoại"
                                   required
                                   data-msg="Vui lòng nhập số điện thoại."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                        </div>
                    </div>
                </div>

                <!-- Address Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="signupAddress">Địa chỉ</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   name="address"
                                   id="signupAddress"
                                   placeholder="Địa chỉ"
                                   required
                                   data-msg="Vui lòng nhập địa chỉ."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                        </div>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="signupPassword">Mật khẩu</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                            </div>
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   id="signupPassword"
                                   placeholder="Mật khẩu"
                                   required
                                   data-msg="Mật khẩu không hợp lệ."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text password-toggle" data-target="signupPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>

                <!-- Confirm Password Input -->
                <div class="form-group">
                    <div class="js-form-message js-focus-state">
                        <label class="sr-only" for="signupConfirmPassword">Xác nhận mật khẩu</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                            </div>
                            <input type="password"
                                   class="form-control"
                                   name="confirmPassword"
                                   id="signupConfirmPassword"
                                   placeholder="Xác nhận mật khẩu"
                                   required
                                   data-msg="Mật khẩu xác nhận không khớp."
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text password-toggle" data-target="signupConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <button type="submit" class="auth-btn primary">Bắt đầu</button>
                </div>

                <div class="text-center mb-4">
                    <span class="small text-muted">Đã có tài khoản?</span>
                    <a class="js-animation-link small text-primary" href="#"
                       data-target="#login"
                       data-link-group="idForm"
                       data-animation-in="slideInUp">Đăng nhập</a>
                </div>


            </form>
        </div>

        <!-- Forgot Password Form -->
{{--        <div class="auth-form forgot-form" id="forgotPassword" style="display: none; opacity: 0;" data-target-group="idForm">--}}
{{--            <div class="auth-header">--}}
{{--                <h2>Khôi phục mật khẩu</h2>--}}
{{--                <p>Nhập email và chúng tôi sẽ gửi hướng dẫn đến bạn</p>--}}
{{--            </div>--}}

{{--            <form class="js-validate" method="POST" action="{{ route('password.email') }}">--}}
{{--                @csrf--}}

{{--                <!-- Email Input -->--}}
{{--                <div class="form-group">--}}
{{--                    <div class="js-form-message js-focus-state">--}}
{{--                        <label class="sr-only" for="recoverEmail">Email của bạn</label>--}}
{{--                        <div class="input-group">--}}
{{--                            <div class="input-group-prepend">--}}
{{--                        <span class="input-group-text">--}}
{{--                            <i class="fas fa-envelope"></i>--}}
{{--                        </span>--}}
{{--                            </div>--}}
{{--                            <input type="email"--}}
{{--                                   class="form-control"--}}
{{--                                   name="email"--}}
{{--                                   id="recoverEmail"--}}
{{--                                   placeholder="Email của bạn"--}}
{{--                                   required--}}
{{--                                   data-msg="Vui lòng nhập email hợp lệ."--}}
{{--                                   data-error-class="u-has-error"--}}
{{--                                   data-success-class="u-has-success">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="mb-2">--}}
{{--                    <button type="submit" class="auth-btn primary">Khôi phục mật khẩu</button>--}}
{{--                </div>--}}

{{--                <div class="text-center mb-4">--}}
{{--                    <span class="small text-muted">Nhớ mật khẩu?</span>--}}
{{--                    <a class="js-animation-link small text-primary" href="#"--}}
{{--                       data-target="#login"--}}
{{--                       data-link-group="idForm"--}}
{{--                       data-animation-in="slideInUp">Đăng nhập</a>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
    </div>
</div>
