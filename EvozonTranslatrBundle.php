<?php

namespace Evozon\TranslatrBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EvozonTranslatrBundle
 *
 * @package   Evozon\TranslatrBundle
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class EvozonTranslatrBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Pass());
    }
}
