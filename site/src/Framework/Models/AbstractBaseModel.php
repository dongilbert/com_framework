<?php

namespace Framework\Models;

use JDatabaseDriver;
use Joomla\Registry\Registry;

abstract class AbstractBaseModel
{
    /**
     * @var \JDatabaseDriver
     */
    protected $db;

    /**
     * @var \Joomla\Registry\Registry
     */
    protected $state;

    /**
     * @param JDatabaseDriver $db
     * @param Registry $state
     */
    public function __construct(JDatabaseDriver $db, Registry $state)
    {
        $this->db = $db;
        $this->state = $state;
    }

    public function setState($key, $value)
    {
        $this->state->set($key, $value);
    }

    public function getState($key, $default = null)
    {
        return $this->state->get($key, $default);
    }
}
