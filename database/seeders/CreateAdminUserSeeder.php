<?php



namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Models\User;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;



class CreateAdminUserSeeder extends Seeder

{

    /**

     * Run the database seeds.

     */

    public function run(): void

    {

        $user = User::create([

            'name' => 'mostafa',
            'email' => 'teefa993813@gmail.com',
            'status'=>'مفعل',
            'password' => bcrypt('12345678'),

        ]);

        $role = Role::create(['name' => 'admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

    }

}
