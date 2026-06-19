<?php

namespace Zephyrisle\RegistrationCode;

use Flarum\Database\AbstractModel;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationCode extends AbstractModel
{
    protected $table = 'registration_codes';

    public $timestamps = true;

    protected $fillable = [
        'username',
        'code',
        'used_by',
        'used_at',
    ];

    protected $dates = [
        'used_at',
        'created_at',
        'updated_at',
    ];

    public function usedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
