Simple RESTful server with Silex

We define the resources within a yml file:

```
example: \Example\Data
```

And we create the resource extending G\RestFull\Resource\RestFullResource

```php
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
```

The server is based on silex

```php
use G\RestFull\Silex\RestFullApplication;

$app = new RestFullApplication([
    'debug' => true,
    'class.map.path' => __DIR__ . '/config/classMap.yml',
    'base.path' => 'rest' // default value
]);

$app->run();
```