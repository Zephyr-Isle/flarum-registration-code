<?php

namespace Zephyrisle\RegistrationCode\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zephyrisle\RegistrationCode\RegistrationCode;

class ExportRegistrationCodesController extends AbstractRegistrationCodeController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->actor($request);

        $rows = RegistrationCode::query()->with('usedByUser')->orderBy('id')->get();
        $lines = ['username,code,status,used_by,used_at,created_at'];

        foreach ($rows as $row) {
            $lines[] = implode(',', [
                $this->escape($row->username),
                $this->escape($row->code),
                $this->escape($row->used_by || $row->used_at ? 'used' : 'unused'),
                $this->escape($row->usedByUser ? $row->usedByUser->username : ''),
                $this->escape($row->used_at ? $row->used_at->toAtomString() : ''),
                $this->escape($row->created_at ? $row->created_at->toAtomString() : ''),
            ]);
        }

        return new JsonResponse([
            'filename' => 'registration-codes-' . date('Ymd-His') . '.csv',
            'content' => implode("\r\n", $lines),
        ]);
    }

    private function escape(string $value): string
    {
        return '"' . str_replace('"', '""', $value) . '"';
    }
}
