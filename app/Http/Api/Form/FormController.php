<?php

namespace App\Http\Api\Form;


use App\Http\Api\Base\BaseController;
use Illuminate\Http\Request;

class FormController extends BaseController
{
    private FormService $service;

    public function __construct()
    {
        $this->service = new FormService();
    }

    public function getFormByName($name, Request $request)
    {
        return $this->service->getFormByName($name, $request);
    }

    public function list(Request $request)
    {
        return $this->service->list($request);
    }

    public function view($id, Request $request)
    {
        return $this->service->view($id);
    }

    public function store(Request $request)
    {
        return $this->service->create($request);
    }

    public function update($id, Request $request)
    {
        return $this->service->update($id, $request);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function addField($form_id, $field_id, Request $request)
    {
        return $this->service->addField($form_id, $field_id, $request);
    }

    public function updateField($form_id, $field_id, Request $request)
    {
        return $this->service->updateField($form_id, $field_id, $request);
    }

    public function removeField($form_id, $field_id, Request $request)
    {
        return $this->service->removeField($form_id, $field_id, $request);
    }

}
