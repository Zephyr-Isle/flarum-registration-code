<?php

namespace Zephyrisle\RegistrationCode\Api;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;

class UserAttributes
{
    public function __invoke(UserSerializer $serializer, User $user): array
    {
        $actor = $serializer->getActor();

        if ($actor->isAdmin() || $actor->id === $user->id) {
            return [
                'registrationCode' => $user->registration_code,
            ];
        }

        return [];
    }
}
