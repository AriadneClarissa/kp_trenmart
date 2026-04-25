<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends \Illuminate\Foundation\Auth\User
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',               // Role:'admin' atau 'customer'
        'customer_type',      // Jenis Customer: 'regular' atau 'langganan'
        'is_approved',        // Diacc / tidak: true (1) atau false (0)
        'phone_number',
        'home_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean', // Memastikan data dibaca sebagai true/false
            'customer_type' => 'string',
            'phone_number' => 'string',
            'home_address' => 'string',
        ];
    }

    /**
     * Helper Methods
     * Tambahkan fungsi-fungsi di bawah ini untuk mempermudah pengecekan di Controller/View
     */

    // Cek apakah user adalah Admin
    public function isAdmin()
    {
    // Sesuaikan dengan kolom di database kamu (tadi di foto kolomnya bernama 'role')
    return $this->role === 'admin';
    }

    // Cek apakah user adalah Customer Langganan yang sudah disetujui Admin
    public function isVerifiedMember(): bool
    {
        return $this->customer_type === 'langganan' && $this->is_approved === true;
    }

    // Cek apakah user masih menunggu persetujuan (untuk info di dashboard)
    public function isPendingMember(): bool
    {
        return $this->customer_type === 'langganan' && $this->is_approved === false;
    }
}