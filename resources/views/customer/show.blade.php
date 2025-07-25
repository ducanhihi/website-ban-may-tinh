@extends('layout.customerApp')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .profile-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .form-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
            z-index: 1;
        }

        .section-title {
            color: #1f2937;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #475569;
            box-shadow: 0 0 0 0.2rem rgba(71, 85, 105, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #475569, #334155);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #334155, #1e293b);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(71, 85, 105, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid #6b7280;
            color: #6b7280;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6b7280;
            color: white;
            transform: translateY(-2px);
        }

        /* FIX: Sửa CSS cho password section */
        .password-section {
            background: white;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            /* Loại bỏ position absolute hoặc fixed nếu có */
        }

        .password-toggle {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border: none;
            padding: 20px 30px;
            width: 100%;
            text-align: left;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
            z-index: 2;
        }

        .password-toggle:hover {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }

        .password-form {
            padding: 30px;
            background: white;
            position: relative;
            z-index: 1;
        }

        /* FIX: Đảm bảo collapse hoạt động đúng */
        .collapse {
            position: relative;
            z-index: 1;
        }

        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(45deg, #059669, #047857);
            color: white;
        }

        .alert-error {
            background: linear-gradient(45deg, #dc2626, #b91c1c);
            color: white;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
            z-index: 1;
        }

        .info-item {
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        }

        .info-item:hover {
            background: #f9fafb;
            transform: translateX(5px);
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #6b7280;
            font-size: 16px;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .role-admin {
            background: linear-gradient(45deg, #dc2626, #b91c1c);
            color: white;
        }

        .role-customer {
            background: linear-gradient(45deg, #059669, #047857);
            color: white;
        }

        /* FIX: Đảm bảo layout responsive */
        .main-content {
            position: relative;
            z-index: 1;
        }

        .left-column {
            position: relative;
            z-index: 1;
        }

        .right-column {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .profile-header {
                padding: 20px;
            }

            .avatar {
                width: 100px;
                height: 100px;
            }

            .form-section,
            .password-section,
            .info-card {
                margin-bottom: 20px;
            }
        }

        .password-strength .progress-bar {
            transition: all 0.3s ease;
        }

        .password-strength .progress-bar.weak {
            background-color: #dc3545;
        }

        .password-strength .progress-bar.medium {
            background-color: #ffc107;
        }

        .password-strength .progress-bar.strong {
            background-color: #28a745;
        }

        .input-group .btn-outline-secondary {
            border-color: #e5e7eb;
        }

        .input-group .btn-outline-secondary:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-valid {
            border-color: #28a745;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .valid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #28a745;
        }
    </style>

    <div class="profile-container">
        <div class="container">
            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Profile Header -->
            <div class="profile-card mb-4">
                <div class="profile-header">
                    <div class="avatar-container">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=475569&color=ffffff&size=120"
                             alt="Avatar" class="avatar">
                    </div>
                    <h2 class="mb-2">{{ $user->name }}</h2>
                    <p class="mb-2 opacity-75">{{ $user->email }}</p>
                    <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
                    <br>
                    <small class="opacity-50 mt-2 d-block">Thành viên từ {{ $user->created_at->format('d/m/Y') }}</small>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row main-content">
                <div class="col-lg-8 left-column">
                    <!-- Profile Information Form -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user text-primary"></i>
                            Thông Tin Cá Nhân
                        </h3>

                        <form id="profileForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-muted"></i>Họ và Tên
                                    </label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2 text-muted"></i>Email
                                    </label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-2 text-muted"></i>Số Điện Thoại
                                    </label>
                                    <input type="text" class="form-control" name="phone" id="phone" value="{{ $user->phone }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="DOB" class="form-label fw-bold">
                                        <i class="fas fa-birthday-cake me-2 text-muted"></i>Ngày Sinh
                                    </label>
                                    <input type="date" class="form-control" name="DOB" id="DOB" value="{{ $user->DOB }}">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>Địa Chỉ
                                    </label>
                                    <textarea class="form-control" name="address" id="address" rows="3" required>{{ $user->address }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập Nhật Thông Tin
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-2"></i>Khôi Phục
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change Section -->
                    <div class="password-section">
                        <button class="password-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#passwordForm" aria-expanded="false" aria-controls="passwordForm">
                            <style>
                                .custom-position {
                                    display: inline-flex;
                                    align-items: center;
                                    margin-top: 25px; /* Điều chỉnh theo nhu cầu */
                                    padding: 8px 12px;
                                    background: #f0f0f0;
                                    border-radius: 4px;
                                }
                            </style>

                            <div class="custom-position">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Đổi Mật Khẩu</strong>
                            </div>

                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="collapse" id="passwordForm">
                            <div class="password-form">
                                <form id="changePasswordForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label fw-bold">
                                            <i class="fas fa-key me-2 text-muted"></i>Mật Khẩu Hiện Tại
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="current_password" id="current_password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                <i class="fas fa-eye" id="current_password_icon"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="current_password_error"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label fw-bold">
                                            <i class="fas fa-lock me-2 text-muted"></i>Mật Khẩu Mới
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password" id="new_password" required minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                                <i class="fas fa-eye" id="new_password_icon"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" id="password_strength_bar" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted" id="password_strength_text">Mật khẩu phải có ít nhất 8 ký tự</small>
                                        </div>
                                        <div class="invalid-feedback" id="new_password_error"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password_confirmation" class="form-label fw-bold">
                                            <i class="fas fa-check-circle me-2 text-muted"></i>Xác Nhận Mật Khẩu Mới
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="confirm_password_error"></div>
                                        <div class="valid-feedback" id="confirm_password_success">
                                            <i class="fas fa-check-circle me-1"></i>Mật khẩu khớp!
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                                        <i class="fas fa-shield-alt me-2"></i>Cập Nhật Mật Khẩu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 right-column">
                    <!-- Account Information -->
                    <div class="info-card">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle text-primary"></i>
                            Thông Tin Tài Khoản
                        </h3>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card me-2"></i>ID Tài Khoản
                            </div>
                            <div class="info-value">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user-tag me-2"></i>Vai Trò
                            </div>
                            <div class="info-value">
                                <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus me-2"></i>Ngày Tạo Tài Khoản
                            </div>
                            <div class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-edit me-2"></i>Cập Nhật Lần Cuối
                            </div>
                            <div class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                        </div>

                        @if($user->email_verified_at)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-check-circle me-2 text-success"></i>Email Đã Xác Thực
                                </div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($user->email_verified_at)->format('d/m/Y H:i') }}</div>
                            </div>
                        @else
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Email Chưa Xác Thực
                                </div>
                                <div class="info-value">
                                    <button class="btn btn-sm btn-warning">Gửi Email Xác Thực</button>
                                </div>
                            </div>
                        @endif

                        @if($recentOrders->count() > 0)
                            <div class="mt-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-history me-2"></i>Đơn Hàng Gần Đây
                                </h5>
                                @foreach($recentOrders as $order)
                                    <div class="info-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>#{{ $order->order_code }}</strong>
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</small>
                                            </div>
                                            <div class="text-end">
                                                <strong>{{ number_format($order->total) }}đ</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có đơn hàng nào</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alert function
            function showAlert(message, type = 'success') {
                const alertContainer = document.getElementById('alertContainer');
                const alertClass = type === 'error' ? 'alert-error' : 'alert-success';

                const alertHtml = `
            <div class="alert alert-custom ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                alertContainer.innerHTML = alertHtml;

                // Auto remove after 5 seconds
                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }

            // Profile form submission
            document.getElementById('profileForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
                submitBtn.disabled = true;

                fetch('{{ route("profile.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert(data.message, 'success');
                            // Reload page to update header info
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showAlert(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi cập nhật thông tin!', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });

            // Toggle password visibility
            window.togglePassword = function(fieldId) {
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(fieldId + '_icon');

                if (field.type === 'password') {
                    field.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    field.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            };

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = '';

                if (password.length >= 8) strength += 1;
                if (password.match(/[a-z]/)) strength += 1;
                if (password.match(/[A-Z]/)) strength += 1;
                if (password.match(/[0-9]/)) strength += 1;
                if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

                const strengthBar = document.getElementById('password_strength_bar');
                const strengthText = document.getElementById('password_strength_text');

                switch (strength) {
                    case 0:
                    case 1:
                        strengthBar.style.width = '20%';
                        strengthBar.className = 'progress-bar weak';
                        feedback = 'Mật khẩu rất yếu';
                        break;
                    case 2:
                        strengthBar.style.width = '40%';
                        strengthBar.className = 'progress-bar weak';
                        feedback = 'Mật khẩu yếu';
                        break;
                    case 3:
                        strengthBar.style.width = '60%';
                        strengthBar.className = 'progress-bar medium';
                        feedback = 'Mật khẩu trung bình';
                        break;
                    case 4:
                        strengthBar.style.width = '80%';
                        strengthBar.className = 'progress-bar strong';
                        feedback = 'Mật khẩu mạnh';
                        break;
                    case 5:
                        strengthBar.style.width = '100%';
                        strengthBar.className = 'progress-bar strong';
                        feedback = 'Mật khẩu rất mạnh';
                        break;
                }

                strengthText.textContent = feedback;
                return strength;
            }

            // Real-time password validation
            document.getElementById('new_password').addEventListener('input', function() {
                const password = this.value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;

                // Check strength
                const strength = checkPasswordStrength(password);

                // Validate length
                if (password.length < 8) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    document.getElementById('new_password_error').textContent = 'Mật khẩu phải có ít nhất 8 ký tự';
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    document.getElementById('new_password_error').textContent = '';
                }

                // Check confirmation match if confirm field has value
                if (confirmPassword) {
                    validatePasswordConfirmation();
                }
            });

            // Password confirmation validation
            function validatePasswordConfirmation() {
                const password = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;
                const confirmField = document.getElementById('new_password_confirmation');
                const errorDiv = document.getElementById('confirm_password_error');
                const successDiv = document.getElementById('confirm_password_success');

                if (confirmPassword === '') {
                    confirmField.classList.remove('is-invalid', 'is-valid');
                    errorDiv.textContent = '';
                    successDiv.style.display = 'none';
                    return false;
                }

                if (password !== confirmPassword) {
                    confirmField.classList.add('is-invalid');
                    confirmField.classList.remove('is-valid');
                    errorDiv.textContent = 'Mật khẩu xác nhận không khớp';
                    successDiv.style.display = 'none';
                    return false;
                } else {
                    confirmField.classList.remove('is-invalid');
                    confirmField.classList.add('is-valid');
                    errorDiv.textContent = '';
                    successDiv.style.display = 'block';
                    return true;
                }
            }

            document.getElementById('new_password_confirmation').addEventListener('input', validatePasswordConfirmation);

            // Password change form submission
            document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                // Validate form
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;

                let hasError = false;

                if (!currentPassword) {
                    document.getElementById('current_password').classList.add('is-invalid');
                    document.getElementById('current_password_error').textContent = 'Vui lòng nhập mật khẩu hiện tại';
                    hasError = true;
                }

                if (newPassword.length < 8) {
                    document.getElementById('new_password').classList.add('is-invalid');
                    document.getElementById('new_password_error').textContent = 'Mật khẩu mới phải có ít nhất 8 ký tự';
                    hasError = true;
                }

                if (newPassword !== confirmPassword) {
                    document.getElementById('new_password_confirmation').classList.add('is-invalid');
                    document.getElementById('confirm_password_error').textContent = 'Mật khẩu xác nhận không khớp';
                    hasError = true;
                }

                if (hasError) {
                    showAlert('Vui lòng kiểm tra lại thông tin!', 'error');
                    return;
                }

                const formData = new FormData(this);
                const submitBtn = document.getElementById('changePasswordBtn');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
                submitBtn.disabled = true;

                fetch('{{ route("profile.password.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('🎉 ' + data.message, 'success');
                            this.reset(); // Clear form

                            // Reset password strength
                            document.getElementById('password_strength_bar').style.width = '0%';
                            document.getElementById('password_strength_text').textContent = 'Mật khẩu phải có ít nhất 8 ký tự';

                            // Remove validation classes
                            document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                                el.classList.remove('is-valid', 'is-invalid');
                            });

                            // Hide success message
                            document.getElementById('confirm_password_success').style.display = 'none';

                            // Collapse the password form after 2 seconds
                            setTimeout(() => {
                                const collapse = new bootstrap.Collapse(document.getElementById('passwordForm'));
                                collapse.hide();
                            }, 2000);
                        } else {
                            if (data.message.includes('Mật khẩu hiện tại')) {
                                document.getElementById('current_password').classList.add('is-invalid');
                                document.getElementById('current_password_error').textContent = data.message;
                            }
                            showAlert('❌ ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('❌ Có lỗi xảy ra khi đổi mật khẩu!', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
        });
    </script>
@endsection
