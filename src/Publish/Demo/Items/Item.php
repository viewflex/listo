<?php

namespace Viewflex\Listo\Publish\Demo\Items;

use Viewflex\Listo\Base\BaseModel;

class Item extends BaseModel {
    
    protected $table = 'listo_items';

    /**
     * The Presenter class for generating formatted content from this model.
     *
     * @var string
     */
    protected $presenter = 'Viewflex\Listo\Publish\Demo\Items\ItemPresenter';
    
}
