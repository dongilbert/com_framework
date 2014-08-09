<?php

namespace Framework;

use JApplicationCms;
use Joomla\DI\Container;
use Framework\Exceptions\ControllerResolutionException;

class Dispatcher
{
    /**
     * @var \JApplicationCms
     */
    protected $app;

    /**
     * @var \Joomla\DI\Container
     */
    protected $container;

    /**
     * @param JApplicationCms $app
     * @param Container $container
     */
    public function __construct(JApplicationCms $app, Container $container)
    {
        $this->app = $app;
        $this->container = $container;
    }

    /**
     * Dispatch the application.
     *
     * @param JApplicationCms $app
     *
     * @return string An rendered view from executing the controller.
     */
    public function execute()
    {
        $view = $this->app->input->get('view');

        if ($view === null)
        {
            throw new ControllerResolutionException('No view was specified.');
        }

        $fqcn = __NAMESPACE__ . '\\Controllers\\' . ($this->app->isAdmin() ? 'Admin\\' : '') . ucfirst($view) . 'Controller';

        if (! class_exists($fqcn))
        {
            throw new ControllerResolutionException(sprintf('Controller for view (%s) cannot be found.', $view));
        }

        /** @var \Framework\Controllers\AbstractBaseController $controller */
        $controller = $this->container->buildObject($fqcn);

        $controller->setContainer($this->container);
        $controller->createDefaultModel();
        $controller->createDefaultView();

        return $controller->execute();
    }
}
