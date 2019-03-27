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
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $process = new Process($this->cmd(
            $request->getAttribute('domain'),
            $this->filterQueryType(($request->getAttribute('option') ?? 'any'))
        ));
        $process->run();

        if (!$process->isSuccessful()) {
            return $response->withHalJson(
                'request failed.',
                ['self' => ["href" => $request->getUri()->getPath()]],
                StatusCode::HTTP_FORBIDDEN
            );
        }

        $result = iterator_to_array($this->convert($process->getOutput()));
        array_shift($result);

        return $response->withHalJson(
            $result,
            ['self' => ["href" => $request->getUri()->getPath()]],
            StatusCode::HTTP_OK
        );
    }

    /**
     * @param string $domain
     * @param string $qType
     * @return array
     */
    private function cmd(string $domain, string $qType): array
    {
        return [
            '/usr/bin/dig',
            '@8.8.8.8',
            $domain,
            $qType,
            '+noall',
            '+ans',
            '+authority',
            '+time=1'
        ];
    }

    /**
     * Convert data.
     *
     * @param string $data
     * @return \Traversable
     */
    private function convert(string $data)
    {
        foreach ((array)explode("\n", $data) as $key => $value) {
            if ($key === 0) {
                continue;
            }
            yield trim(str_replace(["\t", '"'], ' ', $value));
        }
    }

    /**
     * Filter query type.
     *
     * @param string $qType
     * @return string
     */
    private function filterQueryType(string $qType): string
    {
        $qType = strtolower($qType);

        $types = [
            'a',
            'any',
            'aaaa',
            'mx',
            'ns',
            'soa',
            'hinfo',
            'axfr',
            'txt',
            'srv',
            'cname'
        ];

        if (!in_array($qType, $types, true)) {
            return 'any';
        }

        return $qType;
    }
}
