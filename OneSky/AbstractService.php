<?php

namespace Evozon\TranslatrBundle\OneSky;

use Evozon\TranslatrBundle\Clients\ClientInterface;
use Evozon\TranslatrBundle\OneSky\Mapping;
use Onesky\Api\Client;

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

    /** @var int */
    protected $project;

    /** @var Mapping[] */
    protected $mappings = [];

    /** @var array $localeFormat */
    protected $localeFormat = [];

    /** @var string[] */
    protected $resultStack;

    /**
     * @param ClientInterface $client
     * @param int $project
     * @param array $localeFormat
     */
    public function __construct(ClientInterface $client, $project, $localeFormat)
    {
        $this->client = $client;
        $this->project = $project;
        $this->localeFormat = $localeFormat;
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
        $raw = $this->client->getLocales($this->project);
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
        $raw = $this->client->getFiles($this->project);
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

        $intersect = array_intersect_key($locale, array_flip($this->localeFormat['parts']));

        return (count($intersect)) ? implode($this->localeFormat['separator'], $intersect) : null;
    }
}
