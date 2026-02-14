<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Payments\PaymentProvider;
use App\Enums\Payments\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_email',
        'customer_phone',
        'customer_name',
        'customer_city',
        'customer_street',
        'customer_house',
        'total_amount',
        'comment',
        'payment_provider',
        'payment_status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_provider' => PaymentProvider::class,
        'payment_status' => PaymentStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
