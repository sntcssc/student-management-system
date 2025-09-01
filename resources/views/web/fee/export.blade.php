<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fee Transactions Export</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #f5f7fa, #c3cfe2); transition: background 0.3s; }
        [data-bs-theme="dark"] body { background: linear-gradient(135deg, #1e3c72, #2a5298); }
        .card { border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); overflow: hidden; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card-header { border-top-left-radius: 20px; border-top-right-radius: 20px; background: linear-gradient(135deg, #28a745, #00c853); padding: 20px; transition: background 0.3s; }
        .btn-primary, .btn-success, .btn-info, .btn-warning { border-radius: 50px; font-weight: 600; transition: transform 0.3s, box-shadow 0.3s; }
        .btn-primary:hover, .btn-success:hover, .btn-info:hover, .btn-warning:hover { transform: scale(1.05); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .dark-mode-toggle { border-radius: 50px; background: linear-gradient(135deg, #6a11cb, #2575fc); color: white; transition: background 0.3s; }
        .form-control, .form-select, .form-control-file { border-radius: 15px; padding: 12px; transition: border-color 0.3s, box-shadow 0.3s; }
        .form-control:focus, .form-select:focus, .form-control-file:focus { border-color: #28a745; box-shadow: 0 0 0 0.25rem rgba(40,167,69,0.25); }
        .form-label { font-weight: 500; color: #495057; transition: color 0.3s; }
        .table { border-radius: 15px; overflow: hidden; background: #fff; transition: background 0.3s; }
        .table th, .table td { vertical-align: middle; }
        .table th { background: #e9ecef; }
        [data-bs-theme="dark"] .table { background: #2a2a2a; }
        [data-bs-theme="dark"] .table th { background: #3a3a3a; }
        .dark-mode-toggle { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        .alert { border-radius: 15px; animation: fadeIn 0.5s; }
        .loading-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 1050; justify-content: center; align-items: center; flex-direction: column; transition: background 0.3s; }
        .loading-overlay.dark { background: rgba(0,0,0,0.9); color: white; }
        .spinner-border { width: 4rem; height: 4rem; animation: spinner-border 1s linear infinite; }
        .modal-content { border-radius: 15px; }
        .modal-header { background: linear-gradient(135deg, #28a745, #00c853); color: white; }
        .status-select { width: 120px; }
        @media (max-width: 576px) {
            .card { margin: 10px; }
            .dark-mode-toggle { top: 10px; right: 10px; }
            .btn { font-size: 0.85rem; }
            .table-responsive { font-size: 0.8rem; }
            .status-select { width: 100px; }
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="animate__animated animate__fadeIn">
    <button class="btn dark-mode-toggle animate__animated animate__bounceInRight" onclick="toggleDarkMode()" aria-label="Toggle Dark Mode">
        <i class="fas fa-moon me-2"></i> Toggle Dark Mode
    </button>
    <div class="container my-5 animate__animated animate__zoomIn">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-table me-2"></i>Fee Transactions</h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="GET" action="{{ route('fee.export') }}" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label for="search" class="form-label"><i class="fas fa-search me-2"></i>Search</label>
                                    <input type="text" class="form-control" id="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by name, student ID, or application number">
                                </div>
                                <div class="col-md-3">
                                    <label for="per_page" class="form-label"><i class="fas fa-list me-2"></i>Records per page</label>
                                    <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                                        <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $per_page == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $per_page == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" name="export" value="excel" class="btn btn-primary w-100"><i class="fas fa-file-excel me-2"></i>Export to Excel</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Transaction Type</th>
                                        <th>Amount</th>
                                        <th>Fee Month</th>
                                        <th class="d-none">Payment Method</th>
                                        <th>UTR</th>
                                        <th>Status</th>
                                        <th>Transaction Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($feeTransactions as $index => $transaction)
                                        <tr>
                                            <td>{{ $feeTransactions->firstItem() + $index }}</td>
                                            <td>{{ $transaction->student_id }}</td>
                                            <td>{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                                            <td>{{ Str::title(str_replace('_', ' ', $transaction->transaction_type)) }}</td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->fee_month ? \Carbon\Carbon::parse($transaction->fee_month)->format('F Y') : 'N/A' }}</td>
                                            <td class="d-none">{{ $transaction->payment_method }}</td>
                                            <td>{{ $transaction->reference_no }}</td>
                                            <td>
                                                <select class="form-select status-select" data-transaction-id="{{ $transaction->id }}" onchange="updateStatus(this)">
                                                    <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                    <option value="rejected" {{ $transaction->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-transaction" data-transaction-id="{{ $transaction->id }}" title="Edit Transaction"><i class="fas fa-edit me-1"></i>Edit</button>
                                                <a href="{{ route('fee.confirmation', $transaction->id) }}" class="btn btn-success btn-sm" title="Download Receipt"><i class="fas fa-download me-1"></i>Receipt</a>
                                                @if ($transaction->attachment_path)
                                                    <a href="{{ route('fee.attachment', $transaction->id) }}" class="btn btn-info btn-sm" title="View Attachment"><i class="fas fa-paperclip me-1"></i>Attachment</a>
                                                @else
                                                    <span class="text-muted">No Attachment</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No transactions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $feeTransactions->appends(['search' => $search, 'per_page' => $per_page])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Transaction Modal -->
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTransactionModalLabel"><i class="fas fa-edit me-2"></i>Edit Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTransactionForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="edit_transaction_id">
                        <div class="mb-3">
                            <label for="edit_transaction_type" class="form-label"><i class="fas fa-exchange-alt me-2"></i>Transaction Type</label>
                            <select class="form-select" id="edit_transaction_type" name="transaction_type" required>
                                <option value="fee_payment">Tuition Fee Payment</option>
                                <option value="security_deposit">Library Security Deposit</option>
                                <option value="security_refund">Security Refund</option>
                            </select>
                            <div class="invalid-feedback">Please select a transaction type.</div>
                        </div>
                        <div class="mb-3" id="edit_feeMonthSection" style="display: none;">
                            <label for="edit_fee_month" class="form-label"><i class="fas fa-calendar-month me-2"></i>Fee Month</label>
                            <select class="form-select" id="edit_fee_month" name="fee_month">
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
                            <div class="invalid-feedback">Please select a fee month.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label"><i class="fas fa-rupee-sign me-2"></i>Amount</label>
                            <select class="form-select" id="edit_amount" name="amount" required>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                                <option value="1500">1500</option>
                                <option value="2000">2000</option>
                                <option value="2500">2500</option>
                                <option value="3000">3000</option>
                                <option value="3500">3500</option>
                                <option value="4000">4000</option>
                            </select>
                            <div class="invalid-feedback">Please select a valid amount.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_transaction_date" class="form-label"><i class="fas fa-calendar-day me-2"></i>Transaction Date</label>
                            <input type="date" class="form-control" id="edit_transaction_date" name="transaction_date" max="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">Please select a valid transaction date.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payment_method" class="form-label"><i class="fas fa-wallet me-2"></i>Payment Method</label>
                            <select class="form-select" id="edit_payment_method" name="payment_method" required>
                                <option value="UPI QR Code">UPI QR Code</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">Please select a payment method.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_reference_no" class="form-label"><i class="fas fa-hashtag me-2"></i>Reference No. (Numeric Only)</label>
                            <input type="number" class="form-control" id="edit_reference_no" name="reference_no">
                            <div class="invalid-feedback">Please enter a numeric reference number.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_note" class="form-label"><i class="fas fa-sticky-note me-2"></i>Note</label>
                            <textarea class="form-control" id="edit_note" name="note" rows="4" maxlength="1000" placeholder="Enter any additional notes"></textarea>
                            <div class="invalid-feedback">Note must not exceed 1000 characters.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_attachment" class="form-label"><i class="fas fa-paperclip me-2"></i>Payment Proof (JPG/PNG/PDF, Max 2MB, Optional)</label>
                            <input type="file" class="form-control" id="edit_attachment" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="invalid-feedback">Please upload a valid file (JPG, PNG, PDF) not exceeding 2MB.</div>
                            <div id="edit_fileStatus" class="file-status"></div>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="edit_submitButton"><i class="fas fa-save me-2"></i>Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 fw-bold">Processing...</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
        }

        function updateStatus(select) {
            const transactionId = select.dataset.transactionId;
            const status = select.value;
            if (!confirm(`Are you sure you want to change the status to ${status}?`)) {
                select.value = select.dataset.currentStatus || 'pending';
                return;
            }
            select.dataset.currentStatus = status;
            document.getElementById('loadingOverlay').style.display = 'flex';

            fetch(`/fee/update-status/${transactionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status: status }),
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingOverlay').style.display = 'none';
                if (data.success) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                    select.value = select.dataset.currentStatus || 'pending';
                }
            })
            .catch(error => {
                document.getElementById('loadingOverlay').style.display = 'none';
                toastr.error('Failed to update status: ' + error.message);
                select.value = select.dataset.currentStatus || 'pending';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.toggle('dark', savedTheme === 'dark');
            @if (session('toastr_success'))
                toastr.success('{{ session('toastr_success') }}');
            @endif
            @if (session('toastr_error'))
                toastr.error('{{ session('toastr_error') }}');
            @endif

            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-toggle="tooltip"]');
            tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

            // Show loading overlay on button clicks
            document.querySelectorAll('.btn-success, .btn-info, .btn-warning').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('loadingOverlay').style.display = 'flex';
                });
            });

            // Edit transaction
            const modal = new bootstrap.Modal(document.getElementById('editTransactionModal'));
            document.querySelectorAll('.edit-transaction').forEach(button => {
                button.addEventListener('click', () => {
                    const transactionId = button.dataset.transactionId;
                    fetch(`/fee/edit/${transactionId}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        if (data.success) {
                            const transaction = data.transaction;
                            const fullDate = transaction.transaction_date;
                            const dateOnly = fullDate.split("T")[0];
                            const rawAmount = transaction.amount; // "500.00"
                            const normalizedAmount = parseFloat(rawAmount).toString(); // "500"
                            console.log(transaction);
                            document.getElementById('edit_transaction_id').value = transaction.id;
                            document.getElementById('edit_transaction_type').value = transaction.transaction_type;
                            document.getElementById('edit_fee_month').value = transaction.fee_month || '';
                            document.getElementById('edit_amount').value = normalizedAmount;
                            document.getElementById('edit_transaction_date').value = dateOnly;
                            document.getElementById('edit_payment_method').value = transaction.payment_method;
                            document.getElementById('edit_reference_no').value = transaction.reference_no || '';
                            document.getElementById('edit_note').value = transaction.note || '';
                            document.getElementById('edit_feeMonthSection').style.display = transaction.transaction_type === 'fee_payment' ? 'block' : 'none';
                            document.getElementById('edit_fileStatus').textContent = transaction.attachment_path ? 'Current file: ' + transaction.attachment_path.split('/').pop() : 'No file uploaded.';
                            modal.show();
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        toastr.error('Failed to load transaction: ' + error.message);
                    });
                });
            });

            // Handle edit form submission
            const editForm = document.getElementById('editTransactionForm');
            const editTransactionType = document.getElementById('edit_transaction_type');
            const editFeeMonthSection = document.getElementById('edit_feeMonthSection');
            const editAmount = document.getElementById('edit_amount');
            const editTransactionDate = document.getElementById('edit_transaction_date');
            const editPaymentMethod = document.getElementById('edit_payment_method');
            const editReferenceNo = document.getElementById('edit_reference_no');
            const editNote = document.getElementById('edit_note');
            const editAttachment = document.getElementById('edit_attachment');
            const editFileStatus = document.getElementById('edit_fileStatus');
            const editSubmitButton = document.getElementById('edit_submitButton');

            editTransactionType.addEventListener('change', function() {
                editFeeMonthSection.style.display = this.value === 'fee_payment' ? 'block' : 'none';
                editFeeMonthSection.querySelector('select').required = this.value === 'fee_payment';
            });

            editAttachment.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (!['image/jpeg', 'image/png', 'application/pdf'].includes(file.type)) {
                        this.classList.add('is-invalid');
                        editFileStatus.textContent = 'Invalid file type. Only JPG, PNG, PDF allowed.';
                        editFileStatus.style.color = '#dc3545';
                        toastr.error('Invalid file type. Only JPG, PNG, PDF allowed.');
                    } else if (file.size > 2048 * 1024) {
                        this.classList.add('is-invalid');
                        editFileStatus.textContent = 'File size exceeds 2MB.';
                        editFileStatus.style.color = '#dc3545';
                        toastr.error('File size exceeds 2MB.');
                    } else {
                        this.classList.remove('is-invalid');
                        editFileStatus.textContent = 'File selected: ' + file.name;
                        editFileStatus.style.color = '#28a745';
                    }
                }
            });

            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                let valid = true;

                if (!editTransactionType.value) {
                    editTransactionType.classList.add('is-invalid');
                    valid = false;
                } else {
                    editTransactionType.classList.remove('is-invalid');
                }

                const editFeeMonth = document.getElementById('edit_fee_month');
                if (editTransactionType.value === 'fee_payment' && !editFeeMonth.value) {
                    editFeeMonth.classList.add('is-invalid');
                    valid = false;
                } else {
                    editFeeMonth.classList.remove('is-invalid');
                }

                if (!editAmount.value || parseFloat(editAmount.value) < 0) {
                    editAmount.classList.add('is-invalid');
                    valid = false;
                } else {
                    editAmount.classList.remove('is-invalid');
                }

                if (!editTransactionDate.value || new Date(editTransactionDate.value) > new Date()) {
                    editTransactionDate.classList.add('is-invalid');
                    valid = false;
                } else {
                    editTransactionDate.classList.remove('is-invalid');
                }

                if (!editPaymentMethod.value) {
                    editPaymentMethod.classList.add('is-invalid');
                    valid = false;
                } else {
                    editPaymentMethod.classList.remove('is-invalid');
                }

                if (editReferenceNo.value && isNaN(editReferenceNo.value)) {
                    editReferenceNo.classList.add('is-invalid');
                    valid = false;
                } else {
                    editReferenceNo.classList.remove('is-invalid');
                }

                if (editNote.value.length > 1000) {
                    editNote.classList.add('is-invalid');
                    valid = false;
                } else {
                    editNote.classList.remove('is-invalid');
                }

                if (!valid) {
                    toastr.error('Please correct the errors in the form.');
                    return;
                }

                if (!confirm('Are you sure you want to save these changes?')) {
                    return;
                }

                editSubmitButton.disabled = true;
                editSubmitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                document.getElementById('loadingOverlay').style.display = 'flex';

                const formData = new FormData(editForm);
                fetch(`/fee/update/${editForm.querySelector('#edit_transaction_id').value}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    editSubmitButton.disabled = false;
                    editSubmitButton.innerHTML = '<i class="fas fa-save me-2"></i>Save Changes';
                    if (data.success) {
                        toastr.success(data.message);
                        modal.hide();
                        window.location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    editSubmitButton.disabled = false;
                    editSubmitButton.innerHTML = '<i class="fas fa-save me-2"></i>Save Changes';
                    toastr.error('Failed to update transaction: ' + error.message);
                });
            });
        });
    </script>
</body>
</html>