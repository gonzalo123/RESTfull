<?php

namespace Example;

use G\RestFull\Resource\RestFullResource;

class Data extends RestFullResource
{
    public function getAll()
    {
        return [
            ['id' => 0, 'name' => 'Peter Parker'],
            ['id' => 1, 'name' => 'Clark Kent'],
        ];
    }

    public function getOne($id)
    {
        $request = $this->getRequest();
        return ['id' => 1, 'name' => 'Clark Kent'];
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