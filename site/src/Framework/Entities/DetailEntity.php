<?php

namespace Framework\Entities;

class DetailEntity extends AbstractBaseEntity
{
    public function getLink()
    {
        return \JRoute::_('index.php?option=com_framework&view=detail&id=' . $this->entityData['id']);
    }
}
