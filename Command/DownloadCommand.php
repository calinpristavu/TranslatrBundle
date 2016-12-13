<?php

namespace Evozon\TranslatrBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DownloadCommand
 *
 * @package     Evozon\TranslatrBundle\Command
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class DownloadCommand extends AbstractCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('evozon:translatr:download')
            ->setDescription('Download translations from OneSky')
            ->setDefinition([new InputOption(
                'clear-cache',
                null,
                InputOption::VALUE_NONE,
                'Clear the cache after dump'
            )]);
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

        $io->success("Downloading translations from client");

        $this->getContainer()
            ->get('downloader')
            ->download();

        $io->success("Translations successfully downloaded from client");

        if ($input->getOption('clear-cache')) {
            $io->success("Clearing cache after dumping translations");
            $this->clearCache($output);
        }
    }

    /**
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function clearCache(OutputInterface $output)
    {
        $this->runCommand($output, 'cache:clear');
    }
}
