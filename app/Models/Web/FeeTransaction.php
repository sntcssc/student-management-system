<?php

// app/Models/FeeTransaction.php (Updated with transaction_date)
namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'programme_name',
        'batch',
        'application_number',
        'roll_no',
        'student_id',
        'section',
        'first_name',
        'last_name',
        'dob',
        'email',
        'mobile_no',
        'whatsapp_no',
        'gender',
        'category',
        'transaction_type', // e.g., fee_payment, security_deposit, security_refund
        'direction', // credit / debit
        'amount',
        'fee_month', // YYYY-MM format
        'description',
        'payment_method',
        'reference_no',
        'attachment_path',
        'note',
        'performed_by', // students or admin
        'remarks',
        'status', // pending, paid, rejected
        'transaction_date',
    ];

    protected $casts = [
        'dob' => 'date',
        'fee_month' => 'date:Y-m',
        'transaction_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}