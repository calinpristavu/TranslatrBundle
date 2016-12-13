<?php

namespace Evozon\TranslatrBundle\Clients;

/**
 * Interface ClientInterface
 *
 * @package     Evozon\TranslatrBundle\Clients
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
interface ClientInterface
{
    /**
     * Gets all locales defined in the translation platform
     *
     * @param   int     $projectId
     *
     * @return  array
     */
    public function getLocales($projectId);

    /**
     * Gets all files uploaded to the translation platform
     *
     * @param   int     $projectId
     *
     * @return  array
     */
    public function getFiles($projectId);

    /**
     * Gets the translation file content for the selected locale
     *
     * @param int       $projectId
     * @param String    $source
     * @param String    string $locale
     *
     * @return array
     */
    public function getTranslations($projectId, $source, $locale);

    /**
     * Uploads extracted translation files to adapter
     *
     * @param   array       $files
     *
     * @return  array       Response
     */
    public function upload($files);

    /**
     * Downloads files from adapter
     *
     * @param   String  $rootDir
     *
     * @return  array
     */
    public function download($rootDir);

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
