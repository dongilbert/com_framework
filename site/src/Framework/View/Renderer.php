<?php

namespace Framework\View;

use Framework\Exceptions\TemplateException;

class Renderer
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $partialData = [];

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Set a value to be used when including a file.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * "magic" proxy for set()
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function addIncludePath($path, $prepend = false)
    {
        if ($prepend)
        {
            array_unshift($this->paths, $path);
        }
        else
        {
            $this->paths[] = $path;
        }
    }

    /**
     * Render the view.
     *
     * @param string $template The template layout filename to load.
     * @return string
     */
    public function render($template = 'default')
    {
        $fileLocation = $this->locateTemplate($template);

        return $this->includeFile($fileLocation);
    }

    protected function locateTemplate($template)
    {
        foreach ($this->paths as $path)
        {
            if (file_exists($path . '/' . $template . '.php'))
            {
                return $path . '/' . $template . '.php';
            }
        }

        throw new TemplateException(sprintf('Location of template (%s) could not be found.', $template));
    }

    /**
     * Convenience function for inclusion of partials (_ prefixed files);
     *
     * @param string $name
     *
     * @return string
     */
    public function partial($name)
    {
        return $this->render('_' . $name);
    }

    /**
     * Extract the view data into
     *
     * @param string $fileLocation
     * @param array $data
     *
     * @return string
     */
    public function includeFile($fileLocation)
    {
        extract($this->data);

        ob_start();
        include $fileLocation;

        return ob_get_clean();
    }

    public function escape($data)
    {
        return htmlspecialchars($data);
    }
}
