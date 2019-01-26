<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Resource;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;
use Symfony\Component\Process\Process;

/**
 * Class Dig
 *
 * @package Chanshige\WhoisProxy\Resource
 */
final class Dig
{
    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $process = new Process($this->cmd($request->getAttribute('domain', '')));
        $process->run();

        if (!$process->isSuccessful()) {
            return $response->withJson(
                'request failed.',
                StatusCode::HTTP_FORBIDDEN,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }

        return $response->withJson(
            $this->convert($process->getOutput()),
            StatusCode::HTTP_OK,
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
    }

    /**
     * @param string $domain
     * @return array
     */
    private function cmd(string $domain): array
    {
        return [
            '/usr/bin/dig',
            '@8.8.8.8',
            $domain,
            'any',
            '+noall',
            '+ans',
            '+authority',
            '+time=1'
        ];
    }

    /**
     * @param string $data
     * @return array
     */
    private function convert(string $data): array
    {
        $result = [];
        foreach ((array)explode("\n", $data) as $value) {
            if (strlen($value) === 0) {
                continue;
            }
            $result[] = trim(str_replace(["\t", '"'], ' ', $value));
        }

        return $result;
    }
}
