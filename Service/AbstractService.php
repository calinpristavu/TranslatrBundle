<?php

namespace Evozon\TranslatrBundle\Service;

use Evozon\TranslatrBundle\Clients\ClientInterface;

/**
 * Class AbstractService
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @author    Ovidiu Enache <i.ovidiuenache@yahoo.com>
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
     * AbstractService constructor
     *
     * @param ClientInterface   $client
     * @param String            $rootDir
     */
    public function __construct(ClientInterface $client, $rootDir)
    {
        $this->client = $client;
        $this->rootDir = $rootDir;
    }
}
