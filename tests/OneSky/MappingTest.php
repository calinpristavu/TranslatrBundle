<?php

namespace Evozon\TranslatrBundle\OneSky;

/**
 * Class MappingTest
 */
class MappingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test function
     */
    public function testUseSource()
    {
        $firstMapping = new Mapping([], [], "output");
        $secondMapping = new Mapping(["someSource"], [], "output");
        $thirdMapping = new Mapping(["someSource"], [], "output");


        self::assertEquals(true, $firstMapping->useSource("source"));
        self::assertEquals(true, $secondMapping->useSource("someSource"));
        self::assertEquals(false, $thirdMapping->useSource("someOtherSource"));
    }

    /**
     * Test function
     */
    public function testUseLocale()
    {
        $firstMapping = new Mapping([], [], "output");
        $secondMapping = new Mapping([], ["someLocale"], "output");
        $thirdMapping = new Mapping([], ["someLocale"], "output");

        self::assertEquals(true, $firstMapping->useLocale("locale"));
        self::assertEquals(true, $secondMapping->useLocale("someLocale"));
        self::assertEquals(false, $thirdMapping->useLocale("someOtherLocale"));
    }

    /**
     * Test function
     */
    public function testGetOutputFilename()
    {
        $mapping = new Mapping([], [], "[dirname] [filename] [locale] [extension] [ext]", "postfix");

        $source = "/testdir/test.txt";
        $locale = "/testdir/test.txt";
        $outputFileName = $mapping->getOutputFilename($source, $locale);

        self::assertEquals("/testdir test /testdir/test.txt txt txtpostfix", $outputFileName);
    }

    /**
     * Test function
     */
    public function testGetOriginalOutputFilename()
    {
        $mapping = new Mapping([], [], "[dirname] [filename] [locale] [extension] [ext]");

        $source = "/testdir/test.txt";
        $locale = "/testdir/test.txt";
        $modifiedOutput = $mapping->getOriginalOutputFilename($source, $locale);

        self::assertEquals("/testdir test /testdir/test.txt txt txt", $modifiedOutput);
    }

    /**
     * Test function
     */
    public function testGetOutputFileDomain()
    {

    }

}