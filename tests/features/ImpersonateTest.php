<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\Menus\Models\Menu;
use LaravelEnso\Permissions\Models\Permission;
use LaravelEnso\Roles\Models\Role;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImpersonateTest extends TestCase
{
    use RefreshDatabase;

    private $impersonator;
    private $userToImpersonate;
    private $guard;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->withoutMiddleware(LaravelEnso\ControlPanelApi\Http\Middleware\RequestMonitor::class);

        $this->guard = 'web';
    }

    #[Test]
    public function can_impersonate()
    {
        $this->setUpUsers($this->adminRole());

        $this->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(200)
            ->assertSessionHas('impersonating')
            ->assertJsonStructure(['message']);
    }

    #[Test]
    public function cant_impersonate_if_is_not_allowed()
    {
        $this->setUpUsers($this->defaultAccessRole());

        $this->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(403);
    }

    #[Test]
    public function cant_impersonate_if_is_impersonating()
    {
        $this->setUpUsers($this->adminRole());

        $this->withSession(['impersonating' => $this->userToImpersonate->id])
            ->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(403);
    }

    #[Test]
    public function cant_impersonate_self()
    {
        $this->userToImpersonate = $this->createUser($this->adminRole());

        $this->loginAs($this->userToImpersonate);

        $this->get(route('core.impersonate.start', $this->userToImpersonate->id, false))
            ->assertStatus(403);
    }

    #[Test]
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
        $this->loginAs($this->impersonator);
    }

    private function adminRole()
    {
        $role = $this->createRole();

        $role->permissions()
            ->sync(Permission::pluck('id'));

        return $role;
    }

    private function defaultAccessRole()
    {
        $role = $this->createRole();

        $role->permissions()
            ->sync(Permission::implicit()->pluck('id'));

        return $role;
    }

    private function createUser($role)
    {
        return User::factory()->create([
            'role_id'   => $role->id,
            'is_active' => true,
        ]);
    }

    private function createRole()
    {
        $menu = Menu::first(['id']);

        return Role::factory()->create([
            'menu_id' => $menu->id,
        ]);
    }

    public function loginAs(Authenticatable $user)
    {
        return $this->actingAs($user, $this->guard);
    }
}
