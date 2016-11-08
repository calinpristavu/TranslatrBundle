<?php

namespace Evozon\TranslatrBundle\OneSky;

/**
 * Class Uploader
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
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
        $this->resultStack[] = $this->client->upload(
            $this->client->getProject(),
            $this->mappings,
            $this->getAllLocales(),
            $this->isKeepingAllStrings
        );

        return $this;
    }
}
