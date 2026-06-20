<?php

namespace Zephyrisle\RegistrationCode;

use Flarum\Api\Resource\UserResource;
use Flarum\Api\Schema;
use Flarum\Extend;
use Flarum\User\Event\Saving;
use Zephyrisle\RegistrationCode\Api\Controller\CreateRegistrationCodeController;
use Zephyrisle\RegistrationCode\Api\Controller\DeleteRegistrationCodeController;
use Zephyrisle\RegistrationCode\Api\Controller\ExportRegistrationCodesController;
use Zephyrisle\RegistrationCode\Api\Controller\ImportRegistrationCodesController;
use Zephyrisle\RegistrationCode\Api\Controller\ListRegistrationCodesController;
use Zephyrisle\RegistrationCode\Listener\ValidateRegistrationCode;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Routes('api'))
        ->get('/registration-codes', 'zephyrisle.registration-codes.index', ListRegistrationCodesController::class)
        ->post('/registration-codes', 'zephyrisle.registration-codes.create', CreateRegistrationCodeController::class)
        ->delete('/registration-codes/{id}', 'zephyrisle.registration-codes.delete', DeleteRegistrationCodeController::class)
        ->post('/registration-codes/import', 'zephyrisle.registration-codes.import', ImportRegistrationCodesController::class)
        ->get('/registration-codes/export', 'zephyrisle.registration-codes.export', ExportRegistrationCodesController::class),

    (new Extend\Event())
        ->listen(Saving::class, ValidateRegistrationCode::class),

    (new Extend\ApiResource(UserResource::class))
        ->fields(fn () => [
            Schema\Str::make('registrationCode'),
        ]),

    (new Extend\Settings())
        ->default('zephyrisle-registration-code.enabled', true),
];
