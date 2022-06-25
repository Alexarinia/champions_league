<?php

namespace App\Managers;

use App\Exceptions\LeagueException;
use App\Models\GameMatch;
use App\Models\GameWeek;
use App\Models\Team;
use App\Pivot\GameMatchTeam;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FixtureGenerateManager
{
    /**
     * Generates fixtures and returns count of generated matches
     * 
     * @return int
     */
    public static function generateFixtures(): int
    {
        $teams = Team::all();

        if(! $teams || $teams->count() < 2) {
            throw new LeagueException('Teams quantity is very small to create any fixtures');
        }

        // Make teams count even
        if($teams->count() %2 !== 0) {
            $teams->pop();
        }

        $weeks_count = self::countWeeks($teams);
        $weeks = self::generateWeeks($weeks_count, $teams);
        $matches_count = self::saveGeneratedWeeks($weeks, $teams);

        return $matches_count;
    }

    /**
     * Counts weeks necessary to play all teams with each other
     * 
     * @param Collection $teams
     * 
     * @return int
     */
    private static function countWeeks(Collection $teams): int
    {
        return $teams->count() - 1;
    }

    /**
     * Generates weeks with unique team pairs
     * 
     * @param int $weeks_count
     * @param Collection $teams
     * 
     * @return array
     */
    private static function generateWeeks(int $weeks_count, Collection $teams): array
    {
        $weeks_range = range(1, $weeks_count);
        $weeks = [];
        $teams_pool = [];

        foreach($teams as $key => $team1) {
            $teams_pool[$key]['id'] = $team1->id;
            foreach($teams as $team2) {
                if($team1->id != $team2->id) {
                    $teams_pool[$key]['guests'][] = $team2->id;
                }
            }
        }

        foreach($weeks_range as $key => $week_number) {
            $weeks[$key]['order'] = $week_number;
            $weeks[$key]['matches'] = self::generateWeekPairs($teams_pool);
        }

        return $weeks;
    }

    /**
     * Get unique team pairs for one week
     * ans shuffles team list
     * 
     * @param array $teams
     * 
     * @return array
     */
    private static function generateWeekPairs(array &$teams): array
    {
        list($pairs, $teams) = self::createUniquePairs($teams);
        
        // For switching hosts, guests and their order
        shuffle($teams);

        return $pairs;
    }

    /**
     * Generates unique team pairs for one week 
     * and regenerate pairs if draw came to dead end
     * 
     * @param array $teams
     * 
     * @return array
     */
    private static function createUniquePairs(array $teams): array
    {
        $week_reserved_teams = [];
        $initial_teams = $teams;
        $pairs = [];
        $regenerate_flag = false;
        foreach($teams as $h_key => $host) {
            if(in_array($host['id'], $week_reserved_teams) || count($host['guests']) === 0) {
                continue;
            }

            $rand_guest_key = self::pickRandomGuest($host['guests'], $week_reserved_teams);
            if(is_null($rand_guest_key)) {
                $regenerate_flag = true;
                break;
            }
            $guest_id = $host['guests'][$rand_guest_key];

            $pairs[] = [$host['id'], $guest_id];

            foreach($teams as $g_key => $possible_guest) {
                if($possible_guest['id'] == $guest_id) {
                    $guest = $possible_guest;
                    $guest_key = $g_key;
                }
            }

            $host_in_guest_key = array_search($host['id'], $guest['guests']);
            unset($teams[$guest_key]['guests'][$host_in_guest_key]);
            unset($teams[$h_key]['guests'][$rand_guest_key]);

            $week_reserved_teams[] = $host['id'];
            $week_reserved_teams[] = $guest_id;
        }

        if($regenerate_flag) {
            // regenerate pairs if draw came to dead end
            list($pairs, $teams) = self::createUniquePairs($initial_teams);
        }

        return [$pairs, $teams];
    }

    /**
     * Creates match and week models,
     * creates relations in DB
     * and returns count of saved matches
     * 
     * @param array $weeks
     * @param Collection $teams
     * 
     * @return int
     */
    private static function saveGeneratedWeeks(array $weeks, Collection $teams): int
    {
        $matches_count = 0;

        $pivots = [];
        
        foreach($weeks as $week) {
            $new_week = GameWeek::create([
                'name' => "Week {$week['order']}",
                'week_order' => $week['order'],
            ]);

            foreach($week['matches'] as $matches) {
                $new_match = new GameMatch;
                $new_week->matches()->save($new_match);
                $matches_count++;

                $host = $teams->where('id', $matches[0])->first();
                $guest = $teams->where('id', $matches[1])->first();

                $pivots[] = [
                    'game_match_id' => $new_match->id,
                    'team_id' => $host->id,
                    'host' => 1,
                    'goals' => null,
                ];

                $pivots[] = [
                    'game_match_id' => $new_match->id,
                    'team_id' => $guest->id,
                    'host' => 0,
                    'goals' => null,
                ];
            }
        }

        if(count($pivots)) {
            DB::table(GameMatchTeam::getTableName())->insert($pivots);
        }

        return $matches_count;
    }

    /**
     * Picks random guest for creating unique pair
     * 
     * @param mixed $guests
     * @param mixed $week_reserved_teams
     * @param array $excluded_keys
     * 
     * @return [type]
     */
    private static function pickRandomGuest($guests, $week_reserved_teams, $excluded_keys = [])
    {
        $excluded_keys = array_unique($excluded_keys);
        if(count($excluded_keys) === count($guests)) {
            return null;
        }

        // There I would exlude keys from $guests to save memory and reduce this function calls
        
        $rand_guest_key = array_rand($guests, 1);
        $guest_id = $guests[$rand_guest_key];
        if(in_array($guest_id, $week_reserved_teams)) {
            $excluded_keys[] = $guest_id;
            $rand_guest_key = self::pickRandomGuest($guests, $week_reserved_teams, $excluded_keys);
        }

        return $rand_guest_key;
    }
}
