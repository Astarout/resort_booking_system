<?php

namespace App\Services;

class ProfanityFilterService
{
    # list of bad words
    protected array $badWords = [
        'shit','fuck','bitch','asshole','damn','bobo','hayop','motherfucker',
        'fucker','fucking','slut','whore', 'crap','piss','dick',
        'cock','pussy','jerk','idiot','stupid','moron', 'bastard',
        'prick','ass','bullshit','shithead','dumbass','jackass', 'tanga',
        'gago','ulol','tarantado','lintik','punyeta','bwisit','leche',
        'wtf','stfu','fk','fck','sh1t','a$$','biatch',
        'arse', 'balls', 'bastards', 'bollocks', 'bugger', 'crappy', 'cunt',
        'douche', 'douchebag', 'fag', 'faggot', 'freak', 'hell', 'ho',
        'jerkoff', 'kunt', 'loser', 'motherfucking', 'nuts', 'prickhead',
        'pissed', 'shag', 'slag', 'screw', 'twat', 'wanker', 'bitchy', 'shite',
        'shitter', 'turd', 'twit', 'dumbfuck', 'fubar', 'asshat', 'dickhead',
        'fkn', 'sh1tty', 'sux', 'loserish', 'git',
    ];

    public function contains(string $text): bool
    {
        $normalized = $this->normalize($text);

        foreach ($this->badWords as $word) {
            if (str_contains($normalized, $word)) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $text): string
    {
        // lowercase
        $text = strtolower($text);

        // remove non letters (keep a-z only)
        $text = preg_replace('/[^a-z]/', '', $text);

        // collapse repeated letters
        $text = preg_replace('/(.)\1+/', '$1', $text);

        return $text;
    }
}
