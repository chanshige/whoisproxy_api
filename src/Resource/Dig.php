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
final class Dig extends AbstractResource
{
    /**
     * Query Types.
     *
     * @var array
     */
    public static $qTypes = [
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

    /** @var string */
    private static $defaultServer = '8.8.8.8';

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->links['self']['href'] = $request->getUri()->getPath();

        try {
            $qType = isset($args['q-type']) ? $args['q-type'] : '';
            $globalServer = isset($args['global-server']) ? $args['global-server'] : '';

            $result = $this->process($this->cmd($args['domain'], $qType, $globalServer));

            return $response->withHalJson($result, $this->links, StatusCode::HTTP_OK);
        } catch (Exception $e) {
            return $response->withHalJson($e->getMessage(), $this->links, StatusCode::HTTP_FORBIDDEN);
        }
    }

    /**
     * Process. (Symfony/Process)
     *
     * @param array $command
     * @return array
     * @throws RuntimeException
     */
    private function process(array $command): array
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getExitCodeText());
        }

        return iterator_to_array($this->convert($process->getOutput()));
    }

    /**
     * @param string $domain
     * @param string $qType
     * @param string $globalServer
     * @return array
     */
    private function cmd(string $domain, string $qType, string $globalServer): array
    {
        return [
            '/usr/bin/dig',
            $this->filterGlobalServer($globalServer),
            $domain,
            $this->filterQueryType($qType),
            '+noall',
            '+nocmd',
            '+ans',
            '+additional',
            '+authority',
            '+time=1'
        ];
    }

    /**
     * @param string $qType
     * @return string
     */
    private function filterQueryType(string $qType): string
    {
        return strtolower(strlen($qType) > 0 ? $qType : 'any');
    }

    /**
     * @param string $servername
     * @return string
     */
    private function filterGlobalServer(string $servername): string
    {
        return '@' . strtolower(strlen($servername) > 0 ? $servername : self::$defaultServer);
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
