<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view_any_province', 'view_province', 'create_province', 'update_province', 'delete_province',
            'view_any_regency', 'view_regency', 'create_regency', 'update_regency', 'delete_regency',
            'view_any_district', 'view_district', 'create_district', 'update_district', 'delete_district',
            'view_any_village', 'view_village', 'create_village', 'update_village', 'delete_village',
            'view_any_position', 'view_position', 'create_position', 'update_position', 'delete_position',
            'view_any_member', 'view_member', 'create_member', 'update_member', 'delete_member',
            'view_any_committee', 'view_committee', 'create_committee', 'update_committee', 'delete_committee',
            'view_any_inventory', 'view_inventory', 'create_inventory', 'update_inventory', 'delete_inventory',
            'view_any_event', 'view_event', 'create_event', 'update_event', 'delete_event',
            'view_any_document', 'view_document', 'create_document', 'update_document', 'delete_document',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance',
            'manage_roles', 'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $adminDPP = Role::firstOrCreate(['name' => 'Admin Pusat (DPP)']);
        $adminDPP->givePermissionTo(Permission::all());

        $adminDPW = Role::firstOrCreate(['name' => 'Admin Wilayah (DPW)']);
        $adminDPW->givePermissionTo([
            'view_any_province', 'view_province',
            'view_any_regency', 'view_regency', 'create_regency', 'update_regency',
            'view_any_district', 'view_district', 'create_district', 'update_district',
            'view_any_village', 'view_village', 'create_village', 'update_village',
            'view_any_position', 'view_position',
            'view_any_member', 'view_member', 'create_member', 'update_member',
            'view_any_committee', 'view_committee', 'create_committee', 'update_committee', 'delete_committee',
            'view_any_inventory', 'view_inventory', 'create_inventory', 'update_inventory', 'delete_inventory',
            'view_any_event', 'view_event', 'create_event', 'update_event', 'delete_event',
            'view_any_document', 'view_document', 'create_document', 'update_document', 'delete_document',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance',
        ]);

        $adminDPC = Role::firstOrCreate(['name' => 'Admin Cabang (DPC)']);
        $adminDPC->givePermissionTo([
            'view_any_province', 'view_province',
            'view_any_regency', 'view_regency',
            'view_any_district', 'view_district', 'create_district', 'update_district',
            'view_any_village', 'view_village', 'create_village', 'update_village',
            'view_any_position', 'view_position',
            'view_any_member', 'view_member', 'create_member', 'update_member',
            'view_any_committee', 'view_committee', 'create_committee', 'update_committee', 'delete_committee',
            'view_any_inventory', 'view_inventory', 'create_inventory', 'update_inventory', 'delete_inventory',
            'view_any_event', 'view_event', 'create_event', 'update_event', 'delete_event',
            'view_any_document', 'view_document', 'create_document', 'update_document', 'delete_document',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance',
        ]);

        $adminPAC = Role::firstOrCreate(['name' => 'Admin Kecamatan (PAC)']);
        $adminPAC->givePermissionTo([
            'view_any_province', 'view_province',
            'view_any_regency', 'view_regency',
            'view_any_district', 'view_district',
            'view_any_village', 'view_village',
            'view_any_position', 'view_position',
            'view_any_member', 'view_member', 'create_member', 'update_member',
            'view_any_committee', 'view_committee', 'create_committee', 'update_committee',
            'view_any_inventory', 'view_inventory', 'create_inventory', 'update_inventory',
            'view_any_event', 'view_event', 'create_event', 'update_event',
            'view_any_document', 'view_document', 'create_document', 'update_document',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance',
        ]);

        // Create default Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@ppp.com'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('password'),
            ]
        );
        $user->assignRole('Super Admin');
    }
}
