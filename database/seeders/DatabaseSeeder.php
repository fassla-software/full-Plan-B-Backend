<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin_role_permissions = [
            'admin-role-manage',
            'transaction-manage',
        ];

        foreach ($admin_role_permissions as $permission){
            \Spatie\Permission\Models\Permission::updateOrCreate([
                'menu_name' => 'Admin Role and Transaction',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        $blog_permissions = [
            'blog-list',
            'blog-add',
            'blog-edit',
            'blog-delete',
        ];

        foreach ($blog_permissions as $permission){
            \Spatie\Permission\Models\Permission::updateOrCreate([
                'menu_name' => 'Blog Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        $license_permissions = [
            'generate-license-key',
            'update-license',
        ];
        foreach ($license_permissions as $permission){
            \Spatie\Permission\Models\Permission::updateOrCreate([
                'menu_name' => 'License Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


    }
}
