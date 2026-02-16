<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'firstname',
        'lastname',
        'email',
        'amount',
        'currency',
        'payment_method',
        'status',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes pour faciliter les requêtes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}