<?php

namespace Viewflex\Listo\Base;

use Viewflex\Ligero\Contracts\ContextInterface;
use Viewflex\Ligero\Publishers\HasPublisherContext;
use Viewflex\Ligero\Utility\ArrayHelperTrait;
use Viewflex\Listo\Publishers\HasFluentConfiguration;
use Viewflex\Listo\Publishers\HasPublisher;

/**
 * Extend this class and configure its default Publisher,
 * to perform both simple and contextual CRUD actions.
 */
abstract class BaseContext implements ContextInterface
{
    
    use ArrayHelperTrait;
    use HasFluentConfiguration;
    use HasPublisher;
    use HasPublisherContext;
    
    /**
     * Create base context with default publisher components.
     */
    public function __construct()
    {
        $this->createPublisherWithDefaults();
    }

}
