<?php

namespace Evozon\TranslatrBundle\Service;

/**
 * Class Downloader
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Ovidiu Enache <i.ovidiuenache@yahoo.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class Downloader extends AbstractService
{
    /**
     * @return $this
     */
    public function download()
    {
        $result = $this->client->download();

        $this->resultStack[] = $result;

        return $this;
    }
}
