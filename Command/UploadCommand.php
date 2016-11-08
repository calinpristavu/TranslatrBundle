<?php

namespace Evozon\TranslatrBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class UploadCommand
 *
 * @package   Evozon\TranslatrBundle\Command
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class UploadCommand extends AbstractCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('evozon:translatr:upload')
            ->setDescription('Upload translations into OneSky');
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

        $io->success('Uploading translations to client');

        //evozon_translatr_uploader
        $this->getContainer()
            ->get('uploader')
            ->upload();

        $io->success('Translations successfully updated in client');
    }
}
