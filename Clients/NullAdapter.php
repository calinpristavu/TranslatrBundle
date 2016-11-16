<?php

namespace Evozon\TranslatrBundle\Clients;

class NullAdapter implements ClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLocales($project)
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($project)
    {
        return ['null'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($project, $source, $locale)
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
