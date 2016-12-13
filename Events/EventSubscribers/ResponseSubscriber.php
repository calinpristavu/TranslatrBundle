<?php

namespace Evozon\TranslatrBundle\Events\EventSubscribers;

use Evozon\TranslatrBundle\Events\GotFilesEvent;
use Evozon\TranslatrBundle\Events\GotLocalesEvent;
use Evozon\TranslatrBundle\Events\GotTranslationsEvent;
use Evozon\TranslatrBundle\Events\UploadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ResponseSubscriber
 *
 * @package     Evozon\TranslatrBundle\Events\EventSubscribers
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class ResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            GotFilesEvent::NAME => 'onFilesEvent',
            GotTranslationsEvent::NAME => 'onTranslationsEvent',
            GotLocalesEvent::NAME => 'onLocalesEvent',
            UploadEvent::NAME => 'onUpdateEvent',
        );
    }

    /**
     * @param GotFilesEvent $event
     */
    public function onFilesEvent(GotFilesEvent $event)
    {
        $event->logResponse();
    }

    /**
     * @param GotTranslationsEvent $event
     */
    public function onTranslationsEvent(GotTranslationsEvent $event)
    {
        $event->logResponse();
    }

    /**
     * @param UploadEvent $event
     */
    public function onUpdateEvent(UploadEvent $event)
    {
        $event->logResponse();
    }

    /**
     * @param GotLocalesEvent $event
     */
    public function onLocalesEvent(GotLocalesEvent $event)
    {
        $event->logResponse();
    }
}