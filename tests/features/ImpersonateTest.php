<?php

use LaravelEnso\Core\app\Models\User;
use Faker\Factory;
use Tests\TestCase;
use LaravelEnso\Core\app\Models\Owner;
use LaravelEnso\MenuManager\app\Models\Menu;
use LaravelEnso\RoleManager\app\Models\Role;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\PermissionManager\app\Models\Permission;

class ImpersonateTest extends TestCase
{
    use RefreshDatabase, SignIn;

    private $faker;
    private $impersonator;
    private $userToImpersonate;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->seed()
            ->signIn(User::first());

        $this->faker = Factory::create();
    }

    /** @test */
    public function can_impersonate()
    {
        $this->setUpUsers($this->adminRole());

        $this->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(200)
            ->assertSessionHas('impersonating')
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function cant_impersonate_if_is_not_allowed()
    {
        $this->setUpUsers($this->defaultAccessRole());

        $this->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(403);
    }

    /** @test */
    public function cant_impersonate_if_is_impersonating()
    {
        $this->setUpUsers($this->adminRole());

        $this->withSession(['impersonating' => $this->userToImpersonate->id])
            ->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(403);
    }

    /** @test */
    public function cant_impersonate_self()
    {
        $this->userToImpersonate = $this->createUser('userToImpersonate', $this->adminRole());

        $this->actingAs($this->userToImpersonate);

        $this->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(403);
    }

    /** @test */
    public function stop_impersonating()
    {
        $this->setUpUsers($this->adminRole());

        $this->withSession(['impersonating' => $this->userToImpersonate->id])
            ->get(route('core.impersonate.stop', [], false))
            ->assertSessionMissing('impersonating')
            ->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    private function setUpUsers(Role $role)
    {
        $this->impersonator = $this->createUser('impersonator', $role);
        $this->userToImpersonate = $this->createUser('userToImpersonate', $role);

        $this->actingAs($this->impersonator);
    }

    private function adminRole()
    {
        $menu = Menu::first(['id']);

        $role = Role::create([
            'name' => 'adminRole',
            'display_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'menu_id' => $menu->id,
        ]);

        $role->permissions()
            ->sync(Permission::pluck('id'));

        return $role;
    }

    private function defaultAccessRole()
    {
        $menu = Menu::first(['id']);

        $role = Role::create([
            'name' => 'defaultAccessRole',
            'display_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'menu_id' => $menu->id,
        ]);

        $role->permissions()
            ->sync(Permission::implicit()->pluck('id'));

        return $role;
    }

    private function createUser($firstName, $role)
    {
        return User::create([
            'first_name' => $firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'is_active' => 1,
            'email' => $this->faker->email,
            'owner_id' => Owner::first(['id'])->id,
            'role_id' => $role->id,
        ]);
    }
}
