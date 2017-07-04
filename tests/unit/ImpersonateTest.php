<?php

use App\Owner;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LaravelEnso\RoleManager\app\Models\Role;
use Tests\TestCase;

class ImpersonateTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->disableExceptionHandling();
        $this->user = User::first();
        $this->owner = Owner::first(['id']);
        $this->role = Role::first(['id']);
        $this->faker = Factory::create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function start()
    {
        $response = $this->post('/administration/users', $this->postParams());
        $user = User::orderBy('id', 'desc')->first();

        $response = $this->get('/core/impersonate/'.$user->id.'/start');

        $response->assertStatus(302);
        $this->hasSessionConfirmation($response);
    }

    /** @test */
    public function stop()
    {
        $response = $this->get('/core/impersonate/stop');

        $response->assertStatus(302);
        $this->hasSessionConfirmation($response);
    }

    private function hasSessionConfirmation($response)
    {
        return $response->assertSessionHas('flash_notification');
    }

    private function postParams()
    {
        return [
            'first_name'   => $this->faker->firstName,
            'last_name'                  => $this->faker->lastName,
            'email'               => $this->faker->email,
            'owner_id'               => $this->owner->id,
            'role_id'           => $this->role->id,
            'phone'                  => $this->faker->phoneNumber,
            'is_active'               => 1,
            '_method'               => 'POST',
        ];
    }
}
