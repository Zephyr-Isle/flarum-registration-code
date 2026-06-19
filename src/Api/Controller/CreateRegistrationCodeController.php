<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Illuminate\Validation\ValidationException;
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
            throw ValidationException::withMessages(['username' => 'Username is required.']);
        }

        if ($code === '') {
            throw ValidationException::withMessages(['code' => 'Registration code is required.']);
        }

        if (RegistrationCode::query()->where('code', $code)->exists()) {
            throw ValidationException::withMessages(['code' => 'This registration code already exists.']);
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
            'message' => 'Registration code created.',
        ], 201);
    }
}
