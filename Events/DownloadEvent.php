<?php

namespace Evozon\TranslatrBundle\Events;

use Evozon\TranslatrBundle\Clients\ClientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The download event is dispatched each time files are downloaded from the client
 */
class DownloadEvent extends Event
{
    const NAME = 'download';

    /**
     * @var array
     */
    protected $response;

    /**
     * @var ClientInterface
     */
    protected $adapter;

    public function __construct($response, $adapter)
    {
        $this->response = $response;
        $this->adapter = $adapter;
    }

    /**
     * @return array the response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function logResponse()
    {
        $this->adapter->addInCallstack($this->response);
    }
}