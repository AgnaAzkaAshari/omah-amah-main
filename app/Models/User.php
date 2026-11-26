<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',             // Tambahan: Untuk membedakan admin/penghuni
        'phone',            // Tambahan: Nomor telepon
        'gender',           // Tambahan: Jenis kelamin
        'birth_date',       // Tambahan: Tanggal lahir
        'occupation',       // Tambahan: Pekerjaan
        'domicile_address', // Tambahan: Alamat domisili (sesuai DB)
        'profile_photo',    // Tambahan: Foto profil
        'photo',            // Tambahan: Cadangan foto profil (sesuai DB)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}