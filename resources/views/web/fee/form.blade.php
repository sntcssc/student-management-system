<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #161aff 100%);
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
            background: var(--success-gradient);
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
        
        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #3358ff;
        }
        
        [data-bs-theme="dark"] .info-section {
            background: #2c2c2c;
        }
        
        .info-section h5 {
            color: #11998e;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        [data-bs-theme="dark"] .info-section h5 {
            color: #161aff;
        }
        
        .info-section p {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .info-section p strong {
            min-width: 140px;
            display: inline-block;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 15px;
            border: 1px solid #e0e0e0;
            padding: 1rem;
            height: auto;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #161aff;
            box-shadow: 0 0 0 0.25rem rgba(56, 239, 125, 0.25);
        }
        
        .btn-success {
            background: var(--success-gradient);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-success:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4);
        }
        
        .btn-success:disabled {
            cursor: not-allowed;
            opacity: 0.7;
            transform: none;
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
        
        .qr-section {
            text-align: center;
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
            border: 1px dashed #161aff;
            animation: fadeIn 0.5s ease;
        }
        
        [data-bs-theme="dark"] .qr-section {
            background: #2c2c2c;
        }
        
        .qr-section img {
            max-width: 260px;
            border: 2px solid #161aff;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .qr-section img:hover {
            transform: scale(1.05);
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
        
        .file-status {
            color: #161aff;
            font-size: 0.9rem;
            margin-top: 5px;
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
        
        .form-icon {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #161aff;
            z-index: 10;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group .form-control {
            padding-left: 2.5rem;
        }
        
        .input-group .form-select {
            padding-left: 2.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: #161aff;
        }
        
        [data-bs-theme="dark"] .form-label {
            color: #e0e0e0;
        }
        
        .tooltip-icon {
            cursor: help;
            margin-left: 5px;
            color: #6c757d;
        }
        
        [data-bs-theme="dark"] .tooltip-icon {
            color: #adb5bd;
        }
        
        .invalid-feedback {
            display: none;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .form-control.is-invalid ~ .invalid-feedback,
        .form-select.is-invalid ~ .invalid-feedback {
            display: block;
        }
        
        .payment-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border-left: 4px solid #161aff;
            display: none;
        }
        
        [data-bs-theme="dark"] .payment-summary {
            background: #2c2c2c;
        }
        
        .payment-summary h5 {
            color: #11998e;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        [data-bs-theme="dark"] .payment-summary h5 {
            color: #161aff;
        }
        
        .payment-summary p {
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
        }
        
        .payment-summary .total {
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
            
            .info-section p strong {
                min-width: 100px;
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
        <span class="d-none d-md-inline">Toggle Dark Mode</span>
    </button>

    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header">
                        <h4><i class="bi bi-credit-card-fill me-2"></i>Submit Fee Payment</h4>
                        <p class="mb-0 mt-2">Complete the form below to submit your fee payment</p>
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
                        <!-- Student Info Display -->
                        <div class="info-section animate__animated animate__fadeInLeft">
                            <h5><i class="bi bi-person-badge-fill me-2"></i>Student Information</h5>
                            <p><strong>First Name:</strong> {{ $student['First_Name'] ?? 'N/A' }}</p>
                            <p><strong>Last Name:</strong> {{ $student['Last_Name'] ?? 'N/A' }}</p>
                            <p><strong>Student ID:</strong> {{ $student['Student_ID'] ?? 'N/A' }}</p>
                            <p><strong>Section:</strong> {{ $student['Section'] ?? 'N/A' }}</p>
                            <p><strong>Category:</strong> {{ $student['Category'] ?? 'N/A' }}</p>
                        </div>
                        <form id="feeForm" method="POST" action="{{ route('fee.submit') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="application_number" value="{{ $student['Application_No'] ?? '' }}">
                            <input type="hidden" name="student_id" value="{{ $student['Student_ID'] ?? '' }}">
                            <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
                            
                            <div class="mb-3 animate__animated animate__fadeIn">
                                <label for="transaction_type" class="form-label">
                                    <i class="bi bi-arrow-left-right"></i> Transaction Type
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Select the type of fee transaction"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-arrow-left-right"></i></span>
                                    <select class="form-select" id="transaction_type" name="transaction_type" required aria-required="true">
                                        <option value="">Select Type</option>
                                        <option value="fee_payment">Tuition Fee Payment</option>
                                        <option value="security_deposit">Library Security Deposit</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback">Please select a transaction type.</div>
                            </div>
                            
                            <div class="mb-3 animate__animated animate__fadeIn" id="feeMonthSection" style="display: none;">
                                <label for="fee_month" class="form-label">
                                    <i class="bi bi-calendar-month"></i> Fee Month
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Select the month for tuition fee payment"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-calendar-month"></i></span>
                                    <select class="form-select" id="fee_month" name="fee_month" aria-required="true">
                                        <option value="">Select Month</option>
                                        <option value="2025-08">August 2025</option>
                                        <option value="2025-09">September 2025</option>
                                        <option value="2025-10">October 2025</option>
                                        <option value="2025-11">November 2025</option>
                                        <option value="2025-12">December 2025</option>
                                        <option value="2026-01">January 2026</option>
                                        <option value="2026-02">February 2026</option>
                                        <option value="2026-03">March 2026</option>
                                        <option value="2026-04">April 2026</option>
                                        <option value="2026-05">May 2026</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback">Please select a fee month.</div>
                            </div>
                            
                            <div class="mb-3 animate__animated animate__fadeIn">
                                <label for="amount" class="form-label">
                                    <i class="bi bi-currency-rupee"></i> Amount
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Select the payment amount"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-currency-rupee"></i></span>
                                    <select class="form-select" id="amount" name="amount" required aria-required="true">
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                        <option value="1500">1500</option>
                                        <option value="2000">2000</option>
                                        <option value="2500">2500</option>
                                        <option value="3000">3000</option>
                                        <option value="3500">3500</option>
                                        <option value="4000">4000</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback">Please select a valid amount.</div>
                            </div>
                            
                            <div class="mb-3 animate__animated animate__fadeIn">
                                <label for="transaction_date" class="form-label">
                                    <i class="bi bi-calendar-event"></i> Transaction Date
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Select the date of payment"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" max="{{ date('Y-m-d') }}" required aria-required="true">
                                </div>
                                <div class="invalid-feedback">Please select a valid transaction date (not in the future).</div>
                            </div>
                            
                            <div class="mb-3 animate__animated animate__fadeIn">
                                <label for="payment_method" class="form-label">
                                    <i class="bi bi-wallet2"></i> Payment Method
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Choose your payment method"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-wallet2"></i></span>
                                    <select class="form-select" id="payment_method" name="payment_method" required aria-required="true">
                                        <option value="UPI QR Code" selected>UPI QR Code</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback">Please select a payment method.</div>
                            </div>
                            
                            <div class="qr-section animate__animated animate__fadeIn" id="upiQrSection">
                                <h6><i class="bi bi-qr-code-scan me-2"></i>UPI QR Code Payment Instructions</h6>
                                <img src="{{ url('public/storage/images/upi-qr-code.jpg') }}" alt="UPI QR Code">
                            <div style="max-width: 600px; margin: 20px auto; line-height: 1.6; text-align: left;">
                            
                              <h3>📲 UPI Payment Instructions</h3>
                            
                              <ol>
                                <li>Scan the QR code using any UPI app (PhonePe, GPay, Paytm, etc.).</li>
                                <li>Complete the payment.</li>
                                <li>After payment:
                                  <ul>
                                    <li>PhonePe: Enter <strong>UTR number</strong>.</li>
                                    <li>GPay: Enter <strong>UPI Transaction ID</strong>.</li>
                                    <li>Paytm: Enter <strong>UPI Ref. No.</strong>.</li>
                                    <li>Others: Enter <strong>Transaction or Reference ID</strong>.</li>
                                  </ul>
                                </li>
                                <li>Upload a screenshot of the payment confirmation.</li>
                              </ol>
                            
                              <p><strong>🔎 Find UTR/Transaction/Ref. ID:</strong> On the success screen or in your UPI app's transaction history.</p>
                            
                            </div>

                                <p class="mt-2" style="display:none;">Scan this QR code using your UPI app. After payment, enter the unique transaction reference number (UTR) and upload the screenshot.</p>
                            </div>
                            
                            <div class="mb-3 animate__animated animate__fadeIn">
                                <label for="reference_no" class="form-label">
                                    <i class="bi bi-hash"></i> UTR or UPI Transaction ID (Numeric Only)
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Enter the payment reference number (optional)"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-hash"></i></span>
                                    <input type="number" class="form-control" id="reference_no" name="reference_no" aria-describedby="reference_no_help">
                                </div>
                                <div class="invalid-feedback">Please enter a numeric reference number.</div>
                            </div>
                            
                            <div class="mb-3 animate__animated animate__fadeIn">
                                <label for="attachment" class="form-label">
                                    <i class="bi bi-paperclip"></i> Payment Proof (JPG/PNG/PDF, Max 2MB)
                                    <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Upload proof of payment (JPG, PNG, or PDF)"></i>
                                </label>
                                <div class="input-group">
                                    <span class="form-icon"><i class="bi bi-paperclip"></i></span>
                                    <input type="file" class="form-control" id="attachment" name="attachment" accept=".jpg,.jpeg,.png,.pdf" required aria-required="true">
                                </div>
                                <div class="invalid-feedback">Please upload a valid file (JPG, PNG, PDF) not exceeding 2MB.</div>
                                <div id="fileStatus" class="file-status"></div>
                            </div>
                            
                            <div class="payment-summary" id="paymentSummary">
                                <h5><i class="bi bi-receipt me-2"></i>Payment Summary</h5>
                                <div id="summaryContent"></div>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success" id="submitButton">
                                    <i class="bi bi-check-circle me-2"></i> Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 fw-bold">Processing Payment Submission...</p>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} SNTCSSC Student Fees Submission Portal. All rights reserved.</p>
            <p class="mb-0">For support, contact: iascoaching.sntcssc@gmail.com</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        const category = '{{ $student['Category'] ?? '' }}';
        const isUnreserved = category === 'Unreserved' || category === 'UR';
        const tuitionAmount = isUnreserved ? '1000' : '500';
        
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
            
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            
            // Set default amount based on category
            document.getElementById('amount').value = tuitionAmount;
        });
        
        // Form logic and validation
        const form = document.getElementById('feeForm');
        const transactionType = document.getElementById('transaction_type');
        const feeMonthSection = document.getElementById('feeMonthSection');
        const amountSelect = document.getElementById('amount');
        const paymentMethod = document.getElementById('payment_method');
        const upiQrSection = document.getElementById('upiQrSection');
        const referenceNo = document.getElementById('reference_no');
        const attachment = document.getElementById('attachment');
        const transactionDate = document.getElementById('transaction_date');
        const fileStatus = document.getElementById('fileStatus');
        const submitButton = document.getElementById('submitButton');
        const paymentSummary = document.getElementById('paymentSummary');
        const summaryContent = document.getElementById('summaryContent');
        
        transactionType.addEventListener('change', function() {
            const type = this.value;
            feeMonthSection.style.display = type === 'fee_payment' ? 'block' : 'none';
            feeMonthSection.querySelector('select').required = type === 'fee_payment';
            
            if (type === 'fee_payment') {
                amountSelect.value = tuitionAmount;
                amountSelect.disabled = true;
            } else {
                amountSelect.value = '';
                amountSelect.disabled = false;
            }
            
            updatePaymentSummary();
        });
        
        paymentMethod.addEventListener('change', function() {
            upiQrSection.style.display = this.value === 'UPI QR Code' ? 'block' : 'none';
            updatePaymentSummary();
        });
        
        amountSelect.addEventListener('change', updatePaymentSummary);
        transactionDate.addEventListener('change', updatePaymentSummary);
        referenceNo.addEventListener('input', updatePaymentSummary);
        
        function updatePaymentSummary() {
            const type = transactionType.value;
            const month = document.getElementById('fee_month').value;
            const amount = amountSelect.value;
            const date = transactionDate.value;
            const method = paymentMethod.value;
            const ref = referenceNo.value;
            
            if (type && amount && date && method) {
                let summaryHTML = `
                    <p><strong>Transaction Type:</strong> ${transactionType.options[transactionType.selectedIndex].text}</p>
                `;
                
                if (type === 'fee_payment' && month) {
                    summaryHTML += `<p><strong>Fee Month:</strong> ${document.getElementById('fee_month').options[document.getElementById('fee_month').selectedIndex].text}</p>`;
                }
                
                summaryHTML += `
                    <p><strong>Amount:</strong> ₹${amount}</p>
                    <p><strong>Transaction Date:</strong> ${new Date(date).toLocaleDateString()}</p>
                    <p><strong>Payment Method:</strong> ${method}</p>
                `;
                
                if (ref) {
                    summaryHTML += `<p><strong>Reference No:</strong> ${ref}</p>`;
                }
                
                summaryHTML += `<p class="total"><strong>Total Amount:</strong> ₹${amount}</p>`;
                
                summaryContent.innerHTML = summaryHTML;
                paymentSummary.style.display = 'block';
            } else {
                paymentSummary.style.display = 'none';
            }
        }
        
        attachment.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                if (!['image/jpeg', 'image/png', 'application/pdf'].includes(file.type)) {
                    this.classList.add('is-invalid');
                    fileStatus.textContent = 'Invalid file type. Only JPG, PNG, PDF allowed.';
                    fileStatus.style.color = '#dc3545';
                    toastr.error('Invalid file type. Only JPG, PNG, PDF allowed.');
                } else if (file.size > 2048 * 1024) {
                    this.classList.add('is-invalid');
                    fileStatus.textContent = 'File size exceeds 2MB.';
                    fileStatus.style.color = '#dc3545';
                    toastr.error('File size exceeds 2MB.');
                } else {
                    this.classList.remove('is-invalid');
                    fileStatus.textContent = 'File selected: ' + file.name;
                    fileStatus.style.color = '#161aff';
                }
            } else {
                this.classList.add('is-invalid');
                fileStatus.textContent = 'No file selected.';
                fileStatus.style.color = '#dc3545';
            }
        });
        
        form.addEventListener('submit', function(e) {
            let valid = true;
            
            // Validate transaction type
            if (!transactionType.value) {
                transactionType.classList.add('is-invalid');
                valid = false;
            } else {
                transactionType.classList.remove('is-invalid');
            }
            
            // Validate fee month if required
            const feeMonth = document.getElementById('fee_month');
            if (transactionType.value === 'fee_payment' && !feeMonth.value) {
                feeMonth.classList.add('is-invalid');
                valid = false;
            } else {
                feeMonth.classList.remove('is-invalid');
            }
            
            // Validate amount
            if (!amountSelect.value || parseFloat(amountSelect.value) < 0) {
                amountSelect.classList.add('is-invalid');
                valid = false;
            } else {
                amountSelect.classList.remove('is-invalid');
            }
            
            // Validate transaction date
            if (!transactionDate.value || new Date(transactionDate.value) > new Date()) {
                transactionDate.classList.add('is-invalid');
                valid = false;
            } else {
                transactionDate.classList.remove('is-invalid');
            }
            
            // Validate payment method
            if (!paymentMethod.value) {
                paymentMethod.classList.add('is-invalid');
                valid = false;
            } else {
                paymentMethod.classList.remove('is-invalid');
            }
            
            // Validate reference no (numeric only, optional)
            if (referenceNo.value && isNaN(referenceNo.value)) {
                referenceNo.classList.add('is-invalid');
                valid = false;
            } else {
                referenceNo.classList.remove('is-invalid');
            }
            
            // Validate attachment
            if (!attachment.files.length) {
                attachment.classList.add('is-invalid');
                fileStatus.textContent = 'Please upload a payment proof file.';
                fileStatus.style.color = '#dc3545';
                valid = false;
            } else {
                attachment.classList.remove('is-invalid');
            }
            
            if (!valid) {
                e.preventDefault();
                toastr.error('Please correct the errors in the form.', 'Validation Error');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-check-circle me-2"></i>Submit Payment';
            } else {
                // Optional: Add confirmation dialog
                if (!confirm('Are you sure you want to submit this payment?')) {
                    e.preventDefault();
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="bi bi-check-circle me-2"></i>Submit Payment';
                    return;
                }
                
                // Re-enable amount select and disable submit button
                amountSelect.disabled = false;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';
                document.getElementById('loadingOverlay').style.display = 'flex';
            }
        });
        
        // Remove validation feedback on input
        // document.getElementById('student_id').addEventListener('input', function() {
        //     this.classList.remove('is-invalid');
        // });
        
        // document.getElementById('dob').addEventListener('input', function() {
        //     this.classList.remove('is-invalid');
        // });
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