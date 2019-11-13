<?php

namespace Viewflex\Listo\Publish\Demo\Items;

use Viewflex\Ligero\Base\BasePresenter;
use Viewflex\Ligero\Exceptions\PresenterException;

class ItemPresenter extends BasePresenter
{
    /**
     * Returns an array of dynamic fields for current item.
     *
     * @return array
     * @throws PresenterException
     */
    public function dynamicFields()
    {
        $data = [];
        $this->requireConfig();

        $data['price'] = $this->price();
        $data['alt_price'] = $this->config->getUnitConversions() ? $this->altPrice() : '';

        return $data;
    }

}
