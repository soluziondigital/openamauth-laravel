<?php

namespace Maenbn\Tests\OpenAmAuthLaravel;


use Illuminate\Contracts\Auth\Authenticatable;
use Maenbn\OpenAmAuth\Contracts\OpenAm;
use Maenbn\OpenAmAuthLaravel\Contracts\UserMapper;
use Maenbn\OpenAmAuthLaravel\Providers\OpenAmUserProvider;
use PHPUnit\Framework\TestCase;

class UserProviderTest extends TestCase
{
    /**
     * @var OpenAmUserProvider
     */
    protected $userProvider;

    /**
     * @var Authenticatable
     */
    protected $mockUser;

    protected function mockUserProvider($tokenValid = true, $authenticationValid = true)
    {
        $mockOpenAm = $this->getMockBuilder(OpenAm::class)->getMock();
        $mockOpenAm->expects($this->any())
            ->method('setTokenId')
            ->will($this->returnSelf());

        $mockOpenAm->expects($this->any())
            ->method('validateTokenId')
            ->will($this->returnValue($tokenValid));

        $mockOpenAm->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($authenticationValid));

        $this->mockUser = $this->getMockBuilder(Authenticatable::class)->getMock();
        $this->mockUser->tokenId = '543425dfgsd';
        $this->mockUser->expects($this->any())
            ->method('getAuthIdentifier')
            ->willReturn('543425dfgsd');

        $mockUserMapper = $this->getMockBuilder(UserMapper::class)->getMock();
        $mockUserMapper->expects($this->any())
            ->method('map')
            ->will($this->returnValue($this->mockUser));

        $this->userProvider = new OpenAmUserProvider($mockOpenAm, $this->mockUser, $mockUserMapper);
    }

    public function testRetrieveById()
    {
        $this->mockUserProvider();
        $user = $this->userProvider->retrieveById('543425dfgsd');
        $this->assertInstanceOf(Authenticatable::class, $user);
        $this->assertObjectHasAttribute('tokenId', $user);
    }

    public function testRetrieveByIdReturnsNull()
    {
        $this->mockUserProvider(false);
        $user = $this->userProvider->retrieveById('543425dfgsd');
        $this->assertNull($user);
    }

    public function testRetrieveByCredentials()
    {
        $this->mockUserProvider();
        $user = $this->userProvider->retrieveByCredentials(['username' => 'abc123', 'password' => 'secret']);
        $this->assertInstanceOf(Authenticatable::class, $user);
        $this->assertObjectHasAttribute('tokenId', $user);
    }

    public function testRetrieveByCredentialsReturnsNull()
    {
        $this->mockUserProvider(false, false);
        $user = $this->userProvider->retrieveByCredentials(['username' => 'abc123', 'password' => 'secret']);
        $this->assertNull($user);
        $this->assertNull($this->userProvider->retrieveByCredentials([]));
    }

    public function testValidateCredentials()
    {
        $this->mockUserProvider();
        $valid = $this->userProvider->validateCredentials(
            $this->mockUser, ['username' => 'abc123', 'password' => 'secret']
        );
        $this->assertTrue($valid);
    }
}