<?php

namespace Framework\Controllers;

use Framework\Models\AbstractBaseModel;
use Framework\View\Renderer;
use Joomla\DI\Container;

abstract class AbstractBaseController
{
    /**
     * @var \JApplicationCms
     */
    protected $app;

    /**
     * @var \JInput
     */
    protected $input;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Renderer
     */
    protected $view;

    /**
     * @var AbstractBaseModel
     */
    protected $model;

    /**
     * @var string The name of this controller
     */
    protected $name;

    /**
     * @param \JApplicationCms $app
     * @param \JInput $input
     */
    public function __construct(\JApplicationCms $app, \JInput $input)
    {
        $this->app = $app;
        $this->input = $this->app->input;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Execute this controller.
     *
     * @return string A rendered view
     */
    abstract public function execute();

    /**
     * @param array $data
     * @return Renderer
     */
    public function createView(array $data = array())
    {
        $renderer = new Renderer($data);

        $name = $this->getName();

        // Add the default view path
        $renderer->addIncludePath(COMPONENT_ROOT . '/src/views/' . $this->getName());

        $template = $this->app->getTemplate();
        $option = $this->input->get('option');

        // Prepend the template path
        $renderer->addIncludePath(JPATH_ROOT . '/templates/' . $template . '/html/' . $option . '/' . $this->getName(), true);

        return $renderer;
    }

    public function createDefaultModel()
    {
        $this->model = $this->createModel();
    }

    public function createModel($name = null)
    {
        $name or $name = $this->getName();

        $className = 'Framework\\Models\\' . ucfirst($name) . 'Model';

        if (! class_exists($className))
        {
            return false;
        }

        return $this->container->buildObject($className);
    }

    public function createDefaultView()
    {
        $this->view = $this->createView();
    }

    protected function getName()
    {
        if ($this->name === null)
        {
            $parts = explode('\\', get_class($this));
            $className = array_pop($parts);
            $pos = strpos($className, 'Controller');

            $this->name = lcfirst(substr($className, 0, $pos));
        }

        return $this->name;
    }
}
