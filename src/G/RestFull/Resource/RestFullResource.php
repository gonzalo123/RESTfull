<?php

namespace G\RestFull\Resource;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

abstract class RestFullResource implements AcceptRequestIface, RequestMethodsIface
{
    protected $request;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    protected function getPayload()
    {
        return json_decode($this->request->getContent());
    }

    protected function getRequest()
    {
        return $this->request;
    }

    public function getAll()
    {
        throw new MethodNotAllowedException('method not implemented for this resource');
    }

    public function getOne($id)
    {
        throw new MethodNotAllowedException('method not implemented for this resource');
    }

    public function deleteOne($id)
    {
        throw new MethodNotAllowedException('method not implemented for this resource');
    }

    public function addOne()
    {
        throw new MethodNotAllowedException('method not implemented for this resource');
    }

    public function editOne($id)
    {
        throw new MethodNotAllowedException('method not implemented for this resource');
    }
}