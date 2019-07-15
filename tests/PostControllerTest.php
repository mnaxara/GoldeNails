<?php
// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use App\Service\Month;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;

class PostControllerTest extends WebTestCase
{
    public function testShowPost()
    {
        $this->assertEquals(1,1);
    }
}
