<?php

use App\Owner;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LaravelEnso\MenuManager\app\Models\Menu;
use LaravelEnso\PermissionManager\app\Models\Permission;
use LaravelEnso\RoleManager\app\Models\Role;
use Tests\TestCase;

class ImpersonateTest extends TestCase
{
    use DatabaseMigrations;

    private $faker;

    protected function setUp()
    {
        parent::setUp();

        // $this->disableExceptionHandling();
        $this->faker = Factory::create();
    }

    /** @test */
    public function canImpersonateIfIsAllowed()
    {
        $this->createRoleWithAllPermissions();
        $role = Role::whereName('roleWithAllPermissions')->first(['id']);
        $this->createUser('impersonator', $role);
        $this->createUser('userToImpersonate', $role);
        $userToImpersonate = User::whereFirstName('userToImpersonate')->first();
        $this->actingAs(User::whereFirstName('impersonator')->first());

        $response = $this->get('/core/impersonate/'.$userToImpersonate->id);

        $response->assertStatus(302);
        $response->assertSessionHas('impersonating');
        $this->assertTrue(session('flash_notification')[0]->message ===
            'Impersonating '.$userToImpersonate->fullName);
    }

    /** @test */
    public function cantImpersonateIfIsNotAllowed()
    {
        $this->createRoleWithDefaultPermissions();
        $role = Role::whereName('roleWithDefaultPermissions')->first(['id']);
        $this->createUser('impersonator', $role);
        $this->createUser('userToImpersonate', $role);
        $userToImpersonate = User::whereFirstName('userToImpersonate')->first();
        $this->actingAs(User::whereFirstName('impersonator')->first());

        $response = $this->get('/core/impersonate/'.$userToImpersonate->id);

        $this->assertTrue(session('flash_notification')[0]->level === 'danger');
        $response->assertStatus(302);
        $response->assertSessionMissing('impersonating');
    }

    /** @test */
    public function cantImpersonateIfIsImpersonating()
    {
        $this->createRoleWithAllPermissions();
        $role = Role::whereName('roleWithAllPermissions')->first(['id']);
        $this->createUser('impersonator', $role);
        $this->createUser('userToImpersonate', $role);
        $userToImpersonate = User::whereFirstName('userToImpersonate')->first();
        $this->actingAs(User::whereFirstName('impersonator')->first());
        $response = $this->get('/core/impersonate/'.$userToImpersonate->id);

        $response = $this->get('/core/impersonate/'.$userToImpersonate->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function cantImpersonateSelf()
    {
        $this->createRoleWithAllPermissions();
        $role = Role::whereName('roleWithAllPermissions')->first(['id']);
        $this->createUser('userToImpersonate', $role);
        $userToImpersonate = User::whereFirstName('userToImpersonate')->first(['id']);
        $this->actingAs($userToImpersonate);

        $response = $this->get('/core/impersonate/'.$userToImpersonate->id);

        $response->assertStatus(302);
        $response->assertSessionMissing('impersonating');
    }

    /** @test */
    public function stopImpersonating()
    {
        $this->createRoleWithAllPermissions();
        $role = Role::whereName('roleWithAllPermissions')->first(['id']);
        $this->createUser('impersonator', $role);
        $this->createUser('userToImpersonate', $role);
        $userToImpersonate = User::whereFirstName('userToImpersonate')->first();
        $this->actingAs(User::whereFirstName('impersonator')->first());

        $response = $this->get('/core/impersonate/'.$userToImpersonate->id);
        $response->assertSessionHas('impersonating');

        $response = $this->get('/core/impersonate/stop');

        $response->assertSessionMissing('impersonating');
        $response->assertStatus(302);
        $response->assertSessionHas('flash_notification');
    }

    private function createRoleWithAllPermissions()
    {
        $role = Role::create([
            'name'                 => 'roleWithAllPermissions',
            'display_name'         => $this->faker->word,
            'description'          => $this->faker->sentence,
            'menu_id'              => Menu::first(['id'])->id,
        ]);
        $permissions = Permission::all()->pluck('id');
        $role->permissions()->attach($permissions);
    }

    private function createRoleWithDefaultPermissions()
    {
        $role = Role::create([
            'name'                 => 'roleWithDefaultPermissions',
            'display_name'         => $this->faker->word,
            'description'          => $this->faker->sentence,
            'menu_id'              => Menu::first(['id'])->id,
        ]);
        $permissions = Permission::implicit()->pluck('id');
        $role->permissions()->attach($permissions);
    }

    private function createUser($firstName, $role)
    {
        $user = new User([
            'first_name'                 => $firstName,
            'last_name'                  => $this->faker->lastName,
            'phone'                      => $this->faker->phoneNumber,
            'is_active'                  => 1,
        ]);
        $user->email = $this->faker->email;
        $user->owner_id = Owner::first(['id']);
        $user->role_id = $role->id;
        $user->save();
    }
}
