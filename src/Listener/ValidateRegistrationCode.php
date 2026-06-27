<?php

namespace Zephyrisle\RegistrationCode\Listener;

use Carbon\Carbon;
use Flarum\Foundation\ValidationException;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Event\Saving;
use Flarum\User\User;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Zephyrisle\RegistrationCode\RegistrationCode;

class ValidateRegistrationCode
{
    public function __construct(
        protected Repository $config,
        protected ConnectionInterface $db,
        protected SettingsRepositoryInterface $settings
    ) {
    }

    public function handle(Saving $event): void
    {
        if ($event->user->exists || $event->actor->isAdmin()) {
            return;
        }

        $enabled = $this->settings->get('zephyrisle-registration-code.enabled', true);

        if (! (bool) $enabled) {
            return;
        }

        $attributes = (array) Arr::get($event->data, 'attributes', []);
        $username = trim((string) Arr::get($attributes, 'username', $event->user->username ?? ''));
        $registrationCode = trim((string) Arr::get($attributes, 'registrationCode', ''));

        if ($registrationCode === '') {
            throw new ValidationException([
                'registrationCode' => app('translator')->trans('zephyrisle-registration-code.api.errors.code_required'),
            ]);
        }

        $record = $this->db->transaction(function () use ($registrationCode, $username, $event) {
            $record = RegistrationCode::query()->where('code', $registrationCode)->lockForUpdate()->first();

            if (! $record) {
                throw new ValidationException([
                    'registrationCode' => app('translator')->trans('zephyrisle-registration-code.api.errors.code_invalid'),
                ]);
            }

            if ($record->used_by || $record->used_at) {
                throw new ValidationException([
                    'registrationCode' => app('translator')->trans('zephyrisle-registration-code.api.errors.code_used'),
                ]);
            }

            if (strcasecmp($record->username, $username) !== 0) {
                throw new ValidationException([
                    'username' => app('translator')->trans('zephyrisle-registration-code.api.errors.username_mismatch'),
                ]);
            }

            $event->user->email = $this->autoEmail($username);
            $event->user->is_email_confirmed = true;
            $event->user->registration_code = $record->code;

            $event->user->afterSave(function (User $user) use ($record) {
                $record->used_by = $user->id;
                $record->used_at = Carbon::now();
                $record->save();
            });

            return $record;
        });
    }

    private function autoEmail(string $username): string
    {
        $forumUrl = (string) $this->config->get('url', '');
        $domain = parse_url($forumUrl, PHP_URL_HOST) ?: 'forum.local';

        return $username . '@' . $domain;
    }
}
