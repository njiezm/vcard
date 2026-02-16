<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'slug',
        'admin_code',
        'profession',
        'company',
        'email',
        'phone',
        'phone_secondary',
        'website',
        'linkedin',
        'twitter',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'address',
        'city',
        'country',
        'postal_code',
        'bio',
        'photo',
        'cover_photo',
        'theme',
        'is_active',
        'views',
        'downloads',
        'last_accessed',
        'social_links',
        'custom_fields',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'social_links' => 'array',
        'custom_fields' => 'array',
        'is_active' => 'boolean',
        'last_accessed' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'vcard_url',
        'dashboard_url',
    ];

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getVcardUrlAttribute()
    {
        return route('vcard.show', $this->slug);
    }

    public function getDashboardUrlAttribute()
{
    return route('customer.dashboard', [
        'adminCode' => $this->admin_code,
        'slug' => $this->slug,
    ]);
}


    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    // Methods
    public function uploadPhoto($photo)
    {
        if ($this->photo) {
            Storage::disk('public')->delete($this->photo);
        }
        
        $path = $photo->store('photos', 'public');
        $this->update(['photo' => $path]);
    }

    public function uploadCoverPhoto($coverPhoto)
    {
        if ($this->cover_photo) {
            Storage::disk('public')->delete($this->cover_photo);
        }
        
        $path = $coverPhoto->store('covers', 'public');
        $this->update(['cover_photo' => $path]);
    }

    public function generateVCard()
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "FN:" . $this->full_name . "\n";
        $vcard .= "N:" . $this->lastname . ";" . $this->firstname . ";;;\n";

        if ($this->profession) {
            $vcard .= "TITLE:" . $this->profession . "\n";
        }

        if ($this->company) {
            $vcard .= "ORG:" . $this->company . "\n";
        }

        if ($this->email) {
            $vcard .= "EMAIL;TYPE=INTERNET:" . $this->email . "\n";
        }

        if ($this->phone) {
            $vcard .= "TEL;TYPE=CELL,VOICE:" . $this->phone . "\n";
        }

        if ($this->phone_secondary) {
            $vcard .= "TEL;TYPE=WORK,VOICE:" . $this->phone_secondary . "\n";
        }

        if ($this->website) {
            $vcard .= "URL:" . $this->website . "\n";
        }

        if ($this->address) {
            $vcard .= "ADR;TYPE=WORK:;;" . $this->address . ";" . $this->city . ";;" . $this->postal_code . ";" . $this->country . "\n";
        }

        if ($this->bio) {
            $vcard .= "NOTE:" . str_replace("\n", "\\n", $this->bio) . "\n";
        }

        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            $photoData = base64_encode(Storage::disk('public')->get($this->photo));
            $vcard .= "PHOTO;ENCODING=b;TYPE=JPEG:" . $photoData . "\n";
        }

        $vcard .= "REV:" . now()->format('Ymd\THis\Z') . "\n";
        $vcard .= "END:VCARD";

        return $vcard;
    }

    public function incrementViews()
    {
        $this->increment('views');
        $this->update(['last_accessed' => now()]);
    }

    public function incrementDownloads()
    {
        $this->increment('downloads');
    }

    public function getSocialLinks()
    {
        $links = [];

        $socialPlatforms = [
            'linkedin' => 'https://linkedin.com/in/',
            'twitter' => 'https://twitter.com/',
            'facebook' => 'https://facebook.com/',
            'instagram' => 'https://instagram.com/',
            'youtube' => 'https://youtube.com/',
            'tiktok' => 'https://tiktok.com/@',
        ];

        foreach ($socialPlatforms as $platform => $baseUrl) {
            if ($this->$platform) {
                $links[$platform] = [
                    'url' => $baseUrl . ltrim($this->$platform, '@'),
                    'username' => $this->$platform,
                    'icon' => $this->getSocialIcon($platform),
                    'color' => $this->getSocialColor($platform),
                ];
            }
        }

        return $links;
    }

    private function getSocialIcon($platform)
    {
        $icons = [
            'linkedin' => 'fab fa-linkedin',
            'twitter' => 'fab fa-twitter',
            'facebook' => 'fab fa-facebook',
            'instagram' => 'fab fa-instagram',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
        ];

        return $icons[$platform] ?? 'fas fa-link';
    }

    private function getSocialColor($platform)
    {
        $colors = [
            'linkedin' => '#0077B5',
            'twitter' => '#1DA1F2',
            'facebook' => '#1877F2',
            'instagram' => '#E4405F',
            'youtube' => '#FF0000',
            'tiktok' => '#000000',
        ];

        return $colors[$platform] ?? '#666666';
    }
}