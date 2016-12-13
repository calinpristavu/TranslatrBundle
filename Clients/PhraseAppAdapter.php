<?php

namespace Evozon\TranslatrBundle\Clients;

use Evozon\TranslatrBundle\Events\EventSubscribers\ResponseSubscriber;
use Evozon\TranslatrBundle\Events\GotFilesEvent;
use Evozon\TranslatrBundle\Events\GotLocalesEvent;
use Evozon\TranslatrBundle\Events\GotTranslationsEvent;
use Evozon\TranslatrBundle\Events\UploadEvent;
use Evozon\TranslatrBundle\Events\DownloadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PhraseAppAdapter
 *
 * @package     Evozon\TranslatrBundle\Clients
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class PhraseAppAdapter implements ClientInterface
{
    /**
     * @var array
     */
    protected $callStack;

    /**
     * @var int
     */
    protected $project;

    /**
     * @var String
     */
    protected $localeFormat;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var
     */
    protected $apiKey;

    /**
     * OneSkyAdapter constructor.
     *
     * @param EventDispatcherInterface  $dispatcher
     * @param int                       $project
     * @param array                     $localeFormat
     */
    public function __construct(EventDispatcherInterface $dispatcher, $project, $localeFormat)
    {
        $this->callStack = array();

        $this->dispatcher = $dispatcher;
        $dispatcher->addSubscriber(new ResponseSubscriber());

        $this->project = $project;
        $this->localeFormat = $localeFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocales($projectId)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$projectId/locales",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERPWD => "$this->apiKey:"
            )
        );

        $response = curl_exec($ch);

        $gotLocalesEvent = new GotLocalesEvent($response, $this);
        $this->dispatcher->dispatch(GotLocalesEvent::NAME, $gotLocalesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($projectId)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$projectId/uploads",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERPWD => "$this->apiKey:"
            )
        );

        $response = curl_exec($ch);

        $gotFilesEvent = new GotFilesEvent($response, $this);
        $this->dispatcher->dispatch(GotFilesEvent::NAME, $gotFilesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($projectId, $source, $locale)
    {
        $localeId = $this->getLocaleId($locale);

        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$projectId/locales/$localeId/translations",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERPWD => "$this->apiKey:"
            )
        );

        $response = curl_exec($ch);

        $gotTranslationsEvent = new GotTranslationsEvent($response, $this);
        $this->dispatcher->dispatch(GotTranslationsEvent::NAME, $gotTranslationsEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($files)
    {
        $response = array();

        foreach ($files as $filePath) {
            $fileLocale = $this->getLocaleFromFile($filePath);
            $localeId = $this->getLocaleId($fileLocale);

            $cFile = curl_file_create($filePath);

            $ch = curl_init();
            curl_setopt_array(
                $ch,
                array(
                    CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$this->project/uploads",
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_USERPWD => "$this->apiKey:",
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        "file" => $cFile,
                        "locale_id" => $localeId
                    )
                )
            );

            $response[] = curl_exec($ch);
        }

        $uploadEvent = new UploadEvent($response, $this);
        $this->dispatcher->dispatch(UploadEvent::NAME, $uploadEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function download($rootDir)
    {
        $raw = $this->getFiles($this->getProject());
        $response = json_decode($raw);

        $sources = array();
        foreach ($response as $file) {
            $sources[] = $file->filename;
        }

        $response = array();

        foreach ($sources as $source) {
            $locale = explode('.', $source)[1];

            $content = $this->getTranslations($this->getProject(), $source, $locale);

            $content = $this->convertToPo($content, $locale);

            //Remove empty lines from content
            $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);

            $response[] = $content;

            $fs = new Filesystem();
            $fs->dumpFile($source, $content);

            $fs->copy(
                $source,
                $rootDir . '/Resources/translations/' . $source,
                true
            );
            $fs->remove($source);
        }

        $downloadEvent = new DownloadEvent($response, $this);
        $this->dispatcher->dispatch(DownloadEvent::NAME, $downloadEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleFormat()
    {
        return $this->localeFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallStack()
    {
        return $this->callStack;
    }

    /**
     * {@inheritdoc}
     */
    public function addInCallstack($response)
    {
        $this->callStack[] = $response;
    }

    /**
     * Returns the ID of a locale
     *
     * @param   String      $locale
     *
     * @return  int         ID of the locale
     *                      0 if locale was not found
     */
    private function getLocaleId($locale)
    {
        $raw = $this->getLocales($this->getProject());
        $response = json_decode($raw, true);

        foreach ($response as $loc) {
            if ($loc['name'] === $locale) {
                return $loc['id'];
            }
        }

        return 0;
    }

    /**
     * Converts json content to .po text format
     *
     * @param   String    $content
     * @param   String    $locale
     *
     * @return  String
     */
    private function convertToPo($content, $locale)
    {
        $content = json_decode($content);
        foreach ($content as $key => $translation) {
            $content[$key] = [$translation->content,$translation->key->name];
        }

        $po = "msgid \"\"
msgstr \"\"
\"Content-Type: text/plain; charset=UTF-8\"
\"Content-Transfer-Encoding: 8bit\"
\"Language: $locale\"
\n
";

        foreach ($content as $translation) {
            $po .= 'msgid' . ' ' . "\"$translation[1]\"" . PHP_EOL . 'msgstr' . ' ' . "\"$translation[0]\"" . PHP_EOL;
        }

        return $po;
    }

    /**
     * Returns the locale of a file from its full path
     *
     * @param   String  $filePath
     *
     * @return  String
     */
    private function getLocaleFromFile($filePath)
    {
        $fileName = array_values(array_slice(explode('/', $filePath), -1))[0];
        $fileLocale = explode('.', $fileName)[1];

        return $fileLocale;
    }

    /**
     * @param $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}
