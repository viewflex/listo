<?php

namespace Viewflex\Listo\Publish\Demo\Items;

use Viewflex\Listo\Base\BasePublisherController;
use Viewflex\Listo\Publish\Demo\Items\ItemsConfig as Config;
use Viewflex\Listo\Publish\Demo\Items\ItemsRequest as Request;
use Viewflex\Listo\Publish\Demo\Items\ItemsRepository as Query;

class ItemsController extends BasePublisherController
{
    public function __construct(Config $config, Request $request, Query $query)
    {
        $this->createPublisher($config, $request, $query);
    }
    
}
