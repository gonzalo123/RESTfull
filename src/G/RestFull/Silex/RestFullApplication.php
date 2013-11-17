<?php

namespace G\RestFull\Silex;

use Silex\Application;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        if (!isset($this['class.map.path'])) throw new FatalErrorException("class map not found");
        if (!isset($this['auto.injection.map.path'])) throw new FatalErrorException("auto injection class map not found");

        if (!isset($this['base.path'])) {
            $this['base.path'] = self::DEFAULT_BASE_PATH;
        }

        $this['class.map']          = (new Parser())->parse(file_get_contents($this['class.map.path']));
        $this['auto.injection.map'] = (new Parser())->parse(file_get_contents($this['auto.injection.map.path']));

        $this['call.resource'] = $this->protect(function ($resource, $method, $id = null) {
            if (!isset($this['class.map'][$resource])) throw new BadRequestHttpException("not valid resource");
            $obj = $this->getInstance($this['class.map'][$resource], []);

            $callable = [$obj, $method];
            return call_user_func_array($callable, $this->getDependencies($callable, ['id' => $id]));
        });
    }

    private function getInstance($class, $input)
    {
        $metaClass = new \ReflectionClass($class);

        return $metaClass->hasMethod('__construct') ?
            $metaClass->newInstanceArgs($this->getDependencies([$class, '__construct'], $input)) :
            new $class;
    }

    private function getDependencies($controller, $input)
    {
        $method       = new \ReflectionMethod($controller[0], $controller[1]);
        $dependencies = [];
        foreach ($method->getParameters() as $param) {
            $parameterName = $param->getName();
            if (isset($input[$parameterName])) {
                $dependencies[$parameterName] = $input[$parameterName];
            } else {
                if (isset($param->getClass()->name)) {
                    $dependencies[$parameterName] = $this->create($param->getClass()->name);
                }
            }
        }

        return $dependencies;
    }

    protected function create($class)
    {
        if (!array_key_exists($class, $this['auto.injection.map'])) throw new BadRequestHttpException("not valid resource");

        return $this[$this['auto.injection.map'][$class]];
    }

    private function setUpRoutes()
    {
        $basePath = $this['base.path'];

        $this->get("/{$basePath}/{resource}", function ($resource) {
            return new JsonResponse($this['call.resource']($resource, 'getAll'));
        });

        $this->get("/{$basePath}/{resource}/{id}", function ($resource, $id) {
            return new JsonResponse($this['call.resource']($resource, 'getOne', $id));
        });

        $this->delete("/{$basePath}/{resource}/{id}", function ($resource, $id) {
            return new JsonResponse($this['resource']($resource, 'deleteOne', $id));
        });

        $this->post("/{$basePath}/{resource}", function ($resource) {
            return new JsonResponse($this['resource']($resource, 'addOne'));
        });

        $this->post("/{$basePath}/{resource}/{id}", function ($resource, $id) {
            return new JsonResponse($this['resource']($resource, 'editOne', $id));
        });
    }
} 