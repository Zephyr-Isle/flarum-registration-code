<?php

use Flarum\Database\Migration;

return Migration::addColumns('users', [
    'registration_code' => ['string', 'length' => 191, 'nullable' => true, 'after' => 'email'],
]);
