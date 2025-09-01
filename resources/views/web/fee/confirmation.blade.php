<div style="font-family: 'Segoe UI', Arial, sans-serif; padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 6px; max-width: 600px; margin: 0 auto; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 15px;">
        <img src="{{ $logoBase64 }}" alt="Logo" style="width: 80px; height: auto; display: block; margin: 0 auto 8px;">
        <h1 style="color: #2c3e50; margin: 0; font-size: 15px;">Satyendra Nath Tagore Civil Services Study Centre (SNTCSSC)</h1>
        <h2 style="color: #0010f5; margin: 5px 0 0; font-size: 18px; font-weight: 600;">Payment Receipt</h2>
    </div>
    
    <!-- Receipt Details -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td colspan="2" style="padding: 8px 12px; background: #f8f9fa; border: 1px solid #ddd; font-weight: 600; color: #495057;">Transaction Details</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; width: 40%; font-weight: 600;">Receipt No:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->id }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Student ID:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->student_id }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Enrolled:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->programme_name }} {{ $feeTransaction->batch }} {{__('Batch')}}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Section:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->section }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Student Name:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->first_name }} {{ $feeTransaction->last_name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Purpose:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ ucfirst(str_replace('_', ' ', $feeTransaction->description)) }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Amount:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd; color: #4627f3; font-weight: 600;">Rs. {{ number_format($feeTransaction->amount, 2) }}</td>
        </tr>
        @if($feeTransaction->fee_month)
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Fee Month:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->fee_month ? \Carbon\Carbon::parse($feeTransaction->fee_month)->format('F Y') : 'N/A' }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Transaction Ref. No.:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->reference_no ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Transaction Date:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">{{ $feeTransaction->transaction_date ? $feeTransaction->transaction_date->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; border: 1px solid #ddd; font-weight: 600;">Status:</td>
            <td style="padding: 8px 12px; border: 1px solid #ddd;">
                <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                    {{ ucfirst($feeTransaction->status) }}
                </span>
            </td>
        </tr>
    </table>
    
    <!-- Verification & Footer -->
    <div style="display: none; justify-content: space-between; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 4px; margin-bottom: 10px;">
        <div>
            <p style="margin: 0; font-size: 12px; color: #6c757d;">Verification Code:</p>
            <p style="margin: 0; font-family: monospace; font-size: 14px; color: #28a745;">{{ $feeTransaction->id }}</p>
        </div>
        <div style="width: 60px; height: 60px; background: #ddd; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 10px; color: #6c757d;">
            QR
            <img src="" alt="QR Code" style="width: 50px; height: 50px; border-radius: 4px;">
        </div>
    </div>
    
    <div style="text-align: center; border-top: 1px solid #ddd; padding-top: 10px; color: #6c757d; font-size: 11px;">
        <p style="margin: 0 0 5px;">This is a computer-generated receipt.</p>
        <p style="margin: 0;">For inquiries: iascoaching.sntcssc@gmail.com | (+91) 90518 29290</p>
        <p style="margin: 5px 0 0;">© {{ date('Y') }} SNTCSSC</p>
    </div>
</div>