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
     * Uploads files to client
     *
     * @param $files
     *
     * @return array    Response
     */
    public function upload($files);

    /**
     * Downloads files from client
     *
     * @return            array     Response
     */
    public function download();

    /**
     * Gets the locale format
     *
     * @return array
     */
    public function getLocaleFormat();

    /**
     * Gets the project id
     *
     * @return int
     */
    public function getProject();

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