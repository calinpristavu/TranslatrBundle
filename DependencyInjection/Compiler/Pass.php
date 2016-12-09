<?php

namespace Evozon\TranslatrBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Pass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $templates = $container->getParameter('data_collector.templates');
        $templates['translation'] = '@EvozonTranslatrBundle/Resources/views/translation.html.twig';
        $container->setParameter('data_collector.templates', $templates);
    }
}