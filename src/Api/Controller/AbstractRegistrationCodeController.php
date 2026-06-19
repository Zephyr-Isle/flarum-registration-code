<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractRegistrationCodeController
{
    protected function actor(ServerRequestInterface $request): User
    {
        $actor = RequestUtil::getActor($request);

        if (! $actor->isAdmin()) {
            throw new PermissionDeniedException();
        }

        return $actor;
    }

    protected function input(ServerRequestInterface $request, string $key, $default = null)
    {
        return Arr::get((array) $request->getParsedBody(), $key, $default);
    }
}
