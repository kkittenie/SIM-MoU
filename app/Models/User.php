<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role',
        'program_keahlian_id',
        'status',
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
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBK(): bool
    {
        return $this->role === 'bk';
    }

    public function isBKK(): bool
    {
        return $this->role === 'bkk';
    }

    public function isAdminJurusan(): bool
    {
        return $this->role === 'admin_jurusan';
    }

    public function programKeahlian()
    {
        return $this->belongsTo(ProgramKeahlian::class, 'program_keahlian_id');
    }

    /**
     * Get the assigned Program Keahlian ID for Admin Jurusan.
     */
    public function getAssignedProgramKeahlianId(): ?int
    {
        if ($this->program_keahlian_id) {
            return (int) $this->program_keahlian_id;
        }

        if ($this->isAdminJurusan()) {
            $firstId = ProgramKeahlian::orderBy('id')->value('id');
            return $firstId ? (int) $firstId : null;
        }

        return null;
    }
}
