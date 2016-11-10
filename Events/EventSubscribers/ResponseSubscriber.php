<?php

namespace Evozon\TranslatrBundle\Events\EventSubscribers;

use Evozon\TranslatrBundle\Events\GotFilesEvent;
use Evozon\TranslatrBundle\Events\GotLocalesEvent;
use Evozon\TranslatrBundle\Events\GotTranslationsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            GotFilesEvent::NAME => 'onFilesEvent',
            GotTranslationsEvent::NAME => 'onTranslationsEvent',
            GotLocalesEvent::NAME => 'onLocalesEvent',
        );
    }

    public function onFilesEvent(GotFilesEvent $event)
    {
        $event->logResponse();
    }

    public function onTranslationsEvent(GotTranslationsEvent $event)
    {
        $event->logResponse();
    }

    public function onLocalesEvent(GotLocalesEvent $event)
    {
        $event->logResponse();
    }
}