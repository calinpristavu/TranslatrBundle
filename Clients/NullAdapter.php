<?php

namespace Evozon\TranslatrBundle\Clients;

/**
 * Class NullAdapter
 *
 * @package     Evozon\TranslatrBundle\Clients
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class NullAdapter implements ClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLocales($projectId)
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($projectId)
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($projectId, $source, $locale)
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function getCallStack()
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function upload($files)
    {
        return ['null'];
    }

    public function download()
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function addInCallstack($response)
    {
        // Do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleFormat()
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function getProject()
    {
        return -1;
    }
}
