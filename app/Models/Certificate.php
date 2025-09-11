<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'type',
        'issued_at',
        'is_verified',
        'course_rating',
        'download_count',
        'verification_code'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'is_verified' => 'boolean',
        'course_rating' => 'decimal:1',
        'download_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    const TYPE_COMPLETION = 'completion';
    const TYPE_ACHIEVEMENT = 'achievement';
    const TYPE_PARTICIPATION = 'participation';

    /**
     * Get the user that owns the certificate
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course for this certificate
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Generate a unique certificate number
     */
    public static function generateCertificateNumber()
    {
        do {
            $number = 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');
        } while (self::where('certificate_number', $number)->exists());

        return $number;
    }

    /**
     * Generate a verification code
     */
    public static function generateVerificationCode()
    {
        return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Verify the certificate
     */
    public function verify()
    {
        $this->update(['is_verified' => true]);
    }

    /**
     * Scope for verified certificates
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for unverified certificates
     */
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    /**
     * Scope for certificate type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for user's certificates
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get certificate types
     */
    public static function getTypes()
    {
        return [
            self::TYPE_COMPLETION => 'Completion Certificate',
            self::TYPE_ACHIEVEMENT => 'Achievement Certificate',
            self::TYPE_PARTICIPATION => 'Participation Certificate'
        ];
    }

    /**
     * Get the certificate URL for verification
     */
    public function getVerificationUrlAttribute()
    {
        return route('certificates.verify', $this->certificate_number);
    }

    /**
     * Get the certificate download URL
     */
    public function getDownloadUrlAttribute()
    {
        return route('student.certificate.download', $this);
    }
}
