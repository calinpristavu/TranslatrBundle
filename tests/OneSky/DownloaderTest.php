<?php

namespace Evozon\TranslatrBundle\tests\OneSky;

use Evozon\TranslatrBundle\OneSky\Downloader;

/**
 * Class DownloaderTest
 */
class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider downloadProvider
     *
     * @param string $expectedFiles
     * @param $expectedLocales
     * @param string $expectedTranslations
     * @param $expectedFinalFileContentEn
     * @param $expectedFinalFileContentRo
     */
    public function testDownload(
        $expectedFiles,
        $expectedLocales,
        $expectedTranslations,
        $expectedFinalFileContentEn,
        $expectedFinalFileContentRo
    )
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
            ->method('getTranslations')
            ->will($this->returnValue($expectedTranslations));


        $mapping = $this->createMock('Evozon\TranslatrBundle\OneSky\Mapping');
        $mapping
            ->expects($this->any())
            ->method('useLocale')
            ->will($this->returnValue(true));
        $mapping
            ->expects($this->any())
            ->method('useSource')
            ->will($this->returnValue(true));
        $mapping
            ->expects($this->any())
            ->method('getOutputFilename')
            ->will($this->returnValue('.SonataAdminBundle.enenpopopostfix'));
        $mapping
            ->expects($this->any())
            ->method('getOriginalOutputFilename')
            ->will($this->returnValue('.SonataAdminBundle.enenpopo'));
        $mapping
            ->expects($this->any())
            ->method('getOutputFileDomain')
            ->will($this->returnValue('SonataAdminBundle'));

        $project = 85960;
        $localeFormat = 'en';
        $downloader = new Downloader($client, $project, $localeFormat);
        $downloader->addMapping($mapping);

        $downloader->download();
        
        $this->assertEquals(
            $expectedFinalFileContentEn,
            file_get_contents('SonataAdminBundle.en.po')
        );

        $this->assertEquals(
            $expectedFinalFileContentRo,
            file_get_contents('SonataAdminBundle.ro.po')
        );
    }

    /**
     * @return string[]
     */
    public function downloadProvider()
    {
        return [[
            '{"meta":{"status":200,"record_count":2,"page_count":1,"next_page":null,"prev_page":null,"first_page":null,"last_page":null},"data":[{"file_name":".SonataAdminBundle.enenpopo","string_count":14,"last_import":{"id":1779757,"status":"completed"},"uploaded_at":"2016-11-02T13:06:42+0000","uploaded_at_timestamp":1478092002},{"file_name":"SonataAdminBundle.en.po","string_count":14,"last_import":{"id":1779352,"status":"completed"},"uploaded_at":"2016-11-02T10:23:07+0000","uploaded_at_timestamp":1478082187}]}',
            '{"meta":{"status":200,"record_count":2},"data":[{"code":"en","english_name":"English","local_name":"English\u0000","custom_locale":null,"locale":"en","region":"","is_base_language":true,"is_ready_to_publish":false,"translation_progress":"100.0%","last_updated_at":"2016-11-02T13:07:24+0000","last_updated_at_timestamp":1478092044},{"code":"ro","english_name":"Romanian","local_name":"Rom\u00e2n\u0103\u0000","custom_locale":null,"locale":"ro","region":"","is_base_language":false,"is_ready_to_publish":true,"translation_progress":"7.1%","last_updated_at":"2016-11-02T13:03:17+0000","last_updated_at_timestamp":1478091797}]}',
            'msgid ""
msgstr ""
"Project-Id-Version: VERSION\n"
"POT-Creation-Date: 2016-11-02 14:05+0000\n"
"PO-Revision-Date: 2016-11-02 14:05+0000\n"
"Last-Translator: FULL NAME <EMAIL@ADDRESS>\n"
"Language-Team: LANGUAGE TEAM <EMAIL@ADDRESS>\n"
"Language: en\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"

msgid "no_result"
msgstr "no_result"

msgid "confirm_exit"
msgstr "really exit?"

msgid "admin.page.edit.confirm.change_published.label"
msgstr "This page is tied to a menu. Unpublishing the page will make it inaccessible from the menu. Are you sure you want to make this change?"

msgid "switch_user_exit"
msgstr "switch_user_exit"

msgid "btn.reset.pinned_position"
msgstr "Yes, reset position"

msgid "title.reset.pinned_position"
msgstr "Confirm reset position"

msgid "admin.list.reset.pinned_position"
msgstr "Reset position"

msgid "admin.page.edit.confirm.label"
msgstr "Please confirm you want to do this action."

msgid "user_block_logout"
msgstr "Logout"

msgid "message.reset.pinned_position.confirmation"
msgstr "Are you sure that you want to reset the position for the selected item?"

msgid "reset.pinned_position.or"
msgstr "or"

msgid "sonata.batch.reset.pinned_position.success"
msgstr "Positions were reset successfully"

msgid "link.action.edit"
msgstr "Edit"

msgid "sonata.reset.pinned_position.success"
msgstr "Position was reset successfully"

',
            'msgid ""
msgstr ""
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Language: en\n"

msgid "admin.page.edit.confirm.change_published.label"
msgstr "This page is tied to a menu. Unpublishing the page will make it inaccessible from the menu. Are you sure you want to make this change?"
msgid "admin.page.edit.confirm.label"
msgstr "Please confirm you want to do this action."
msgid "confirm_exit"
msgstr "really exit?"
msgid "no_result"
msgstr "no_result"
msgid "switch_user_exit"
msgstr "switch_user_exit"
msgid "user_block_logout"
msgstr "Logout"
msgid "admin.list.reset.pinned_position"
msgstr "Reset position"
msgid "title.reset.pinned_position"
msgstr "Confirm reset position"
msgid "message.reset.pinned_position.confirmation"
msgstr "Are you sure that you want to reset the position for the selected item?"
msgid "btn.reset.pinned_position"
msgstr "Yes, reset position"
msgid "reset.pinned_position.or"
msgstr "or"
msgid "link.action.edit"
msgstr "Edit"
msgid "sonata.batch.reset.pinned_position.success"
msgstr "Positions were reset successfully"
msgid "sonata.reset.pinned_position.success"
msgstr "Position was reset successfully"',
            'msgid ""
msgstr ""
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Language: ro\n"

msgid "admin.page.edit.confirm.change_published.label"
msgstr "This page is tied to a menu. Unpublishing the page will make it inaccessible from the menu. Are you sure you want to make this change?"
msgid "admin.page.edit.confirm.label"
msgstr "Please confirm you want to do this action."
msgid "confirm_exit"
msgstr "really exit?"
msgid "no_result"
msgstr "no_result"
msgid "switch_user_exit"
msgstr "switch_user_exit"
msgid "user_block_logout"
msgstr "Logout"
msgid "admin.list.reset.pinned_position"
msgstr "Reset position"
msgid "title.reset.pinned_position"
msgstr "Confirm reset position"
msgid "message.reset.pinned_position.confirmation"
msgstr "Are you sure that you want to reset the position for the selected item?"
msgid "btn.reset.pinned_position"
msgstr "Yes, reset position"
msgid "reset.pinned_position.or"
msgstr "or"
msgid "link.action.edit"
msgstr "Edit"
msgid "sonata.batch.reset.pinned_position.success"
msgstr "Positions were reset successfully"
msgid "sonata.reset.pinned_position.success"
msgstr "Position was reset successfully"'
        ]];
    }
}