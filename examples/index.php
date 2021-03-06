<?php

// Use MeguminFramework namespace to gain direct access to its components
use pfcode\MeguminFramework\Dispatcher;
use pfcode\MeguminFramework\View;
use pfcode\MeguminFramework\Controller;

// Use autoloader generated by composer
require __DIR__ . "/../vendor/autoload.php";

class TestController extends Controller{
    public function execute()
    {
        $this->view = new \pfcode\MeguminFramework\ScopeDebugView();

        $this->view->setScope($this->args);

        $this->view->display();
    }
}

// Set global View parameters (page title doesn't have to be honored by chosen View)
View::setPageTitlePrefix("Website Title");
View::setPageTitleSeparator(" : ");

// Specify namespace that your controllers belongs to
Dispatcher::setControllersNamespace(__NAMESPACE__);

// Set controllers that should be called when routing fails
Dispatcher::setFallbackControllers(TestController::class, TestController::class);

// Specify routing table for the dispatcher
// (you can omit this call when you don't want to use friendly URLs)
Dispatcher::setUrlPatterns(array(
    "/test/{id}" => TestController::class,
    "/test" => TestController::class,
    "/" => TestController::class
));

// Perform routing and execute proper controller
Dispatcher::explosion();