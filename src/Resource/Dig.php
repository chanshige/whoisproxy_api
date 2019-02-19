<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Resource;

use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
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
            return $response->withHalJson(
                'request failed.',
                ['self' => ["href" => $request->getUri()->getPath()]],
                StatusCode::HTTP_FORBIDDEN
            );
        }

        return $response->withHalJson(
            $this->convert($process->getOutput()),
            ['self' => ["href" => $request->getUri()->getPath()]],
            StatusCode::HTTP_OK
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
        // <<>> DiG...は不要
        array_shift($result);

        return $result;
    }
}
