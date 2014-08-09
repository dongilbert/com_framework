<?php

namespace Framework\Controllers;

class DetailController extends AbstractBaseController
{
    /**
     * @var \Framework\Models\DetailModel
     */
    protected $model;

    public function execute()
    {
        $data = [];
        $id = $this->input->getInt('id');
        $layout = $this->input->getCmd('layout', 'list');
        $data['item'] = $this->model->getItem($id);

        return $this->view->setData($data)->render($layout);
    }
}
