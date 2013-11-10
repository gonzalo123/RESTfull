<?php

namespace G\RestFull\Silex;

use Silex\Application;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\HttpFoundation\JsonResponse;
use G\RestFull\Resource\RestFullResource;

class RestFullApplication extends Application
{
    const DEFAULT_BASE_PATH = '/rest';

    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->configure();
        $this->setUpRoutes();
    }

    private function configure()
    {
        if (!isset($this['class.map.path']))  throw new FatalErrorException("class map not found");

        if (!isset($this['base.path'])) {
            $this['base.path'] = self::DEFAULT_BASE_PATH;
        }

        $this['class.map'] = (new Parser())->parse(file_get_contents($this['class.map.path']));

        $this['resource'] = $this->protect(function ($resource) {
            if (!isset($this['class.map'][$resource])) throw new BadRequestHttpException("not valid resource");
            $obj = new $this['class.map'][$resource];

            if (!($obj instanceof RestFullResource)) throw new BadRequestHttpException("not valid resource");
            $obj->setRequest($this['request']);

            return $obj;
        });
    }

    private function setUpRoutes()
    {
        $basePath = $this['base.path'];

        $this->get("/{$basePath}/{resource}", function ($resource) {
            return new JsonResponse($this['resource']($resource)->getAll());
        });

        $this->get("/{$basePath}/{resource}/{id}", function ($resource, $id) {
            return new JsonResponse($this['resource']($resource)->getOne($id));
        });

        $this->delete("/{$basePath}/{resource}/{id}", function ($resource, $id) {
            return new JsonResponse($this['resource']($resource)->deleteOne($id));
        });

        $this->post("/{$basePath}/{resource}", function ($resource) {
            return new JsonResponse($this['resource']($resource)->addOne());
        });

        $this->post("/{$basePath}/{resource}/{id}", function ($resource, $id) {
            return new JsonResponse($this['resource']($resource)->editOne($id));
        });
    }
} 