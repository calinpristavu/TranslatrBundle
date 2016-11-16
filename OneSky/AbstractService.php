<?php

namespace Evozon\TranslatrBundle\OneSky;

use Evozon\TranslatrBundle\Clients\ClientInterface;

/**
 * Class AbstractService
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class AbstractService
{
    /** @var ClientInterface */
    protected $client;

    /** @var string[] */
    protected $resultStack;

    /** @var String */
    protected $rootDir;

    /**
     * @param ClientInterface $client
     * @param $rootDir
     */
    public function __construct(ClientInterface $client, $rootDir)
    {
        $this->client = $client;
        $this->rootDir = $rootDir;
    }
}
