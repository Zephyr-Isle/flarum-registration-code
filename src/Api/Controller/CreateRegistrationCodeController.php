<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Flarum\Foundation\ValidationException;
use Flarum\User\User;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zephyrisle\RegistrationCode\RegistrationCode;

class CreateRegistrationCodeController extends AbstractRegistrationCodeController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->actor($request);

        $username = trim((string) $this->input($request, 'username', ''));
        $code = trim((string) $this->input($request, 'code', ''));

        if ($username === '') {
            throw new ValidationException([
                'username' => app('translator')->trans('zephyrisle-registration-code.api.errors.username_required'),
            ]);
        }

        if ($code === '') {
            throw new ValidationException([
                'code' => app('translator')->trans('zephyrisle-registration-code.api.errors.code_required'),
            ]);
        }

        if (RegistrationCode::query()->where('code', $code)->exists()) {
            throw new ValidationException([
                'code' => app('translator')->trans('zephyrisle-registration-code.api.errors.code_exists'),
            ]);
        }

        if (User::query()->where('username', $username)->exists()) {
            throw new ValidationException([
                'username' => app('translator')->trans('zephyrisle-registration-code.api.errors.username_taken'),
            ]);
        }

        $record = RegistrationCode::query()->create([
            'username' => $username,
            'code' => $code,
        ]);

        return new JsonResponse([
            'data' => [
                'id' => $record->id,
                'username' => $record->username,
                'code' => $record->code,
                'used' => false,
                'usedBy' => null,
                'usedAt' => null,
                'createdAt' => $record->created_at ? $record->created_at->toAtomString() : null,
            ],
            'message' => app('translator')->trans('zephyrisle-registration-code.api.messages.code_created'),
        ], 201);
    }
}
