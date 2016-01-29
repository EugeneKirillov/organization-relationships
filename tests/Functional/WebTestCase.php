<?php
namespace Owr\Tests\Functional;

use Owr\App\App;
use Owr\App\AppFactory;

/**
 * Base class functional tests
 *
 * @package Owr\Tests\Functional
 */
class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /**
    * Application instance
    *
    * @var App
    */
    protected $app;

    /**
     * Test client instance
     *
     * @var WebTestClient
     */
    protected $client;

    /**
     * Setting up test environment
     */
    public static function setUpBeforeClass()
    {
        //TODO: init database
    }

    /**
    * Setting up the application
    */
    protected function setUp()
    {
        $this->app = $this->createApplication();
        $this->setUpDatabase();
        $this->client = $this->createClient();
    }

    /**
    * Creates the application
    *
    * @return App
    */
    public function createApplication()
    {
        return AppFactory::createTestApp(require __DIR__ . '/../../config.php');
    }

    /**
    * Creates a Client
    *
    * @param array $server Server parameters
    *
    * @return WebTestClient A Client instance
    */
    public function createClient(array $server = array())
    {
        return new WebTestClient($this->app, $server);
    }

    /**
     * Assert that response status equals to expected code
     *
     * @param $code
     */
    public function assertStatusCode($code)
    {
        $this->assertEquals($code, $this->client->getStatusCode());
    }

    /**
     * Assert that response content type equals to expected content type
     *
     * @param $type
     */
    public function assertContentType($type)
    {
        $this->assertContains($type, $this->client->getHeader('Content-Type'));
    }

    /**
     * Assert that JSON response equals to expected data
     *
     * @param array $expected
     * @param int $code
     */
    public function assertJsonResponse(array $expected, $code = 200)
    {
        $this->assertStatusCode($code);
        $this->assertContentType('application/json;charset=utf-8');

        $expected = json_encode($expected);
        $actual   = $this->client->getResponse();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Setting up database
     */
    protected function setUpDatabase()
    {
        /** @var \Doctrine\DBAL\Driver\Connection $db */
        $db = $this->app->getContainer()['dbal'];
        $db->beginTransaction();
        $db->exec('DELETE FROM relations');
        $db->exec('DELETE FROM organizations');
        $db->exec('ALTER TABLE organizations AUTO_INCREMENT = 1');
        $db->commit();
    }
}
