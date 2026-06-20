<?php

namespace Zephyrisle\RegistrationCode\Listener;

use Carbon\Carbon;
use Flarum\User\Event\Saving;
use Flarum\User\User;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Zephyrisle\RegistrationCode\RegistrationCode;

class ValidateRegistrationCode
{
    public function __construct(
        protected Repository $config
    ) {
    }

    public function handle(Saving $event): void
    {
        if ($event->user->exists || $event->actor->isAdmin()) {
            return;
        }

        $attributes = (array) Arr::get($event->data, 'attributes', []);
        $username = trim((string) Arr::get($attributes, 'username', $event->user->username ?? ''));
        $registrationCode = trim((string) Arr::get($attributes, 'registrationCode', ''));

        if ($registrationCode === '') {
            throw ValidationException::withMessages(['registrationCode' => 'Registration code is required.']);
        }

        $record = RegistrationCode::query()->where('code', $registrationCode)->lockForUpdate()->first();

        if (! $record) {
            throw ValidationException::withMessages(['registrationCode' => 'The registration code is invalid.']);
        }

        if ($record->used_by || $record->used_at) {
            throw ValidationException::withMessages(['registrationCode' => 'This registration code has already been used.']);
        }

        if (strcasecmp($record->username, $username) !== 0) {
            throw ValidationException::withMessages(['username' => 'This registration code is assigned to another username.']);
        }

        $event->user->email = $this->autoEmail($username);
        $event->user->is_email_confirmed = true;
        $event->user->registration_code = $record->code;

        $event->user->afterSave(function (User $user) use ($record) {
            $record->used_by = $user->id;
            $record->used_at = Carbon::now();
            $record->save();
        });
    }

    private function autoEmail(string $username): string
    {
        $forumUrl = (string) $this->config->get('url', '');
        $domain = parse_url($forumUrl, PHP_URL_HOST) ?: 'forum.local';

        return $username . '@' . $domain;
    }
}
