<?php

namespace Evozon\TranslatrBundle\Events;

use Evozon\TranslatrBundle\Clients\ClientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class GotFilesEvent
 *
 * @package     Evozon\TranslatrBundle\Events
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
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

    /**
     * GotFilesEvent constructor
     *
     * @param array             $response
     * @param ClientInterface   $adapter
     */
    public function __construct($response, $adapter)
    {
        $this->response = $response;
        $this->adapter = $adapter;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Adds response in the adapter's callstack
     */
    public function logResponse()
    {
        $this->adapter->addInCallstack($this->response);
    }
}
