<?php
namespace Tests\Domain\Customers;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use VueFileManager\Subscription\Support\EngineManager;
use Tests\Mocking\Stripe\CreateCustomerStripeMocksClass;
use Tests\Mocking\Stripe\UpdateCustomerStripeMocksClass;
use Tests\Mocking\PayStack\CreateCustomerPaystackMocksClass;
use Tests\Mocking\PayStack\UpdateCustomerPaystackMocksClass;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;

class CustomersTest extends TestCase
{
    public Model $user;
    public EngineManager $subscription;

    public function setUp(): void
    {
        parent::setUp();

        $this->subscription = resolve(EngineManager::class);
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function it_create_customer()
    {
        resolve(CreateCustomerPaystackMocksClass::class)($this->user);
        resolve(CreateCustomerStripeMocksClass::class)($this->user);

        // Create customer drivers
        collect(['stripe', 'paystack'])
            ->each(function ($driver) {
                $response = $this->subscription->driver($driver)
                    ->createCustomer([
                        'id'      => $this->user->id,
                        'email'   => $this->user->email,
                        'name'    => 'John',
                        'surname' => 'Doe',
                        'phone'   => '+421 950 123 456',
                    ]);

                $this->assertTrue($response->ok());
            });

        $this
            ->assertDatabaseHas('customers', [
                'user_id' => $this->user->id,
                'driver'  => 'paystack',
            ])
            ->assertDatabaseHas('customers', [
                'user_id' => $this->user->id,
                'driver'  => 'stripe',
            ]);
    }

    /**
     * @test
     */
    public function it_update_customer()
    {
        // Create customer drivers
        collect(['stripe', 'paystack'])
            ->each(
                fn ($driver) => Customer::create([
                    'user_id'        => $this->user->id,
                    'driver_user_id' => Str::random(),
                    'driver'         => $driver,
                ])
            );

        resolve(UpdateCustomerPaystackMocksClass::class)($this->user);
        resolve(UpdateCustomerStripeMocksClass::class)($this->user);

        // Check if response is ok
        collect(['stripe', 'paystack'])
            ->each(function ($driver) {
                $response = $this->subscription
                    ->driver($driver)
                    ->updateCustomer([
                        'id'      => $this->user->id,
                        'email'   => $this->user->email,
                        'name'    => 'Jane',
                        'surname' => 'Does',
                        'phone'   => '+421 950 456 123',
                    ]);

                $this->assertTrue($response->ok());
            });
    }
}
