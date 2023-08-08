<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCustomerIndexReturnsStatus200ForSuccess() {
        Sanctum::actingAs(
            User::factory()->create(),
        );

        $response = $this->getJson('api/v1/customers');

        $response->assertOk();
    }    
    
    public function testCustomerIndexReturnsStatus401ForUnauthenticatedUser() {
        $response = $this->getJson('api/v1/customers');

        $response->assertUnauthorized();
    }

    /**
     * @testWith [1]
     * [2]
     * [10]
     * @testdox test customer index returns $amount elements
     */
    public function testCustomerIndexReturnsAppropriateNumberOfRecords(int $amount) {
        Sanctum::actingAs(
            User::factory()->create(),
        );

        Customer::factory($amount)->create();

        $response = $this->getJson('api/v1/customers');

        $response->assertJsonCount($amount, 'data');
    }

    public function testCustomerIndexReturnAppropriateJsonStructure() {
        Sanctum::actingAs(
            User::factory()->create(),
        );

        Customer::factory()->create();

        $response = $this->getJson('api/v1/customers');
        //$response->dd();
        $response->assertJsonStructure([
           "data" => [
            '*' => [
                "id",
                "name",
                "type",
                "email",
                "city",
                "state",
                "postalCode",
            ]
           ]
        ]);
    } 

    public function testCustomerStoreReturnsStatus201ForCreate() {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $response = $this->postJson('api/v1/customers', [
            "name" => "Mr. Donald Heller",
            "type" => "I",
            "email" => "shanel52@glover.biz",
            "address" => 'Barcza 58/91',
            "city" => "New Madisonville",
            "state" => "New Hampshire",
            "postal_code" => "39472"
        ]);

        $response->assertCreated();
    }  

    /**
     * @dataProvider customerTypeDataProvider
     * @testdox test customer store validates returns $type elements
     */
    #[DataProvider('customerTypeDataProvider')]
    #[TestDox('test customer store validates returns $type elements')]
    public function testCustomerStoreValidatesTypeField($type) {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $response = $this->postJson('api/v1/customers', [
            "name" => "Mr. Donald Heller",
            "type" => $type,
            "email" => "shanel52@glover.biz",
            "address" => 'Barcza 58/91',
            "city" => "New Madisonville",
            "state" => "New Hampshire",
            "postal_code" => "39472"
        ]);
       $response->assertValid('type');
    }

    public static function customerTypeDataProvider(): array
    {
        return [
            'Individual' => ['I'],
            'individual' => ['i'],
            'Business' => ['B'],
            'business' => ['b']
        ];
    }
}
