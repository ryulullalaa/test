<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = Member::where('is_admin', 1)->get();

        foreach ($members as $key => $member) {
            $user = DB::table('users')
                ->where('member_id', $member->id)
                ->first();

            if (!is_null($user)) continue;

            DB::table('users')->insert([
                'name' => $member->name,
                'email' => $member->id.'@newsong-j.com',
                'member_id' => $member->id,
                'password' => Hash::make('000000'),
            ]);
        }
    }
}
