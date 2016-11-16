<?php

namespace Evozon\TranslatrBundle\Clients;

use Evozon\TranslatrBundle\Events\EventSubscribers\ResponseSubscriber;
use Evozon\TranslatrBundle\Events\GotFilesEvent;
use Evozon\TranslatrBundle\Events\GotLocalesEvent;
use Evozon\TranslatrBundle\Events\GotTranslationsEvent;
use Evozon\TranslatrBundle\Events\UploadEvent;
use Onesky\Api\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

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
     * @var
     */
    protected $localeFormat;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;


    /**
     * OneSkyAdapter constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param int $project
     * @param array $localeFormat
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
    public function getLocales($project)
    {
        $response = $this->projects('languages', ['project_id' => $project]);

        $gotLocalesEvent = new GotLocalesEvent($response, $this);
        $this->dispatcher->dispatch(GotLocalesEvent::NAME, $gotLocalesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($project)
    {
        $response = $this->files('list', ['project_id' => $project, 'per_page' => 100]);

        $gotFilesEvent = new GotFilesEvent($response, $this);
        $this->dispatcher->dispatch(GotFilesEvent::NAME, $gotFilesEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($project, $source, $locale)
    {
        $response = $this->translations(
            'export',
            [
                'project_id'       => $project,
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
    public function upload($project, $files, $locales, $isKeepingAllStrings)
    {
        $response = array();

        foreach ($locales as $locale) {
            foreach ($files as $file) {
                $response[] = $this->files('upload', [
                    'project_id'             => $project,
                    'file'                   => $file,
                    'file_format'            => 'GNU_PO',
                    'locale'                 => $locale,
                    'is_keeping_all_strings' => $isKeepingAllStrings,
                ]);
            }
        }

        $uploadEvent = new UploadEvent($response, $this);
        $this->dispatcher->dispatch(UploadEvent::NAME, $uploadEvent);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function download($sources)
    {
        $response = array();

        foreach ($sources as $source) {
            $locale = explode('.', $source)[1];

            $content = $this->getTranslations($this->getProject(), $source, $locale);

            //Remove empty lines from content
            $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);

            $response[] = $content;

            $fs = new Filesystem();
            $fs->dumpFile($source, $content);

            $fs->copy($source, 'app/Resources/translations/' . $source, true);
            $fs->remove($source);
        }

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
}
