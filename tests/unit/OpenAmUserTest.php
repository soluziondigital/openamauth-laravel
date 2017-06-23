<?php
namespace Maenbn\Tests\OpenAmAuthLaravel;


use Maenbn\OpenAmAuthLaravel\OpenAmUser;
use PHPUnit\Framework\TestCase;

class OpenAmUserTest extends TestCase
{
    public function testUser()
    {
        $user = new OpenAmUser();
        $user->tokenId = '3hjfsa9sdf09';
        $user->password = 'secret';
        $this->assertEquals('tokenId', $user->getAuthIdentifierName());
        $this->assertEquals('3hjfsa9sdf09', $user->getAuthIdentifier());
        $this->assertEquals('secret', $user->getAuthPassword());
        $this->assertEquals('3hjfsa9sdf09', $user->getRememberToken());
        $this->assertEquals('tokenId', $user->getRememberTokenName());
        $user->setRememberToken('newToken');
        $this->assertEquals('newToken', $user->getAuthIdentifier());
    }
}