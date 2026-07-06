<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$p = Permission::firstOrCreate(['name' => 'manage_approval_chains', 'guard_name' => 'web']);
$r = Role::where('name', 'Super Admin')->first();
if($r) {
    $r->givePermissionTo($p);
    echo "Permission 'manage_approval_chains' added to Super Admin.\n";
} else {
    echo "Super Admin role not found.\n";
}
