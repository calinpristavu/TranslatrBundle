<?php

namespace Evozon\TranslatrBundle\OneSky;

use Evozon\TranslatrBundle\Clients\ClientInterface;

/**
 * Class AbstractService
 *
 * @package   Evozon\TranslatrBundle\OneSky
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class AbstractService
{
    /** @var ClientInterface */
    protected $client;

    /** @var Mapping[] */
    protected $mappings = [];

    /** @var string[] */
    protected $resultStack;

    /** @var String */
    protected $rootDir;

    /**
     * @param ClientInterface $client
     * @param $rootDir
     */
    public function __construct(ClientInterface $client, $rootDir)
    {
        $this->client = $client;
        $this->rootDir = $rootDir;
    }

    /**
     * @param Mapping $mapping
     *
     * @return $this
     */
    public function addMapping(Mapping $mapping)
    {
        $this->mappings[] = $mapping;

        return $this;
    }

    /**
     * @return array
     */
    protected function getAllLocales()
    {
        $raw = $this->client->getLocales($this->client->getProject());
        $this->resultStack[] = $raw;
        $response = json_decode($raw, true);
        $data = $response['data'];

        return array_map(
            function ($item) {
                return $this->formatLocale($item);
            },
            $data
        );
    }

    /**
     * @return array
     */
    protected function getAllSources()
    {
        $raw = $this->client->getFiles($this->client->getProject());
        $this->resultStack[] = $raw;
        $response = json_decode($raw, true);
        $data = $response['data'];

        return array_map(
            function ($item) {
                return $item['file_name'];
            },
            $data
        );
    }

    /**
     * @param array $locale
     *
     * @return string
     */
    private function formatLocale(array $locale = [])
    {
        if (!$locale['region']) {
            return $locale['locale'];
        }

        $intersect = array_intersect_key($locale, array_flip($this->client->getLocaleFormat()['parts']));

        return (count($intersect)) ? implode($this->client->getLocaleFormat()['separator'], $intersect) : null;
    }
}
