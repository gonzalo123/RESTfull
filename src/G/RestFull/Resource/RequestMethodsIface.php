<?php

namespace G\RestFull\Resource;

use Symfony\Component\HttpFoundation\Request;

interface RequestMethodsIface
{
    public function getAll();

    public function getOne($id);

    public function deleteOne($id);

    public function addOne();

    public function editOne($id);
}