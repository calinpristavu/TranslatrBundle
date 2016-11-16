<?php

namespace Evozon\TranslatrBundle\OneSky;

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
        $this->client->download();

        return $this;
    }
}
