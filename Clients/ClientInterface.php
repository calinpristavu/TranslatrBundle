<?php

namespace Evozon\TranslatrBundle\Clients;

interface ClientInterface
{
    /**
     * Gets all locales defined in the translation platform
     *
     * @param $project
     * @return array
     */
    public function getLocales($project);

    /**
     * Gets all files uploaded to the translation platform
     *
     * @param $project
     *
     * @return array
     */
    public function getFiles($project);

    /**
     * Gets the translation file content for the selected locale
     *
     * @param $project
     * @param $source
     * @param string $locale
     *
     * @return array
     */
    public function getTranslations($project, $source, $locale);

    /**
     * Gets the responses
     *
     * @return array
     */
    public function getCallStack();

    /**
     * Adds a new response in the callstack
     *
     * @param array $response
     */
    public function addInCallstack($response);
}