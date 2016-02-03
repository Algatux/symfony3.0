<?php
declare(strict_types=1);

namespace Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;


class TestCase extends WebTestCase
{

    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        ini_set('xdebug.max_nesting_level', '250');
        parent::__construct($name, $data, $dataName);
        $this->initialize();
    }

    /**
     * Do not EVER forget to call parent::setUp() if you override this method!
     */
    public function setUp()
    {
        parent::setUp();
        $this->getEntityManager()->getConnection()->setTransactionIsolation(Connection::TRANSACTION_READ_COMMITTED);
        $this->getEntityManager()->beginTransaction();
    }

    /**
     * Do not EVER forget to call parent::tearDown() if you override this method!
     */
    public function tearDown()
    {
        if ($this->getEntityManager()) {
            $this->getEntityManager()->rollback();
            $conn = $this->getEntityManager()->getConnection();
            $conn->close();
        }
        parent::tearDown();
    }

    /**
     * Will return the entity manager.
     * using the doctrine em naming convention will try to fetch the service
     * doctrine.orm.{entityManagername}_entity_manager if exist.
     *
     * @param null $entityManagerName the name of the desired entity manager or null for the default one
     *
     * @return EntityManagerInterface
     *
     */
    protected function getEntityManager($entityManagerName = null)
    {
        if (!$entityManagerName){
            return $this->entityManager;
        }
        $entityManagerServiceName = 'doctrine.orm'.$entityManagerName.'_entity_manager';
        $entityManager = $this->getContainer()->get($entityManagerServiceName, Container::NULL_ON_INVALID_REFERENCE);
        if (!$entityManager instanceof EntityManagerInterface) {
            throw new \BadMethodCallException(
                sprintf(
                    'There is no entity manager with the following name: %s. Please check your doctrine cofiguration',
                    $entityManagerName
                )
            );
        }
        return $entityManager;
    }

    /**
     *
     */
    private function initialize()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    }
    
}
