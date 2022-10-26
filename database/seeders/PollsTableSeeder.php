<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use App\Models\Answer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PollsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $admin = $users->where('name', 'John Doe')->first();

        Poll::factory()
            ->count(25)
            ->for($admin, 'owner')
            ->create()
            ->each(function(Poll $poll) use($users) {
                $poll->answers()->saveMany(
                    Answer::factory()->times(random_int(2, 4))->make()
                );

                if ( random_int(0, 1) ) {
                    $poll->votes()->saveMany(
                        Vote::factory()->for($poll)->times(random_int(0, 10))->make([
                            'answer_id' => $poll->answers->shuffle()->first()->id,
                            'user_id' => $users->shuffle()->first()->id,
                        ])
                    );
                }
            })
        ;
    }
}
