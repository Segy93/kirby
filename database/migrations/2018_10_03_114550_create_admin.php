<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('Roles')
        ->insert(
            [
                'description' => 'Admin',
            ]);

        $password = str_random(10);

        if(config(php_uname('n').'.DEVPASS')) {
            $password = 'admin';
        }
        DB::table('Admins')
        ->insert(
            [
            'username'  =>  'kirby_admin',
            'email'     =>  'admin@kesezakirby.rs',
            'password'  =>  password_hash($password, PASSWORD_DEFAULT),
            'role_id'   =>  1,
        ]);

        echo 'Ovo je admin password: '.$password."\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('Admins')->where('username', 'kirby_admin')->delete();
        DB::table('Roles')->where('description', 'Admin')->delete();
    }
}
