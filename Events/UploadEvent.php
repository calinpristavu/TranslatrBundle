<?php

namespace Evozon\TranslatrBundle\Events;

use Evozon\TranslatrBundle\Clients\ClientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UploadEvent
 *
 * @package     Evozon\TranslatrBundle\Events
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class UploadEvent extends Event
{
    const NAME = 'upload';

    /**
     * @var array
     */
    protected $response;

    /**
     * @var ClientInterface
     */
    protected $adapter;

    /**
     * UploadEvent constructor
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
     * @return array the response
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
