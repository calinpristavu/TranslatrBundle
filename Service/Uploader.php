<?php

namespace Evozon\TranslatrBundle\Service;

/**
 * Class Uploader
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Ovidiu Enache <i.ovidiuenache@yahoo.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class Uploader extends AbstractService
{
    /**
     * @var bool
     */
    protected $isKeepingAllStrings = false;

    /**
     * @return $this
     */
    public function upload()
    {
        $result = $this->client->upload($this->getUploadFileNames('po'));

        $this->resultStack[] = $result;

        return $this;
    }

    /**
     * Gets all files in Resources/translations with given extension
     *
     * @param   String    $fileExtension
     *
     * @return  array
     */
    protected function getUploadFileNames($fileExtension)
    {
        $fileNames = scandir($this->rootDir . '/Resources/translations', 1);

        //Remove . and ..
        array_pop($fileNames);
        array_pop($fileNames);

        $fileNames = array_filter($fileNames, function ($fileName) use ($fileExtension) {
            $parts = explode('.', $fileName);
            $extension = array_pop($parts);
            return $extension === $fileExtension;
        });

        foreach ($fileNames as $key => $fileName) {
            $fileNames[$key] = $this->rootDir . '/Resources/translations/' . $fileName;
        }

        return $fileNames;
    }
}
