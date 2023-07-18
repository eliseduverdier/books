<?php

namespace App\Tests;

use App\Tests\Util\FunctionalTestCase;

class BookAppTest extends FunctionalTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testList(): void
    {
        $output = $this->crawl('/');
    }

    public function testSee(): void
    {
        $output = $this->crawl('/?slug=vvv_vvv');
    }

    public function testCreate(): void
    {
        $output = $this->crawl('/?action=create');
    }

    public function testEdit(): void
    {
        $output = $this->crawl('/?action=edit&slug=vvv_vvv', 'GET');


        $output = $this->crawl('/?action=edit', 'POST');
    }

    public function testDelete(): void
    {
    }
}
