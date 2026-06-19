<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zephyrisle\RegistrationCode\RegistrationCode;

class DeleteRegistrationCodeController extends AbstractRegistrationCodeController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->actor($request);

        $id = (int) $request->getAttribute('id');
        $record = RegistrationCode::query()->findOrFail($id);
        $record->delete();

        return new JsonResponse(['message' => 'Registration code deleted.']);
    }
}
