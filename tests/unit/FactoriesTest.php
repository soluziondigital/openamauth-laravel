<?php

namespace Maenbn\Tests\OpenAmAuthLaravel;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuth\Contracts\Config;
use Maenbn\OpenAmAuth\Contracts\OpenAm;
use Maenbn\OpenAmAuthLaravel\Contracts\UserMapper;
use Maenbn\OpenAmAuthLaravel\Contracts\UserRepository;
use Maenbn\OpenAmAuthLaravel\Factories\ConfigFactory;
use Maenbn\OpenAmAuthLaravel\Factories\ModelFactory;
use Maenbn\OpenAmAuthLaravel\Factories\UserMapperFactory;
use Maenbn\OpenAmAuthLaravel\Factories\UserProviderFactory;
use Maenbn\OpenAmAuthLaravel\Factories\UserRepositoryFactory;
use Maenbn\OpenAmAuthLaravel\OpenAmUser;
use Maenbn\OpenAmAuthLaravel\Providers\OpenAmUserProvider;
use PHPUnit\Framework\TestCase;

class FactoriesTest extends TestCase
{

    protected $config = [
        'domain' => null,

        'uri' => null,

        'realm' => null,

        'cookiePath' => null,

        'cookieDomain' => null,

        'cookieName' => null,

        'eloquentUser' => null,

        'eloquentUidName' => 'uid'
    ];

    public function testConfigFactory()
    {
        $configObject = ConfigFactory::create($this->config);
        $this->assertInstanceOf(Config::class, $configObject);
    }

    public function testModelFactory()
    {
        $model = ModelFactory::create();
        $this->assertInstanceOf(OpenAmUser::class, $model);
        $mockModel = $this->getMockBuilder(Model::class)->getMock();
        $this->assertInstanceOf(Model::class, ModelFactory::create($mockModel));
    }

    public function testUserMapperFactory()
    {
        $this->assertInstanceOf(UserMapper::class, UserMapperFactory::create());
    }

    public function testUserProviderFactory()
    {
        $mockOpenAm = $this->getMockBuilder(OpenAm::class)->getMock();
        $this->assertInstanceOf(OpenAmUserProvider::class, UserProviderFactory::create($this->config,$mockOpenAm));
    }

    public function testUserRepositoryFactory()
    {
        $this->assertNull(UserRepositoryFactory::create('uid'));
        $mockModel = $this->getMockBuilder(Model::class)->getMock();
        $this->assertInstanceOf(UserRepository::class, UserRepositoryFactory::create('uid', $mockModel));
    }
}