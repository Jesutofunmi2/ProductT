<?php

namespace Tests\Controller\Feature;

use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }
    public function test_index_return_pagination_product()
    {

        Product::factory()->count(30)->create();

        $response = $this->getJson( route('products.index') );

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'description',
                             'price',
                             'stock',
                             'files' => [
                                 '*' => ['id', 'filename', 'url'] 
                             ]
                         ]
                     ],
                     'links',
                     'meta'
                 ]);
    }

    public function test_index_returns_empty_when_no_products()
    {
        $response = $this->getJson( route('products.index') );

        $response->assertStatus(200)
                    ->assertJson([
                        'data' => [],
                        'links' => [
                            'first' => 'http://localhost/api/v1/products?page=1',
                            'last' => 'http://localhost/api/v1/products?page=1',
                            'prev' => null,
                            'next' => null
                        ],
                        'meta' => [
                            'current_page' => 1,
                            'from' => null,
                            'last_page' => 1,
                            'links' => [
                                [
                                    'url' => null,
                                    'label' => '&laquo; Previous',
                                    'active' => false
                                ],
                                [
                                    'url' => 'http://localhost/api/v1/products?page=1',
                                    'label' => '1',
                                    'active' => true
                                ],
                                [
                                    'url' => null,
                                    'label' => 'Next &raquo;',
                                    'active' => false
                                ]
                            ],
                            'path' => 'http://localhost/api/v1/products',
                            'per_page' => 15,
                            'to' => null,
                            'total' => 0
                        ]
                    ]);
    }

    public function test_store_creates_product_successfully()
    {
        $file = UploadedFile::fake()->image('product-image.jpg');

        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'stock' => 10,
            'files' => [$file] 
        ];

        $response = $this->postJson( route('products.store'), $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'name',
                         'description',
                         'price',
                         'stock',
                         'files' => [
                             '*' => ['id','name', 'path']
                         ]
                     ]
                 ]);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_store_validation_fails()
    {
        $response = $this->postJson( route('products.store'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'description', 'price', 'stock']);
    }

    public function test_update_product_handles_service_errors()
    {
        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('updateProduct')
                 ->andThrow(new \Exception('Service error'));
        });

        $product = Product::factory()->create();
        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 150.00,
            'stock' => 20
        ];

        $response = $this->putJson( route('products.update', $product->id), $data);

        $response->assertStatus(500);
    }

    public function test_destroy_deletes_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson( route('products.destroy', $product->id));

        $response->assertStatus(204);

    }

    public function test_destroy_handles_non_existent_product()
    {
        $response = $this->deleteJson('/api/v1/products/999999');

        $response->assertStatus(404);
    }
}
