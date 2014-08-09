<?php

namespace Framework\Models;

use JDatabaseQuery;
use Framework\Entities\DetailEntity;

class ListModel extends AbstractBaseModel
{
    /**
     * @param null $offset
     * @param int $limit
     *
     * @return DetailEntity[]
     */
    public function getItems($offset = 0, $limit = 10)
    {
        $query = $this->db->getQuery(true)
            ->select('a.*, a.id')
            ->from('#__framework_items AS a')
            ->where('a.state = 1');

        $this->addFilters($query);

        $ordering = $this->getState('list.ordering', 'a.id');
        $orderDir = $this->getState('list.order_dir', 'ASC');

        $query->order($ordering . ' ' . $orderDir);

        return array_map(
            function($profile)
            {
                return new DetailEntity($profile);
            },
            (array) $this->db->setQuery($query, $offset, $limit)->loadObjectList()
        );
    }

    public function getTotalEntries()
    {
        $query = $this->db->getQuery(true)
            ->select('COUNT(a.id)')
            ->from('#__framework_items AS a')
            ->where('a.state = 1');

        $this->addFilters($query);

        return (int) $this->db->setQuery($query)->loadResult();
    }

    /**
     * Modify the query based on model state
     *
     * @param $query JDatabaseQuery
     */
    protected function addFilters(&$query)
    {
        $filterState = $this->getState('filter.state');

        if ($filterState)
        {
            $query->where('a.state = ' . $this->db->quote($this->db->escape($filterState)));
        }
    }
}
