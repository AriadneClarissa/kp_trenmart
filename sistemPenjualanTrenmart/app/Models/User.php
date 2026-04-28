<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends \Illuminate\Foundation\Auth\User
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',           
        'customer_type',  
        'is_approved',    
        'phone_number',
        'home_address',   
        'google_id',
        'organization_name', 
        'organization_type', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * --- HELPER METHODS ---
     */

    // 1. Cek Admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // 2. Cek apakah user adalah Customer Langganan yang SUDAH DISETUJUI
    public function isVerifiedMember(): bool
    {
        return $this->customer_type === 'langganan' && $this->is_approved === true;
    }

    // 3. Cek apakah user adalah Langganan yang MASIH PENDING
    // Tambahkan pengecekan !isAdmin agar admin tidak dianggap pending member
    public function isPendingMember(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        return $this->customer_type === 'langganan' && $this->is_approved === false;
    }
}