<?php

namespace App\Modules\Property\Tests\Services;

use App\Modules\Property\Repos\PropertyRepo;
use App\Modules\Property\Services\PropertyService;
use Tests\TestCase;

class PropertyServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Property Repo Mock
        $this->propertyRepo = \Mockery::mock(PropertyRepo::class);
        $this->app->instance(PropertyRepo::class, $this->propertyRepo);

        // Setup Service
        // Todo - need to implement mockPartial to able to call protected function
        $this->service = $this->app->make(PropertyService::class);
    }

    public function testFailCreatePropertyWhenDataIsEmpty()
    {
        // Arrange
        $data = [];

        // Expect
        $this->expectExceptionMessage('Data to create cannot be empty');
        $this->propertyRepo->shouldNotReceive('create');

        // Action
        $this->service->create($data);
    }

    public function testCreateProperty()
    {
        // Arrange
        $data = ['hello' => 1];

        // Expect
        $this->propertyRepo->expects()->create($data)->andReturnUsing(function ($data) {
            return $data;
        });

        // Action
        $result = $this->service->create($data);

        // Assert
        $this->assertSame($data, $result);
    }
}
