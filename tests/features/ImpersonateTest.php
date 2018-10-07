<?php

use LaravelEnso\Core\app\Models\User;
use Faker\Factory;
use Tests\TestCase;
use LaravelEnso\MenuManager\app\Models\Menu;
use LaravelEnso\RoleManager\app\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\PermissionManager\app\Models\Permission;

class ImpersonateTest extends TestCase
{
    use RefreshDatabase;

    private $impersonator;
    private $userToImpersonate;

    protected function setUp()
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed();
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
        $this->userToImpersonate = $this->createUser($this->adminRole());

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
        $this->impersonator = $this->createUser($role);
        $this->userToImpersonate = $this->createUser($role);
        $this->actingAs($this->impersonator);
    }

    private function adminRole()
    {
        $menu = Menu::first(['id']);

        $role = factory(Role::class)->create([
            'menu_id' => $menu->id,
        ]);

        $role->permissions()
            ->sync(Permission::pluck('id'));

        return $role;
    }

    private function defaultAccessRole()
    {
        $menu = Menu::first(['id']);

        $role = factory(Role::class)->create([
            'menu_id' => $menu->id,
        ]);

        $role->permissions()
            ->sync(Permission::implicit()->pluck('id'));

        return $role;
    }

    private function createUser($role)
    {
        return factory(User::class)->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}
