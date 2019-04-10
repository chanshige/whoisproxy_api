<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Resource;

use Exception;
use RuntimeException;

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
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $links = ['self' => ["href" => $request->getUri()->getPath()]];

        try {
            $qType = strtolower(($args['option'] ?? 'ANY'));
            if (!in_array($qType, self::$qTypes, true)) {
                throw new RuntimeException('query-type:' . $qType . ' is not supported.');
            }

            $result = $this->process($args['domain'], $qType);

            return $response->withHalJson($result, $links, StatusCode::HTTP_OK);
        } catch (Exception $e) {
            return $response->withHalJson($e->getMessage(), $links, StatusCode::HTTP_FORBIDDEN);
        }
    }

    /**
     * Process.
     *
     * @param string $domain
     * @param string $option
     * @return array
     * @throws RuntimeException
     */
    private function process(string $domain, string $option): array
    {
        $process = new Process($this->cmd($domain, $option));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException(Process::$exitCodes[$process->getStatus()]);
        }

        return iterator_to_array($this->convert($process->getOutput()));
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
            '+nocmd',
            '+ans',
            '+additional',
            '+authority',
            '+time=1'
        ];
    }

    /**
     * Convert data.
     *
     * @param string $data
     * @return \Generator
     */
    private function convert(string $data): \Generator
    {
        foreach ((array)explode("\n", trim($data)) as $key => $value) {
            // <<>> DiG..
            if ($key <= 2 || strlen($value) === 0) {
                continue;
            }

            yield trim(str_replace(["\t", '"'], ' ', $value));
        }
    }
}
