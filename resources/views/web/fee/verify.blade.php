<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Student for Fee Payment</title>
    <meta name="description" content="Pay your tuition and library fees for August 2025 via UPI. Deadline: 18th August. Visit https://fees.csscwb.in/">

    <!-- Open Graph (for Facebook, WhatsApp, LinkedIn, etc.) -->
    <meta property="og:title" content="Tuition Fee Payment – August 2025">
    <meta property="og:description" content="Pay your tuition and library fees for August 2025 via UPI. Deadline: 18th August.">
    <meta property="og:url" content="https://fees.csscwb.in/">
    <meta property="og:image" content="https://fees.csscwb.in/public/storage/images/upi-qr-code.jpg">
    <meta property="og:type" content="website">

    <!-- Twitter Card (optional but recommended) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tuition Fee Payment – August 2025">
    <meta name="twitter:description" content="Pay your tuition and library fees via UPI. Deadline: 18th August.">
    <meta name="twitter:image" content="https://fees.csscwb.in/public/storage/images/upi-qr-code.jpg">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #4a6491 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease;
        }
        
        [data-bs-theme="dark"] body {
            background: #121212;
        }
        
        .navbar-custom {
            background: var(--primary-gradient);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            margin-right: 10px;
        }
        
        .main-container {
            flex: 1;
            /* display: flex; */
            align-items: center;
            padding: 2rem 0;
        }
        
        .card {
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .card-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem;
            text-align: center;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border-radius: 15px;
            border: 1px solid #e0e0e0;
            padding: 1rem;
            height: auto;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary i {
            margin-right: 8px;
        }
        
        .dark-mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .dark-mode-toggle:hover {
            transform: scale(1.05);
        }
        
        .dark-mode-toggle i {
            margin-right: 5px;
        }
        
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 1050;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            backdrop-filter: blur(5px);
            transition: background 0.3s ease;
        }
        
        .loading-overlay.dark {
            background: rgba(0, 0, 0, 0.85);
            color: white;
        }
        
        .spinner-border {
            width: 4rem;
            height: 4rem;
            border-width: 0.3em;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .alert-dismissible .btn-close {
            filter: none;
            opacity: 0.7;
        }
        
        .footer {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 0;
            text-align: center;
            margin-top: auto;
        }
        
        .footer p {
            margin: 0;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-icon {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #667eea;
            z-index: 10;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group .form-control {
            padding-left: 2.5rem;
        }
        
        @media (max-width: 768px) {
            .card {
                margin: 0 1rem;
            }
            
            .dark-mode-toggle {
                top: 10px;
                right: 10px;
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="animate__animated animate__fadeIn">
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-mortarboard-fill"></i>
                SNTCSSC Student Fee Submission Portal
            </a>
        </div>
    </nav>

    <button class="btn dark-mode-toggle animate__animated animate__bounceInRight" onclick="toggleDarkMode()">
        <i class="bi bi-moon-stars-fill"></i>
        <span class="d-none d-md-inline">Switch</span>
    </button>

    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header">
                        <h4><i class="bi bi-person-check-fill me-2"></i>Verify Student Details</h4>
                        <p class="mb-0 mt-2">Please enter your details to verify your identity</p>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Error!</strong> Please check the following:
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Error!</strong> {{ session('error') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>
                                        <strong>Success!</strong> {{ session('success') }}
                                        @if (session('pdf_path'))
                                            <div class="mt-2">
                                                <a href="{{ session('pdf_path') }}" class="btn btn-sm btn-outline-success" target="_blank">
                                                    <i class="bi bi-download me-1"></i> Download Receipt
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form id="verifyForm" method="POST" action="{{ route('fee.verify.submit') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="form-icon"><i class="bi bi-person-badge"></i></span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id') }}" placeholder="Student ID" required>
                                    <label for="student_id" style="left:1.5rem;">Student ID</label>
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <span class="form-icon"><i class="bi bi-calendar3"></i></span>
                                <div class="form-floating flex-grow-1">
                                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                                    <label for="dob" style="left:1.5rem;">Date of Birth</label>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-shield-check"></i> Verify Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 fw-bold">Verifying Student Information...</p>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} SNTCSSC Student Fees Submission Portal. All rights reserved.</p>
            <p class="mb-0">For support, contact: iascoaching.sntcssc@gmail.com</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.toggle('dark', newTheme === 'dark');
            
            // Update button icon
            const toggleBtn = document.querySelector('.dark-mode-toggle i');
            if (newTheme === 'dark') {
                toggleBtn.classList.remove('bi-moon-stars-fill');
                toggleBtn.classList.add('bi-sun-fill');
            } else {
                toggleBtn.classList.remove('bi-sun-fill');
                toggleBtn.classList.add('bi-moon-stars-fill');
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.toggle('dark', savedTheme === 'dark');
            
            // Update button icon based on saved theme
            const toggleBtn = document.querySelector('.dark-mode-toggle i');
            if (savedTheme === 'dark') {
                toggleBtn.classList.remove('bi-moon-stars-fill');
                toggleBtn.classList.add('bi-sun-fill');
            }
            
            @if (session('toastr_success'))
                toastr.success('{{ session('toastr_success') }}', 'Success');
            @endif
            @if (session('toastr_error'))
                toastr.error('{{ session('toastr_error') }}', 'Error');
            @endif
        });
        
        // Client-side validation and loading
        const form = document.getElementById('verifyForm');
        form.addEventListener('submit', function(e) {
            let valid = true;
            const studentId = document.getElementById('student_id');
            const dob = document.getElementById('dob');
            
            if (!studentId.value.trim()) {
                studentId.classList.add('is-invalid');
                valid = false;
            } else {
                studentId.classList.remove('is-invalid');
            }
            
            if (!dob.value || new Date(dob.value) >= new Date()) {
                dob.classList.add('is-invalid');
                valid = false;
            } else {
                dob.classList.remove('is-invalid');
            }
            
            if (!valid) {
                e.preventDefault();
                toastr.error('Please correct the errors in the form.', 'Validation Error');
            } else {
                document.getElementById('loadingOverlay').style.display = 'flex';
            }
        });
        
        // Remove validation feedback on input
        document.getElementById('student_id').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        document.getElementById('dob').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    </script>
    
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>
</body>
</html>