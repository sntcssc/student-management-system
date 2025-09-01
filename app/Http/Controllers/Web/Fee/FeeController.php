<?php

// app/Http/Controllers/Fee/FeeController.php
namespace App\Http\Controllers\Web\Fee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Web\FeeTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FeeExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FeeController extends Controller
{
    public function showVerificationForm()
    {
        return view('web.fee.verify');
    }

    public function verifyStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'dob' => 'required|date|before:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('toastr_error', 'Please correct the errors in the form.');
        }

        $jsonPath = storage_path('app/students.json');
        if (!file_exists($jsonPath)) {
            return redirect()->back()
                ->with('error', 'Student data not found')
                ->withInput()
                ->with('toastr_error', 'Student data not found.');
        }

        $students = json_decode(file_get_contents($jsonPath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()
                ->with('error', 'Error reading student data')
                ->withInput()
                ->with('toastr_error', 'Error reading student data.');
        }

        $student = collect($students)->firstWhere('Student_ID', (int)$request->student_id);

        if (!$student || $student['DOB'] !== $request->dob . ' 00:00:00') {
            return redirect()->back()
                ->with('error', 'Invalid Student ID or Date of Birth')
                ->withInput()
                ->with('toastr_error', 'Invalid Student ID or Date of Birth.');
        }

        // Generate and store submission token
        $submissionToken = Str::random(32);
        Session::put('submission_token', $submissionToken);

        return view('web.fee.form', compact('student', 'submissionToken'));
    }

    public function submitFeePayment(Request $request)
    {
        // Rate limiting
        $executed = RateLimiter::attempt(
            'submit-fee:' . $request->ip(),
            5,
            function () use ($request) {
                $validator = Validator::make($request->all(), [
                    'application_number' => 'required|string',
                    'student_id' => 'required|string',
                    'transaction_type' => 'required|in:fee_payment,security_deposit,security_refund',
                    'amount' => 'required|numeric|in:500,1000,1500,2000,2500,3000,3500,4000',
                    'fee_month' => 'nullable|date_format:Y-m',
                    'payment_method' => 'required|in:UPI QR Code,Bank Transfer,Other',
                    'reference_no' => 'nullable|numeric',
                    'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                    'transaction_date' => 'required|date|before_or_equal:today',
                    'submission_token' => 'required|string',
                ]);

                if ($validator->fails()) {
                    Log::warning('Validation failed: ' . json_encode($validator->errors()->all()));
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('toastr_error', 'Validation failed. Please check the form.');
                }

                // Verify submission token
                $sessionToken = Session::get('submission_token');
                if (!$sessionToken || $request->submission_token !== $sessionToken) {
                    Log::warning('Invalid submission token: ' . $request->submission_token);
                    return redirect()->back()
                        ->with('error', 'Invalid submission token. Please try again.')
                        ->withInput()
                        ->with('toastr_error', 'Invalid submission token.');
                }
                Session::forget('submission_token'); // Invalidate token after use

                // Verify student
                $jsonPath = storage_path('app/students.json');
                if (!file_exists($jsonPath)) {
                    Log::error('Student data file not found: ' . $jsonPath);
                    return redirect()->back()
                        ->with('error', 'Student data not found')
                        ->withInput()
                        ->with('toastr_error', 'Student data not found.');
                }

                $students = json_decode(file_get_contents($jsonPath), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Error decoding students.json: ' . json_last_error_msg());
                    return redirect()->back()
                        ->with('error', 'Error reading student data')
                        ->withInput()
                        ->with('toastr_error', 'Error reading student data.');
                }

                $student = collect($students)->firstWhere('Student_ID', (int)$request->student_id);

                if (!$student || $student['Application_No'] !== $request->application_number) {
                    Log::warning('Invalid student details: Student_ID=' . $request->student_id . ', Application_No=' . $request->application_number);
                    return redirect()->back()
                        ->with('error', 'Invalid student details')
                        ->withInput()
                        ->with('toastr_error', 'Invalid student details.');
                }

                // Duplicate check for tuition fees (prevent multiple submissions for same month)
                if ($request->transaction_type === 'fee_payment' && $request->fee_month) {
                    $existing = FeeTransaction::where('student_id', $request->student_id)
                        ->where('transaction_type', 'fee_payment')
                        ->where('fee_month', $request->fee_month)
                        ->whereIn('status', ['paid', 'pending'])
                        ->where('id', '!=', $request->id ?? 0) // Exclude current transaction for edits
                        ->exists();
                    if ($existing) {
                        Log::warning('Duplicate tuition fee submission detected: Student_ID=' . $request->student_id . ', Month=' . $request->fee_month);
                        return redirect()->back()
                            ->with('error', 'Tuition fee for this month has already been submitted or paid.')
                            ->withInput()
                            ->with('toastr_error', 'Tuition fee for this month has already been submitted or paid.');
                    }
                }

                // General duplicate check (same type, amount, month, date)
                $duplicate = FeeTransaction::where('student_id', $request->student_id)
                    ->where('transaction_type', $request->transaction_type)
                    ->where('amount', $request->amount)
                    ->where('fee_month', $request->fee_month)
                    ->where('transaction_date', $request->transaction_date)
                    ->where('id', '!=', $request->id ?? 0) // Exclude current transaction for edits
                    ->exists();
                if ($duplicate) {
                    Log::warning('Duplicate submission detected: Student_ID=' . $request->student_id . ', Type=' . $request->transaction_type);
                    return redirect()->back()
                        ->with('error', 'This submission appears to be a duplicate. Please check your previous submissions.')
                        ->withInput()
                        ->with('toastr_error', 'Duplicate submission detected.');
                }

                try {
                    DB::beginTransaction();

                    $studentId = $student['Student_ID'];

                    // Handle file upload
                    $attachmentPath = null;
                    if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                        $attachmentExtension = $request->file('attachment')->getClientOriginalExtension();
                        $timestamp = date('Ymd_His');
                        $fileName = "{$studentId}_{$request->transaction_type}_{$timestamp}.{$attachmentExtension}";
                        $attachmentPath = $request->file('attachment')->storeAs('fee_attachments', $fileName, 'public');

                        // Verify file was stored
                        if (!Storage::disk('public')->exists($attachmentPath)) {
                            Log::error('Failed to store attachment: ' . $attachmentPath);
                            throw new \Exception('Failed to store attachment at path: ' . $attachmentPath);
                        }
                    } else {
                        Log::error('Invalid or missing attachment file.');
                        throw new \Exception('Invalid or missing attachment file.');
                    }

                    // Auto-set direction
                    $direction = ($request->transaction_type === 'security_refund') ? 'debit' : 'credit';

                    // Auto-set description
                    $description = '';
                    if ($request->transaction_type === 'fee_payment') {
                        $monthYear = $request->fee_month ? Carbon::createFromFormat('Y-m', $request->fee_month)->format('F Y') : '';
                        $description = "Tuition Fee for {$monthYear}";
                    } elseif ($request->transaction_type === 'security_deposit') {
                        $description = 'Library Security Deposit';
                    } elseif ($request->transaction_type === 'security_refund') {
                        $description = 'Security Refund';
                    }

                    // Set amount in controller for fee_payment (ignore request)
                    $amount = $request->amount;
                    if ($request->transaction_type === 'fee_payment') {
                        $category = $student['Category'] ?? '';
                        $isUnreserved = $category === 'Unreserved' || $category === 'UR';
                        $amount = $isUnreserved ? 1000 : 500;
                    }

                    $feeTransaction = FeeTransaction::create([
                        'programme_name' => $student['Programme_Name'] ?? 'Composite Course',
                        'batch' => $student['Batch'] ?? '2026',
                        'application_number' => $student['Application_No'] ?? $request->application_number,
                        'roll_no' => $student['Roll_No'] ?? null,
                        'student_id' => $student['Student_ID'] ?? $request->student_id,
                        'section' => $student['Section'] ?? null,
                        'first_name' => $student['First_Name'] ?? null,
                        'last_name' => $student['Last_Name'] ?? null,
                        'dob' => $student['DOB'] ? Carbon::parse($student['DOB'])->format('Y-m-d') : null,
                        'email' => $student['Email'] ?? null,
                        'mobile_no' => $student['Mobile'] ?? null,
                        'whatsapp_no' => $student['Mobile'] ?? null,
                        'gender' => $student['Gender'] ?? null,
                        'category' => $student['Category'] ?? null,
                        'transaction_type' => $request->transaction_type,
                        'direction' => $direction,
                        'amount' => $amount,
                        'fee_month' => $request->fee_month,
                        'description' => $description,
                        'payment_method' => $request->payment_method,
                        'reference_no' => $request->reference_no,
                        'attachment_path' => $attachmentPath,
                        'note' => $request->note ?? '',
                        'performed_by' => 'student',
                        'remarks' => '',
                        'status' => 'pending',
                        'transaction_date' => $request->transaction_date,
                    ]);

                    // PDF generation
                    $logoPath = public_path('images/logo-rmb.png');
                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
                    $logoBase64 = $logoData ? 'data:image/png;base64,' . $logoData : 'https://via.placeholder.com/120x60?text=SNTCSSC+Logo';

                    $pdf = Pdf::loadView('web.fee.confirmation', compact('feeTransaction', 'student', 'logoBase64'));
                    // $pdfPath = "fee_confirmations/{$studentId}_{$feeTransaction->id}_confirmation.pdf";
                    $timestamp = date('Ymd_His');
                    $pdfPath = "fee_confirmations/{$studentId}_{$feeTransaction->id}_{$timestamp}_confirmation.pdf";
                    Storage::disk('public')->put($pdfPath, $pdf->output());

                    DB::commit();

                    Log::info('Fee submission successful: Transaction_ID=' . $feeTransaction->id . ', Student_ID=' . $studentId);

                    return redirect()->route('fee.verify')
                        ->with('success', 'Fee payment submitted successfully! Download your payment receipt.')
                        // ->with('pdf_path', Storage::url($pdfPath))
                        ->with('pdf_path', url('/file-storage/' . $pdfPath))
                        ->with('toastr_success', 'Form submitted successfully! Download your payment receipt.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                        Storage::disk('public')->delete($attachmentPath);
                    }
                    Log::error('Fee submission error: ' . $e->getMessage());
                    return redirect()->back()
                        ->with('error', 'An error occurred while submitting the form: ' . $e->getMessage())
                        ->withInput()
                        ->with('toastr_error', 'Submission failed: ' . $e->getMessage());
                }
            },
            60
        );

        if (!$executed) {
            Log::warning('Rate limit exceeded for IP: ' . $request->ip());
            return redirect()->back()
                ->with('error', 'Too many attempts. Please try again later.')
                ->with('toastr_error', 'Too many attempts. Please try again later.');
        }else{
            return redirect()->route('fee.verify');
        }
    }

    public function downloadReceiptPDF(Request $request, $id)
    {
        try {
            $feeTransaction = FeeTransaction::findOrFail($id);
            $logoPath = public_path('images/logo-rmb.png');
            $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
            $logoBase64 = $logoData ? 'data:image/png;base64,' . $logoData : 'https://via.placeholder.com/120x60?text=SNTCSSC+Logo';

            $student = collect(json_decode(file_get_contents(storage_path('app/students.json')), true))
                ->firstWhere('Student_ID', (int)$feeTransaction->student_id);

            $pdf = Pdf::loadView('web.fee.confirmation', compact('feeTransaction', 'student', 'logoBase64'));
            // $pdfPath = "fee_confirmations/{$feeTransaction->student_id}_{$id}_confirmation.pdf";
            $timestamp = date('Ymd_His');
            $pdfPath = "fee_confirmations/{$feeTransaction->student_id}_{$id}_{$timestamp}_confirmation.pdf";
            Storage::disk('public')->put($pdfPath, $pdf->output());
            return $pdf->stream("{$feeTransaction->student_id}_confirmation.pdf", [
                'Attachment' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while generating the PDF.')
                ->with('toastr_error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    public function downloadConfirmationPDF(Request $request, $id)
    {
        try {
            $feeTransaction = FeeTransaction::findOrFail($id);
            $logoPath = public_path('images/logo-rmb.png');
            $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
            $logoBase64 = $logoData ? 'data:image/png;base64,' . $logoData : 'https://via.placeholder.com/120x60?text=SNTCSSC+Logo';

            $student = collect(json_decode(file_get_contents(storage_path('app/students.json')), true))
                ->firstWhere('Student_ID', (int)$feeTransaction->student_id);

            if (!$student) {
                Log::warning('Student not found for transaction: ' . $id);
                return redirect()->back()
                    ->with('error', 'Student data not found for this transaction.')
                    ->with('toastr_error', 'Student data not found.');
            }

            $pdf = Pdf::loadView('web.fee.confirmation', compact('feeTransaction', 'student', 'logoBase64'));
            // $pdfPath = "fee_confirmations/{$feeTransaction->student_id}_{$id}_confirmation.pdf";
            $timestamp = date('Ymd_His');
            $pdfPath = "fee_confirmations/{$feeTransaction->student_id}_{$id}_{$timestamp}_confirmation.pdf";
            Storage::disk('public')->put($pdfPath, $pdf->output());

            Log::info('Confirmation PDF generated: Transaction_ID=' . $id . ', Path=' . $pdfPath);

            return $pdf->stream("{$feeTransaction->student_id}_confirmation.pdf", [
                'Attachment' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Confirmation PDF generation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while generating the confirmation PDF.')
                ->with('toastr_error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    public function viewAttachment(Request $request, $id)
    {
        try {
            $feeTransaction = FeeTransaction::findOrFail($id);

            if (!$feeTransaction->attachment_path || !Storage::disk('public')->exists($feeTransaction->attachment_path)) {
                Log::warning('Attachment not found for transaction: ' . $id);
                return redirect()->back()
                    ->with('error', 'Attachment not found for this transaction.')
                    ->with('toastr_error', 'Attachment not found.');
            }

            $filePath = storage_path('app/public/' . $feeTransaction->attachment_path);
            $fileName = basename($feeTransaction->attachment_path);
            $mimeType = Storage::disk('public')->mimeType($feeTransaction->attachment_path);

            Log::info('Serving attachment: Transaction_ID=' . $id . ', Path=' . $feeTransaction->attachment_path);

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Attachment view error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while accessing the attachment.')
                ->with('toastr_error', 'Attachment access failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,paid,rejected',
            ]);

            if ($validator->fails()) {
                Log::warning('Status update validation failed: ' . json_encode($validator->errors()->all()));
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value.',
                ], 422);
            }

            $feeTransaction = FeeTransaction::findOrFail($id);
            $feeTransaction->status = $request->status;
            $feeTransaction->save();

            Log::info('Status updated: Transaction_ID=' . $id . ', New_Status=' . $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editTransaction($id)
    {
        try {
            $feeTransaction = FeeTransaction::findOrFail($id);
            $student = collect(json_decode(file_get_contents(storage_path('app/students.json')), true))
                ->firstWhere('Student_ID', (int)$feeTransaction->student_id);

            if (!$student) {
                Log::warning('Student not found for transaction: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Student data not found for this transaction.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'transaction' => $feeTransaction,
                'student' => $student,
            ]);
        } catch (\Exception $e) {
            Log::error('Edit transaction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateTransaction(Request $request, $id)
    {
        $executed = RateLimiter::attempt(
            'update-transaction:' . $request->ip(),
            5,
            function () use ($request, $id) {
                $validator = Validator::make($request->all(), [
                    'transaction_type' => 'required|in:fee_payment,security_deposit,security_refund',
                    'amount' => 'required|numeric|in:500,1000,1500,2000,2500,3000,3500,4000',
                    'fee_month' => 'nullable|date_format:Y-m',
                    'payment_method' => 'required|in:UPI QR Code,Bank Transfer,Other',
                    'reference_no' => 'nullable|numeric',
                    'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                    'note' => 'nullable|string|max:1000',
                    'transaction_date' => 'required|date|before_or_equal:today',
                ]);

                if ($validator->fails()) {
                    Log::warning('Update transaction validation failed: ' . json_encode($validator->errors()->all()));
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed: ' . $validator->errors()->first(),
                    ], 422);
                }

                try {
                    DB::beginTransaction();

                    $feeTransaction = FeeTransaction::findOrFail($id);
                    $student = collect(json_decode(file_get_contents(storage_path('app/students.json')), true))
                        ->firstWhere('Student_ID', (int)$feeTransaction->student_id);

                    if (!$student) {
                        Log::warning('Student not found for transaction: ' . $id);
                        throw new \Exception('Student data not found.');
                    }

                    // Duplicate check for tuition fees
                    if ($request->transaction_type === 'fee_payment' && $request->fee_month) {
                        $existing = FeeTransaction::where('student_id', $feeTransaction->student_id)
                            ->where('transaction_type', 'fee_payment')
                            ->where('fee_month', $request->fee_month)
                            ->whereIn('status', ['paid', 'pending'])
                            ->where('id', '!=', $id)
                            ->exists();
                        if ($existing) {
                            Log::warning('Duplicate tuition fee submission detected: Student_ID=' . $feeTransaction->student_id . ', Month=' . $request->fee_month);
                            throw new \Exception('Tuition fee for this month has already been submitted or paid.');
                        }
                    }

                    // Handle attachment update
                    $attachmentPath = $feeTransaction->attachment_path;
                    if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                        // Delete old attachment if exists
                        if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                            Storage::disk('public')->delete($attachmentPath);
                        }
                        $attachmentExtension = $request->file('attachment')->getClientOriginalExtension();
                        $timestamp = date('Ymd_His');
                        $fileName = "{$feeTransaction->student_id}_{$request->transaction_type}_{$timestamp}.{$attachmentExtension}";
                        $attachmentPath = $request->file('attachment')->storeAs('fee_attachments', $fileName, 'public');

                        // Verify file was stored
                        if (!Storage::disk('public')->exists($attachmentPath)) {
                            Log::error('Failed to store attachment: ' . $attachmentPath);
                            throw new \Exception('Failed to store attachment.');
                        }
                    }

                    // Auto-set direction
                    $direction = ($request->transaction_type === 'security_refund') ? 'debit' : 'credit';

                    // Auto-set description
                    $description = '';
                    if ($request->transaction_type === 'fee_payment') {
                        $monthYear = $request->fee_month ? Carbon::createFromFormat('Y-m', $request->fee_month)->format('F Y') : '';
                        $description = "Tuition Fee for {$monthYear}";
                    } elseif ($request->transaction_type === 'security_deposit') {
                        $description = 'Library Security Deposit';
                    } elseif ($request->transaction_type === 'security_refund') {
                        $description = 'Security Refund';
                    }

                    // Set amount for fee_payment
                    $amount = $request->amount;
                    if ($request->transaction_type === 'fee_payment') {
                        $category = $student['Category'] ?? '';
                        $isUnreserved = $category === 'Unreserved' || $category === 'UR';
                        $amount = $isUnreserved ? 1000 : 500;
                    }

                    $feeTransaction->update([
                        'transaction_type' => $request->transaction_type,
                        'amount' => $amount,
                        'fee_month' => $request->fee_month,
                        'payment_method' => $request->payment_method,
                        'reference_no' => $request->reference_no,
                        'attachment_path' => $attachmentPath,
                        'note' => $request->note ?? '',
                        'transaction_date' => $request->transaction_date,
                        'direction' => $direction,
                        'description' => $description,
                    ]);

                    // Regenerate PDF
                    $logoPath = public_path('images/logo-rmb.png');
                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
                    $logoBase64 = $logoData ? 'data:image/png;base64,' . $logoData : 'https://via.placeholder.com/120x60?text=SNTCSSC+Logo';

                    $pdf = Pdf::loadView('web.fee.confirmation', compact('feeTransaction', 'student', 'logoBase64'));
                    // $pdfPath = "fee_confirmations/{$feeTransaction->student_id}_{$id}_confirmation.pdf";
                    $timestamp = date('Ymd_His');
                    $pdfPath = "fee_confirmations/{$feeTransaction->student_id}_{$id}_{$timestamp}_confirmation.pdf";
                    Storage::disk('public')->put($pdfPath, $pdf->output());

                    DB::commit();

                    Log::info('Transaction updated: Transaction_ID=' . $id);

                    return response()->json([
                        'success' => true,
                        'message' => 'Transaction updated successfully.',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Update transaction error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to update transaction: ' . $e->getMessage(),
                    ], 500);
                }
            },
            60
        );

        if (!$executed) {
            Log::warning('Rate limit exceeded for IP: ' . $request->ip());
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please try again later.',
            ], 429);
        }
    }

    public function export(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string|max:255',
                'export' => 'nullable|string|in:excel',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|in:10,25,50,100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $query = FeeTransaction::query()
                ->where('status', '!=', 'rejected');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhere('application_number', 'like', "%{$search}%");
                });
            }

            if ($request->has('export') && $request->export === 'excel') {
                try {
                    $exportData = $query->get();

                    if ($exportData->isEmpty()) {
                        return redirect()->back()->with('error', 'No records available to export.');
                    }

                    return Excel::download(
                        new FeeExport($exportData),
                        'fee_transactions_' . date('Ymd_His') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Log::error('Excel Export failed: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Unable to export to Excel. Please try again.');
                }
            }

            $perPage = $request->input('per_page', 25);
            $feeTransactions = $query->paginate($perPage);

            return view('web.fee.export', [
                'feeTransactions' => $feeTransactions,
                'search' => $request->input('search'),
                'per_page' => $perPage,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching fee data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching data. Please try again later.');
        }
    }
}