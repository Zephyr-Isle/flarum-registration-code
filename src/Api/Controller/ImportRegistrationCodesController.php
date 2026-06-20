<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Flarum\User\User;
use Illuminate\Validation\ValidationException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zephyrisle\RegistrationCode\RegistrationCode;

class ImportRegistrationCodesController extends AbstractRegistrationCodeController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->actor($request);

        $content = trim((string) $this->input($request, 'content', ''));

        if ($content === '') {
            throw ValidationException::withMessages(['content' => 'Please provide CSV content to import.']);
        }

        $created = 0;
        $skipped = 0;

        foreach (preg_split('/\r\n|\r|\n/', $content) ?: [] as $index => $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $columns = str_getcsv($line);
            $username = isset($columns[0]) ? trim((string) $columns[0]) : '';
            $code = isset($columns[1]) ? trim((string) $columns[1]) : '';

            if ($index === 0 && strtolower($username) === 'username' && strtolower($code) === 'code') {
                continue;
            }

            if ($username === '' || $code === '' || RegistrationCode::query()->where('code', $code)->exists()) {
                $skipped++;
                continue;
            }

            if (User::query()->where('username', $username)->exists()) {
                $skipped++;
                continue;
            }

            RegistrationCode::query()->create([
                'username' => $username,
                'code' => $code,
            ]);

            $created++;
        }

        return new JsonResponse([
            'message' => 'Import complete.',
            'summary' => [
                'created' => $created,
                'skipped' => $skipped,
            ],
        ]);
    }
}
