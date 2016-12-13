<?php

namespace Evozon\TranslatrBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class Pass
 *
 * @package     Evozon\TranslatrBundle\DependencyInjection\Compiler
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class Pass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $templates = $container->getParameter('data_collector.templates');
        $templates['data_collector.translation'][1] = '@EvozonTranslatrBundle/Resources/views/translation.html.twig';
        $container->setParameter('data_collector.templates', $templates);
    }
}
