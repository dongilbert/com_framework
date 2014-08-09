<?php

namespace Framework\Models;

use Framework\Entities\DetailEntity;

class DetailModel extends AbstractBaseModel
{
    /**
     * @param int $id The integer id for the profile to get.
     *
     * @return DetailEntity
     */
    public function getProfile($id)
    {
	    $query = $this->db->getQuery(true)
            ->select('a.*')
            ->from('#__framework_items AS a')
            ->where('a.state = 1')
	    	->where('a.id = ' . (int) $id);

	    $profile = $this->db->setQuery($query)->loadObject();

        return new DetailEntity($profile);
    }
}
