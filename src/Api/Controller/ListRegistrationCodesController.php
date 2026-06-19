<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zephyrisle\RegistrationCode\RegistrationCode;

class ListRegistrationCodesController extends AbstractRegistrationCodeController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->actor($request);

        $records = RegistrationCode::query()
            ->with('usedByUser')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (RegistrationCode $record) {
                return [
                    'id' => $record->id,
                    'username' => $record->username,
                    'code' => $record->code,
                    'used' => (bool) ($record->used_by || $record->used_at),
                    'usedBy' => $record->usedByUser ? $record->usedByUser->username : null,
                    'usedAt' => $record->used_at ? $record->used_at->toAtomString() : null,
                    'createdAt' => $record->created_at ? $record->created_at->toAtomString() : null,
                ];
            })
            ->values();

        return new JsonResponse(['data' => $records]);
    }
}
