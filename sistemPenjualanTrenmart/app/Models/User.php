<?php

namespace App\Models;

use App\Notifications\TrenmartResetPasswordNotification;
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
        'is_active',
        'kd_pelanggan',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * --- HELPER METHODS ---
     */

    // 1. Cek Admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'owner';
    }

    // 1b. Cek Pemilik (owner)
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isCashier(): bool
    {
        return $this->role === 'kasir';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isInternalStaff(): bool
    {
        return $this->isAdmin() || $this->isCashier();
    }

    public function isActive(): bool
    {
        return $this->is_active !== false;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'owner' => 'Pemilik',
            'admin' => 'Administrator',
            'kasir' => 'Kasir',
            'customer' => $this->customer_type === 'langganan' ? 'Pelanggan Langganan' : 'Pelanggan Umum',
            default => ucfirst((string) $this->role),
        };
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
        // Sistem sekarang tidak menggunakan mekanisme persetujuan manual.
        // Semua akun (terutama yang dibuat oleh admin atau pendaftaran mandiri)
        // dianggap langsung aktif, jadi tidak ada lagi status 'pending'.
        return false;
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new TrenmartResetPasswordNotification($token));
    }

    
}