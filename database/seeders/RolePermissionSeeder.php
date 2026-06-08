<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Old meeting (backward compat)
            'access_user_meeting',
            'create_user_meeting',

            // Granular meeting permissions
            'join_meeting',
            'view_meeting_room',
            'manage_meeting_recording',
            'use_meeting_broadcast',
            'create_meeting',
            'update_meeting',

            // Other user-facing features
            'access_user_dashboard',
            'access_user_agenda',
            'access_user_notulensi',
            'download_user_notulensi',
            'use_live_transcription',
            'access_user_audio',
            'create_user_audio',
            'edit_user_audio',
            'delete_user_audio',
            'edit_notulensi',
            'access_user_video',

            // Admin section permissions
            'admin_access_dashboard',
            'admin_access_users',
            'admin_access_roles',
            'admin_access_meetings',
            'admin_access_agendas',
            'admin_access_arsips',
            'admin_access_rekaman_audio',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // super_admin — gets everything
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // admin — gets all admin sections
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'admin_access_dashboard',
            'admin_access_users',
            'admin_access_roles',
            'admin_access_meetings',
            'admin_access_agendas',
            'admin_access_arsips',
            'admin_access_rekaman_audio',
        ]);

        // user — standard user permissions
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'join_meeting',
            'view_meeting_room',
            'manage_meeting_recording',
            'use_meeting_broadcast',
            'create_meeting',
            'update_meeting',
            'access_user_dashboard',
            'access_user_agenda',
            'access_user_notulensi',
            'download_user_notulensi',
            'use_live_transcription',
            'access_user_audio',
            'create_user_audio',
            'edit_user_audio',
            'delete_user_audio',
            'edit_notulensi',
            'access_user_video',
        ]);
    }
}
