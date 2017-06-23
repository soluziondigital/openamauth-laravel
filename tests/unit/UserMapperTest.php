<?php

namespace Maenbn\Tests\OpenAmAuthLaravel;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuth\Contracts\OpenAm;
use Maenbn\OpenAmAuthLaravel\Contracts\UserRepository;
use Maenbn\OpenAmAuthLaravel\Mappers\UserMapper;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var OpenAm
     */
    protected $mockOpenAm;

    /**
     * @var Authenticatable
     */
    protected $mockUser;

    public function mockStubs($withRepository = false)
    {
        $openAmUser = new \stdClass();
        $openAmUser->name = 'Test McTest';
        $openAmUser->mail = ["test@test.com"];

        $this->mockOpenAm = $this->getMockBuilder(OpenAm::class)->getMock();
        $this->mockOpenAm->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($openAmUser));
        $this->mockOpenAm->expects($this->any())
            ->method('getTokenId')
            ->will($this->returnValue('3jf89fah3nf0'));

        $this->mockUser = $this->getMockBuilder(Authenticatable::class)->getMock();
        $this->mockUser->expects($this->any())
            ->method('getAuthIdentifierName')
            ->will($this->returnValue('tokenId'));
        $this->mockUser->uid = 'abc123';

        $mockUserRepository = null;
        if($withRepository){
            $mockModel = $this->getMockBuilder(Model::class)->getMock();
            $mockModel->expects($this->any())
                ->method('toArray')
                ->will($this->returnValue(['username' => 'abc123']));

            $mockUserRepository = $this->getMockBuilder(UserRepository::class)->getMock();
            $mockUserRepository->expects($this->any())
                ->method('findByUid')
                ->will($this->returnValue($mockModel));
        }

        $this->userMapper = new UserMapper($mockUserRepository);
    }

    public function testMapperWhenRepositoryIsNull()
    {
        $this->mockStubs();
        $user = $this->userMapper->map($this->mockOpenAm, $this->mockUser);
        $this->assertInstanceOf(Authenticatable::class, $user);
        $this->assertObjectHasAttribute('name', $user);
        $this->assertObjectHasAttribute('tokenId', $user);
    }

    public function testMapperWithRepository()
    {
        $this->mockStubs(true);
        $user = $this->userMapper->map($this->mockOpenAm, $this->mockUser);
        $this->assertInstanceOf(Authenticatable::class, $user);
        $this->assertObjectHasAttribute('name', $user);
        $this->assertObjectHasAttribute('tokenId', $user);
    }
}