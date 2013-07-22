Installation
===========================================
Add this link to your composer.json file:

```
"require": {

  ..
  
  "tfox/di-tools-bundle": "1.0.0"
  
  ..
  
}
```

...and run
php composer.json update


The next step is to add to AppKernel.php the next line:

```
$bundles = array(
  ...
  new TFox\DiToolsBundle\TFoxDiToolsBundle()
  ...
);

```


Usage
===========================================

<b>Injection of a service into super class and all sub class controllers:</b>


Here is provided a simple example of dependency injection using DiToolsBundle. For instance, we have two controllers:


1. TestBundle\Controller\SuperController : A super class controller with protected property "entityManager". Our purpose is to inject here an Entity Manager so that it would be visible in sub class controllers.



2. TestBundle\Controller\SubController, which extends a SuperController and uses its entity manager.


Our code will be like below:

```
//SuperController.php

namespace TestBundle\Controller;

use TFox\DiToolsBundle\Annotation as Di;
use TFox\DiToolsBundle\Controller\DiToolsInjectableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SuperController extends Controller implements DiToolsInjectableInterface {

  /**
	 * @Di\Inject("doctrine.orm.default_entity_manager")
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;
}


//SubController.php

namespace TestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @Sensio\Route("/sub")
 */
class SubController extends SuperController {
  /**
	 * @Sensio\Route
	 * @Sensio\Template
	 */
	public function indexAction()
	{
    //Any operations with injected entity manager here. For instance we will get some article
		$article = $this->entityManager->getRepository('TestBundle:Article')
			->findOneById(1);
		
		return array('article' => $article);
	}
}


```

