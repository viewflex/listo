<?php

namespace Viewflex\Listo\Base;

use App\Http\Controllers\Controller;
use Viewflex\Ligero\Publishers\HasPublisherSession;
use Viewflex\Ligero\Utility\RouteHelperTrait;
use Viewflex\Listo\Publishers\HasFluentConfiguration;
use Viewflex\Listo\Publishers\HasPublisher;
use Viewflex\Listo\Publishers\HasPublisherUi;
use Viewflex\Listo\Utility\BootstrapUiTrait;

/**
 * Extend this class, create and configure a Publisher,
 * to perform domain CRUD actions via stateful web UI.
 */
abstract class BasePublisherController extends Controller
{
    use BootstrapUiTrait;
    use HasFluentConfiguration;
    use HasPublisher;
    use HasPublisherSession;
    use HasPublisherUi;
    use RouteHelperTrait;
    
}
