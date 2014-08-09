<?php

namespace Framework\Controllers;

use JPagination as Paginator;

class ListController extends AbstractBaseController
{
    /**
     * @var \Framework\Models\ListModel
     */
    protected $model;

    public function execute()
    {
        $data = [];
        $offset = $this->input->getInt('limitstart', 0);
        $limit = $this->input->getInt('limit', 10);
        $filterState = $this->app->getUserStateFromRequest('filter.state', 'filter_state', null, 'cmd');

        $this->model->setState('filter.state', $filterState);

        $data['items'] = $this->model->getItems($offset, $limit);
        $data['pagination'] = new Paginator($this->model->getTotalEntries(), $offset, $limit);

        return $this->view->setData($data)->render();
    }
}
