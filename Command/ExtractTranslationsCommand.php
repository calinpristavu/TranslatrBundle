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
            ->addOption(
                'configs',
                null,
                InputOption::VALUE_OPTIONAL,
                'Configure what will be extracted. Support arrays as comma separated values. Defaults to all available configurations.',
                null
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

        $locales = $this->getContainer()->getParameter('available_locales', []);
        $locales = array_map(
            function ($locale) {
                return strtolower(substr($locale, 0, 2));
            },
            $locales
        );

        $commandName = 'translation:update';
        $command = $this->getApplication()->find($commandName);
        
        foreach ($locales as $locale) {
            $command->run(new ArrayInput(['command' => $commandName, 'locale' => $locale, '--dump-messages' => true]), $output);
        }

        $output->writeln("<info>Translations successfully extracted</info>");
    }
}
