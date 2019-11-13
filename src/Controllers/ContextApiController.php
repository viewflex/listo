<?php

namespace Viewflex\Listo\Controllers;

use App\Http\Controllers\Controller;
use Viewflex\Ligero\Contracts\ContextInterface as Context;
use Viewflex\Ligero\Controllers\HasContextApi;

/**
 * Provides API access to a Context.
 */
class ContextApiController extends Controller
{
    use HasContextApi;
    
    /**
     * @var array
     */
    protected $inputs;
    
    /**
     * @var array
     */
    protected $contexts;

    /**
     * @var Context
     */
    protected $context;

    public function __construct()
    {
        $this->inputs = json_decode(request()->getContent(), true);
        $this->contexts = config('listo.contexts', []);
    }

}
