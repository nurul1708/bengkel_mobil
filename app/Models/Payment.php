<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    private const METHOD_LABELS = [
        'cash' => 'Tunai',
        'transfer' => 'Transfer Bank',
        'midtrans' => 'Midtrans',
        'qris' => 'QRIS',
        'gopay' => 'GoPay',
        'shopeepay' => 'ShopeePay',
        'credit_card' => 'Kartu Kredit/Debit',
        'bca_va' => 'BCA Virtual Account',
        'bni_va' => 'BNI Virtual Account',
        'bri_va' => 'BRI Virtual Account',
        'permata_va' => 'Permata Virtual Account',
        'other_va' => 'Virtual Account',
        'mandiri_bill' => 'Mandiri Bill Payment',
        'indomaret' => 'Indomaret',
        'alfamart' => 'Alfamart',
        'akulaku' => 'Akulaku',
    ];

    protected $table = 'payments';

    protected $fillable = [
        'transaction_id',
        'payment_date',
        'amount_paid',
        'payment_method',
        'payment_status'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => '<span class="badge bg-success">Lunas</span>',
            'partial' => '<span class="badge bg-warning">Sebagian</span>',
            default => '<span class="badge bg-danger">Belum Bayar</span>',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::METHOD_LABELS[$this->payment_method]
            ?? str($this->payment_method)->replace('_', ' ')->title()->toString();
    }
}
