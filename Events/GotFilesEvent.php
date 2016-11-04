<?php

namespace Evozon\TranslatrBundle\Events;

use Evozon\TranslatrBundle\Clients\ClientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The got.files event is dispatched each time is get the files from the server
 */
class GotFilesEvent extends Event
{
    const NAME = 'got.files';

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