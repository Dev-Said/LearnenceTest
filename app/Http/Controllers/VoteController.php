<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Inertia\Inertia;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index()
    {
        $usersCount = User::all()->count();
        $polls = Poll::with('votes')->wherePublish(true)->whereClosed(false)->latest('updated_at')->get();

        return Inertia::render('Votes/Index', [
            'polls' => $polls->transform(function (Poll $poll) {

                // on pourrait faire entièrement la requête ici sans utiliser hasVoted() dans le model Poll

                $poll->voted = $poll->votes->where('user_id', auth()->user());

                // ou, si on doit garder hasVoted(), envoyer $poll en deuxième paramètres pour accéder à la relation votes. Ceci nous évitera de faire une requête à chaque itération.

                // $poll->voted = $poll->hasVoted(auth()->user(), $poll);

                return $poll;
            }),
            'status' => session('status'),
            'usersCount' => $usersCount,
            'votesCount' => session('votesCount'),
        ]);
    }



    public function show(Poll $poll)
    {
        return Inertia::render('Votes/Show', [
            'poll' => $poll,
        ]);
    }

    public function voteForPoll(Poll $poll, Request $request)
    {
        // le seeder crée des doublons donc peut être qu'il faut d'abord faire un get pour supprimer tous les votes du même utilisateur dans un même sondage

        // $userVotes = Vote::where('poll_id', $poll->id)
        //     ->where('user_id', $request->user()->id)
        //     ->get();
        // if (count($userVotes) > 0) {
        //     foreach($userVotes as $userVote) {
        //         $userVote->delete();
        //     }
        // } 

        // check si l'utilisateur a déjà voté. Si oui, on supprime son vote
        $userVote = Vote::where('poll_id', $poll->id)
            ->where('user_id', $request->user()->id)
            ->first();
        if ($userVote) $userVote->delete();

        $poll->votes()->create([
            'poll_id' => $poll->id,
            'answer_id' => $request->answer_id,
            'user_id' => $request->user()->id,
        ]);

        // servira à calculer le pourcentage d'utilisateurs ayant votés
        $votesCount = Vote::where('poll_id', $poll->id)->count();

        $params = [
            'status' => "Your vote in the \"{$poll->title}\" poll was well received!", 
            'votesCount' => $votesCount,
        ];

        return redirect()->route('votes.index')->with($params);
    }
}
