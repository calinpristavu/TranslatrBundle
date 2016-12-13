<?php

namespace Evozon\TranslatrBundle\Clients;

use Evozon\TranslatrBundle\Events\EventSubscribers\ResponseSubscriber;
use Evozon\TranslatrBundle\Events\GotFilesEvent;
use Evozon\TranslatrBundle\Events\GotLocalesEvent;
use Evozon\TranslatrBundle\Events\GotTranslationsEvent;
use Evozon\TranslatrBundle\Events\UploadEvent;
use Evozon\TranslatrBundle\Events\DownloadEvent;
use Onesky\Api\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class OneSkyAdapter
 *
 * @package     Evozon\TranslatrBundle\Clients
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class OneSkyAdapter extends Client implements ClientInterface
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
     * Class constructor.
     *
     * @param EventDispatcherInterface  $dispatcher
     * @param int                       $project
     * @param array                     $localeFormat
     */
    public function __construct(EventDispatcherInterface $dispatcher, $project, $localeFormat)
    {
        parent::__construct();

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
        $response = $this->projects('languages', ['project_id' => $projectId]);

        $gotLocalesEvent = new GotLocalesEvent($response, $this);
        $this->dispatcher->dispatch(GotLocalesEvent::NAME, $gotLocalesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($projectId)
    {
        $response = $this->files('list', ['project_id' => $projectId, 'per_page' => 100]);

        $gotFilesEvent = new GotFilesEvent($response, $this);
        $this->dispatcher->dispatch(GotFilesEvent::NAME, $gotFilesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($projectId, $source, $locale)
    {
        $response = $this->translations(
            'export',
            [
                'project_id'       => $projectId,
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
    public function upload($files)
    {
        $raw = $this->getLocales($this->getProject());
        $response = json_decode($raw, true);

        foreach ($files as $file) {
            $response[] = $this->files('upload', [
                'project_id'             => $this->getProject(),
                'file'                   => $file,
                'file_format'            => 'GNU_PO',
                'locale'                 => $this->getLocaleFromFile($file),
                'is_keeping_all_strings' => false,
            ]);
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
        $response = json_decode($raw, true);
        $data = $response['data'];

        $sources = array_map(
            function ($item) {
                return $item['file_name'];
            },
            $data
        );

        $response = array();

        foreach ($sources as $source) {
            $locale = explode('.', $source)[1];

            $content = $this->getTranslations($this->getProject(), $source, $locale);

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
}
