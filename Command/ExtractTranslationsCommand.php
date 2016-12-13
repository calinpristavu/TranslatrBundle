<?php

namespace Evozon\TranslatrBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ExtractTranslationsCommand
 *
 * @package     Evozon\TranslatrBundle\Command
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
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
                InputArgument::OPTIONAL,
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
        $io = new SymfonyStyle($input, $output);
        $errorsFound = false;

        $io->success('Extracting translations from the application!');

        $availableLocales = $this->getContainer()->getParameter('available_locales', []);
        $availableLocales = array_map(
            function ($locale) {
                return strtolower(substr($locale, 0, 2));
            },
            $availableLocales
        );

        $inputLanguages = explode(',', $input->getArgument('languages'));
        $filteredInput = array();

        if (empty($inputLanguages[0])) {
            $filteredInput = $availableLocales;
            $io->warning('No language found! Will extract all available languages!');
        } else {
            foreach ($inputLanguages as $language) {
                if (in_array($language, $availableLocales)) {
                    $filteredInput[] = $language;
                } else {
                    $io->error("Language $language is not available!");
                    $errorsFound = true;
                }
            }
        }

        $commandName = 'translation:update';
        $command = $this->getApplication()->find($commandName);

        foreach ($filteredInput as $locale) {
            $command->run(
                new ArrayInput([
                    'command' => $commandName,
                    'locale' => $locale,
                    '--force' => true,
                    '--output-format' => 'po']),
                $output
            );
        }

        if (!$errorsFound) {
            $io->success('Translations successfully extracted');
        } else {
            $io->warning('Some errors have occured!');
        }
    }
}
