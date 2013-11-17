Simple RESTfull server with Silex

We define the resources within a yml file:

```
example: \Example\Data
```

We also define the auto dependencies:

```
Symfony\Component\HttpFoundation\Request: request
Doctrine\DBAL\Connection: db
```

And we create the resource extending G\RestFull\Resource\RestFullResource
We can define parameters in constructor or in request funcions (getOne, getAll, deleteOne, addOne, editOne) parameters to be taken from DIC
```php
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
        return [];
    }

    public function editOne($id)
    {
        return [];
    }
}
```

The server is based on silex

```php
use G\RestFull\Silex\RestFullApplication;

$app = new RestFullApplication([
    'debug' => true,
    'class.map.path' => __DIR__ . '/config/resourceClassMap.yml',
    'auto.injection.map.path' => __DIR__ . '/config/autoDependenciesClassMap.yml',
    'base.path' => 'rest' // default value
]);

$app->run();
```