<?php

namespace Evozon\TranslatrBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExtractTranslationsCommand
 *
 * @package   Evozon\TranslatrBundle\Command
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @author    Calin Bolea <calin.bolea@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class ExtractTranslationsCommand extends AbstractCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('evozon:translatr:extract')
            ->setDescription('Extract translations from the application')
            ->addArgument(
                'languages',
                InputArgument::VALUE_OPTIONAL,
                'Languages to be extracted'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Extracting translations from the application</info>");

        $availableLocales = $this->getContainer()->getParameter('available_locales', []);
        $availableLocales = array_map(
            function ($locale) {
                return strtolower(substr($locale, 0, 2));
            },
            $availableLocales
        );

        $inputLanguages = explode(',', $input->getArgument('languages'));
        $filteredInput = array();

        if (empty($inputLanguages)) {
            $filteredInput = $availableLocales;
            $output->writeln("<info>No language found! Will extract all available languages!</info>");
        } else {
            foreach ($inputLanguages as $language) {
                if (in_array($language, $availableLocales)) {
                    $filteredInput[] = $language;
                } else {
                    $output->writeln("<warning>Language $language is not available!</warning>");
                }
            }
        }

        $commandName = 'translation:update';
        $command = $this->getApplication()->find($commandName);

        foreach ($filteredInput as $locale) {
            $command->run(
                new ArrayInput(['command' => $commandName, 'locale' => $locale, '--dump-messages' => true]),
                $output
            );
        }

        $output->writeln("<info>Translations successfully extracted</info>");
    }
}
