<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperUserProfile
 */
class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'phone',
        'city',
        'street',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
