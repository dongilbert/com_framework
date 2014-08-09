<?php
/**
 * @version     1.0.0
 * @package     com_framework
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     MIT
 */

// no direct access
defined('_JEXEC') or die;

class FrameworkRouter extends JComponentRouterBase
{
    protected $cache = [
        'Itemids' => []
    ];

    /**
     * Build the route for the com_banners component
     *
     * @param   array  &$query  An array of URL arguments
     *
     * @return  array  The URL arguments to use to assemble the subsequent URL.
     *
     * @since   3.3
     */
    public function build(&$query)
    {
        $segments = array();

        if (isset($query['view']))
        {
            if ($query['view'] !== 'list')
            {
                $segments[] = $query['view'];
            }

            unset($query['view']);
        }

        if (isset($query['id']))
        {
            $segments[] = $query['id'];
            unset($query['id']);
        }

        if (isset($query['layout']))
        {
            $segments[] = $query['layout'];
            unset($query['layout']);
        }

        if (isset($query['start']))
        {
            $limit = isset($query['limit']) ? $query['limit'] : 10;
            $page = ($query['start'] / $limit) + 1;

            if ($page > 1)
            {
                $segments[] = 'page-' . $page;
            }

            unset($query['start']);
            unset($query['limit']);
        }

        if (! isset($query['Itemid']))
        {
            $query['Itemid'] = 105;
        }

        $total = count($segments);

        for ($i = 0; $i < $total; $i++)
        {
            $segments[$i] = str_replace(':', '-', $segments[$i]);
        }

        return $segments;
    }

    /**
     * Parse the segments of a URL.
     *
     * @param   array  &$segments  The segments of the URL to parse.
     *
     * @return  array  The URL attributes to be used by the application.
     *
     * @since   3.3
     */
    public function parse(&$segments)
    {
        $total = count($segments);
        $vars = array();

        for ($i = 0; $i < $total; $i++)
        {
            $segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
        }

        // view is always the first element of the array
        while ($segment = array_shift($segments))
        {
            if (is_numeric($segment))
            {
                $vars['id'] = $segment;
            }
            elseif (strpos($segment, 'page') === 0)
            {
                $vars['limitstart'] = $vars['start'] = ((preg_replace('/[^0-9]/', '', $segment) - 1) * 10);
            }
            else
            {
                $vars['view'] = $segment;
            }
        }

        return $vars;
    }

    /**
     * Get the itemid for the requested view.
     *
     * @param $view
     *
     * @return int
     */
    protected function getMenuItemIdForView($view)
    {
        if (! isset($this->cache['Itemids'][$view]))
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('id')
                ->from('#__menu')
                ->where('link LIKE "%' . $db->escape($view) . '%"');

            $this->cache['Itemids'][$view] = (int) $db->setQuery($query)->loadResult();
        }

        return $this->cache['Itemids'][$view];
    }
}

/**
 * Frameworks router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function FrameworkBuildRoute(&$query)
{
    $router = new FrameworkRouter;

    return $router->build($query);
}

function FrameworkParseRoute($segments)
{
    $router = new FrameworkRouter;

    return $router->parse($segments);
}

