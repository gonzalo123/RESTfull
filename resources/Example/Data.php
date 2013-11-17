<?php

namespace Example;

use Symfony\Component\HttpFoundation\Request;

class Data
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getAll()
    {
        return [
            ['id' => 0, 'name' => 'Peter Parker'],
            ['id' => 1, 'name' => 'Clark Kent'],
        ];
    }

    public function getOne($id)
    {
        return ['id' => $id, 'name' => 'Clark Kent ' . $this->request->get('a')];
    }

    public function deleteOne($id)
    {
        return [];
    }

    public function addOne()
    {
        $payload = $this->getPayload();
        return [];
    }

    public function editOne($id)
    {
        return [];
    }
}