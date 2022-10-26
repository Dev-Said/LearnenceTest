<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'publish',
        'imagePath',
        'closed',
    ];

    protected $casts = [
        'publish' => 'bool',
        'closed' => 'bool',
    ];

    protected $with = [
        'answers',
        'votes',
    ];

    protected $withCount = [
        'answers',
        'votes',
    ];

    public static $rules = [
        'title' => 'string|max:255',
        'publish' => 'boolean',
        'closed' => 'boolean',
        'answers' =>'array|min:2',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Ajout du paramètre $poll pour accéder à la relation votes
    public function hasVoted(User $user, $poll)
    {
        $has_voted = $poll->votes->where('user_id', $user->id)->first();

        if ($has_voted) {
            return $poll->votes->where('user_id', $user->id);
        }
    }
}
