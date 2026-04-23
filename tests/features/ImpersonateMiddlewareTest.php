<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use LaravelEnso\Impersonate\State\Impersonating;
use LaravelEnso\Menus\Models\Menu;
use LaravelEnso\Permissions\Models\Permission;
use LaravelEnso\Roles\Models\Role;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImpersonateMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private User $impersonator;
    private User $userToImpersonate;
    private string $guard = 'web';
    private string $domain = 'impersonate.test';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->withoutMiddleware(LaravelEnso\ControlPanelApi\Http\Middleware\RequestMonitor::class);

        $role = $this->adminRole();
        $this->impersonator = $this->createUser($role);
        $this->userToImpersonate = $this->createUser($role);

        Route::middleware([StartSession::class, 'auth', 'impersonate'])
            ->domain($this->domain)
            ->get('/_test/impersonate/current-user', fn () => [
                'id'    => auth()->id(),
                'state' => (new Impersonating())->state(),
            ])->name('test.impersonate.current-user');

        Route::getRoutes()->refreshNameLookups();
        Route::getRoutes()->refreshActionLookups();
    }

    #[Test]
    public function middleware_switches_the_authenticated_user_from_session(): void
    {
        parent::actingAs($this->impersonator, $this->guard);

        $this->withSession(['impersonating' => $this->userToImpersonate->id])
            ->get(route('test.impersonate.current-user'))
            ->assertStatus(200)
            ->assertJson([
                'id'    => $this->userToImpersonate->id,
                'state' => ['impersonating' => true],
            ]);
    }

    #[Test]
    public function state_provider_reports_missing_impersonation_session(): void
    {
        $this->assertSame(['impersonating' => false], (new Impersonating())->state());
    }

    private function adminRole(): Role
    {
        $role = Role::factory()->create([
            'menu_id' => Menu::first(['id'])->id,
        ]);

        $role->permissions()->sync(Permission::pluck('id'));

        return $role;
    }

    private function createUser(Role $role): User
    {
        return User::factory()->create([
            'role_id'   => $role->id,
            'is_active' => true,
        ]);
    }
}
