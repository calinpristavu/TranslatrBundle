<?php

namespace Evozon\TranslatrBundle\Clients;

use Evozon\TranslatrBundle\Events\EventSubscribers\ResponseSubscriber;
use Evozon\TranslatrBundle\Events\GotFilesEvent;
use Evozon\TranslatrBundle\Events\GotLocalesEvent;
use Evozon\TranslatrBundle\Events\GotTranslationsEvent;
use Onesky\Api\Client;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OneSkyAdapter extends Client implements ClientInterface
{
    /**
     * @var array
     */
    protected $callStack;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct();

        $this->callStack = array();

        $this->dispatcher = $dispatcher;
        $dispatcher->addSubscriber(new ResponseSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function getLocales($project)
    {
        $response = $this->projects('languages', ['project_id' => $project]);

        $gotLocalesEvent = new GotLocalesEvent($response, $this);
        $this->dispatcher->dispatch(GotLocalesEvent::NAME, $gotLocalesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($project)
    {
        $response = $this->files('list', ['project_id' => $project, 'per_page' => 100]);

        $gotFilesEvent = new GotFilesEvent($response, $this);
        $this->dispatcher->dispatch(GotFilesEvent::NAME, $gotFilesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($project, $source, $locale)
    {
        $response = $this->translations(
            'export',
            [
                'project_id'       => $project,
                'locale'           => $locale,
                'source_file_name' => $source,
            ]
        );

        $gotTranslationsEvent = new GotTranslationsEvent($response, $this);
        $this->dispatcher->dispatch(GotTranslationsEvent::NAME, $gotTranslationsEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function addInCallstack($response)
    {
        $this->callStack[] = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallStack()
    {
        return $this->callStack;
    }
}