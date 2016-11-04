<?php

namespace Evozon\TranslatrBundle\OneSky;

use Evozon\TranslatrBundle\Clients\OneSkyAdapter;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class UploaderTest
 */
class UploaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider uploadProvider
     *
     * @param string $expectedLocales
     * @param string $expectedFiles
     * @param string $expectedFinal
     */
    public function testUpload($expectedLocales, $expectedFiles, $expectedFinal)
    {
        $client = $this->createMock('Evozon\TranslatrBundle\Clients\OneSkyAdapter');
        $client
            ->expects($this->any())
            ->method('getFiles')
            ->will($this->returnValue($expectedFiles));
        $client
            ->expects($this->any())
            ->method('getLocales')
            ->will($this->returnValue($expectedLocales));
        $client
            ->expects($this->any())
            ->method('getCallStack')
            ->will($this->returnValue(array($expectedFinal)));

        $mapping = $this->createMock('Evozon\TranslatrBundle\OneSky\Mapping');
        $mapping
            ->expects($this->any())
            ->method('getOutputFilename')
            ->will($this->returnValue('.SonataAdminBundle.enenpopo'));

        $project = 85960;
        $localeFormat = 'en';
        $uploader = new Uploader($client, $project, $localeFormat);
        $uploader->addMapping($mapping);

        $uploader->upload();

        self::assertEquals($expectedFinal, $client->getCallStack()[0]);
    }

    public function test()
    {
        $client = new OneSkyAdapter(new EventDispatcher());
        $client->setApiKey('eTr4ohoDFHYwC50PKcHYGQqQaSUj9Q7d');
        $client->setSecret('OYXDZEZtpxsu15gDmPm2nDiBjtCdjq5X');

        $mapping = $this->createMock('Evozon\TranslatrBundle\OneSky\Mapping');
        $mapping
            ->expects($this->any())
            ->method('getOutputFilename')
            ->will($this->returnValue('.SonataAdminBundle.enenpopo'));

        $project = 85960;
        $localeFormat = 'en';
        $uploader = new Uploader($client, $project, $localeFormat);

        $uploader->addMapping($mapping);

        $uploader->upload();
        $uploader->upload();
    }

    /**
     * @return string[]
     */
    public function uploadProvider()
    {
        return [
            [
                '{"meta":{"status":200,"record_count":2},"data":[{"code":"en","english_name":"English","local_name":"English\u0000","custom_locale":null,"locale":"en","region":"","is_base_language":true,"is_ready_to_publish":false,"translation_progress":"100.0%","last_updated_at":"2016-11-02T14:12:56+0000","last_updated_at_timestamp":1478095976},{"code":"ro","english_name":"Romanian","local_name":"Rom\u00e2n\u0103\u0000","custom_locale":null,"locale":"ro","region":"","is_base_language":false,"is_ready_to_publish":true,"translation_progress":"7.1%","last_updated_at":"2016-11-02T13:03:17+0000","last_updated_at_timestamp":1478091797}]}',
                '{"meta":{"status":201},"data":{"name":".SonataAdminBundle.enenpopo","format":"GNU_PO","language":{"code":"en","english_name":"English","local_name":"English\u0000","custom_locale":null,"locale":"en","region":""},"import":{"id":1780588,"created_at":"2016-11-02T14:17:40+0000","created_at_timestamp":1478096260}}}',
                '{"meta":{"status":200,"record_count":2},"data":[{"code":"en","english_name":"English","local_name":"English\u0000","custom_locale":null,"locale":"en","region":"","is_base_language":true,"is_ready_to_publish":false,"translation_progress":"100.0%","last_updated_at":"2016-11-02T14:17:42+0000","last_updated_at_timestamp":1478096262},{"code":"ro","english_name":"Romanian","local_name":"Rom\u00e2n\u0103\u0000","custom_locale":null,"locale":"ro","region":"","is_base_language":false,"is_ready_to_publish":true,"translation_progress":"7.1%","last_updated_at":"2016-11-02T13:03:17+0000","last_updated_at_timestamp":1478091797}]}',
            ],
        ];
    }
}

