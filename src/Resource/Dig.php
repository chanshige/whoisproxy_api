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
     * Query Types.
     *
     * @var array
     */
    private static $qTypes = [
        'a',
        'any',
        'aaaa',
        'mx',
        'ns',
        'soa',
        'txt',
        'srv',
        'cname'
    ];

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $result = $this->process(
                $request->getAttribute('domain'),
                strtolower(($request->getAttribute('option') ?? 'ANY'))
            );

            return $response->withHalJson(
                $result,
                ['self' => ["href" => $request->getUri()->getPath()]],
                StatusCode::HTTP_OK
            );
        } catch (\Exception $e) {
            return $response->withHalJson(
                $e->getMessage(),
                ['self' => ["href" => $request->getUri()->getPath()]],
                StatusCode::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * Process.
     *
     * @param string      $domain
     * @param string|null $option
     * @return array
     * @throws \RuntimeException
     */
    private function process(string $domain, ?string $option): array
    {
        if (!in_array($option, self::$qTypes, true)) {
            throw new \RuntimeException('query-type:' . $option . ' is not supported.');
        }

        $process = new Process($this->cmd($domain, $option));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(Process::$exitCodes[$process->getStatus()]);
        }

        $output = iterator_to_array($this->convert($process->getOutput()));
        array_shift($output);

        return $output;
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
}
