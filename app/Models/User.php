<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($user) {
            if(! $user->isForceDeleting()) {
                $user->anonymize();
            }

            if($user->isForceDeleting() && $user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

        });
    }

    public function anonymize() {
        if($this->profile_image) {
            Storage::disk('public')->delete($this->profile_image);
        }

        $this->update([
            'name' => 'Deleted Member',
            'email' => 'deleted+' . $this->id . '@email.com',
            'role' => 'Former Member',
            'profile_image' => null,
            'password' => bcrypt(str()->random(40)),
        ]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'role',
        'gender',
        'location',
        'date_of_birth'
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


    public function getProfileSummaryAttribute()
    {
        $age = Carbon::parse($this->date_of_birth)->age;

        $parts = array_filter([
            $this->role,
            $age,
            $this->gender,
        ]);

        $summary = implode(', ', $parts);

        if ($this->location) {
            $summary .= ($summary ? ', from ' : "") . $this->location;
        }

        return $summary ?: 'No information provided';
    }

    public function getDisplayNameAttribute()
    {
        if ($this->trashed()) {
                return 'Deleted Member ' . $this->id;
            }

        return $this->name;
    }

    public function getProfileImageUrlAttribute()
    {
    return $this->profile_image
        ? asset('storage/' . $this->profile_image)
        : asset('images/default-avatar.png');
    }
}
