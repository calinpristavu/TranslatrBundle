<?php

namespace Evozon\TranslatrBundle\Clients;

use Evozon\TranslatrBundle\Events\EventSubscribers\ResponseSubscriber;
use Onesky\Api\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

// Access token : 42412d61e38ddc607551e765065a6bb131fd689a9dbf345bb9e23e78e5daf8cf
// Project ID : 900090880e75f475788b5f7ff503d5c0

class PhraseAppAdapter extends Client implements ClientInterface
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
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$project/locales",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERPWD => "$this->apiKey:"
            )
        );

        $response = curl_exec($ch);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles($project)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$project/uploads",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERPWD => "$this->apiKey:"
            )
        );

        $response = curl_exec($ch);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations($project, $source, $locale)
    {
        $localeId = $this->getLocaleId($locale);

        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/:project_id/locales/$localeId/translations",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERPWD => "$this->apiKey:"
            )
        );

        $response = curl_exec($ch);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($files)
    {
        $raw = $this->getLocales($this->getProject());
        $response = json_decode($raw, true);
        $locales = array();

        foreach ($response as $locale) {
            $locales[] = $locale['name'];
        }

        foreach ($locales as $locale) {

            $localeId = $this->getLocaleId($locale);

            foreach ($files as $file) {

                $cFile = curl_file_create($file);

                $ch = curl_init();
                curl_setopt_array(
                    $ch,
                    array(
                        CURLOPT_URL => "https://api.phraseapp.com/api/v2/projects/$this->getProject()/uploads",
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
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function download()
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
     * @param   $locale     String
     *
     * @return  int         ID of the locale
     *                      0 if locale was not found
     */
    private function getLocaleId($locale)
    {
        $raw = $this->getLocales($this->getProject());
        $response = json_decode($raw, true);

        foreach ($response as $locale) {
            if ($locale['name'] === $locale) {
                return $locale['id'];
            }
        }

        return 0;
    }
}