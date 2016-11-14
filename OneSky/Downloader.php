<?php

namespace Evozon\TranslatrBundle\OneSky;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Downloader
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class Downloader extends AbstractService
{
    /**
     * @return $this
     */
    public function download()
    {
        $sources = $this->getAllSources();

        foreach ($sources as $source) {
            $this->dump($source, explode('.', $source)[1]);
        }

        return $this;
    }

    /**
     * @param string $source
     * @param string $locale
     *
     * @return $this
     */
    private function dump($source, $locale)
    {
        $content = $this->fetch($source, $locale);

        $this->write(
            $source,
            $this->cleanupContent($content)
        );

        $this->merge($source);

        return $this;
    }

    /**
     * Moves $source file to translations file and deletes temporary file from OneSky
     * @param $source   String
     */
    public function merge($source)
    {
        $fs = new Filesystem();
        $fs->copy($source, 'app/Resources/translations/' . $source, true);
        $fs->remove($source);
    }

    /**
     * Remove empty newlines from the content
     *
     * @param $content
     *
     * @return mixed
     */
    private function cleanupContent($content)
    {
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
    }

    /**
     * @param string $source
     * @param string $locale
     *
     * @return mixed
     */
    private function fetch($source, $locale)
    {
        return $this->client->getTranslations($this->client->getProject(), $source, $locale);
    }

    /**
     * @param $file
     * @param $content
     *
     * @return $this
     */
    private function write($file, $content)
    {
        $fs = new Filesystem();
        $fs->dumpFile($file, $content);

        return $this;
    }
}
