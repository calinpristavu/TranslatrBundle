<?php
/**
 * Created by PhpStorm.
 * User: calinpristavu
 * Date: 02.11.2016
 * Time: 15:31
 */

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
    public function upload($project, $mappings, $locales, $isKeepingAllStrings)
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