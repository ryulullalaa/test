<?php

namespace App\Services;

use App\Models\Affiliation;
use App\Models\Member;
use Auth;
use DB;

class KpiService
{
    private $groupByRange = 'affiliations.parish';

    public function getGrade()
    {
        return Member::find(Auth::user()->member_id)->grade_id;
    }

    public function getAffiliation()
    {
        return Affiliation::find(Member::find(Auth::user()->member_id)->affiliation_id);
    }

    public function getAffiliations($request)
    {
        return Affiliation::select('id')
                ->where(function ($query) use ($request) {
                    if ($request->parish != 0) {
                        if ($request->team != 0) {
                            if ($request->group != -1) {
                                $query->where([
                                    ['parish', $request->parish],
                                    ['team', $request->team],
                                    ['group', $request->group],
                                ]);
                            } else {
                                $query->where([
                                    ['parish', $request->parish],
                                    ['team', $request->team],
                                ]);
                            }
                        } else {
                            $query->where([
                                ['parish', $request->parish],
                            ]);
                        }
                    } else if ($request->parish == NULL) {
                        $query->where('id', NULL);
                    }
                })
                ->pluck('id');
    }

    public function groupByRange($request)
    {
        if ($request->parish == 0) {
            $groupByRange = 'affiliations.parish';
        } else if ($request->team == 0) {
            $groupByRange = 'affiliations.team';
        } else if ($request->group == -1) {
            $groupByRange = 'affiliations.group';
        } else {
            $groupByRange = 'affiliations.group';
        }

        return $groupByRange;
    }

    public function getSearchLength()
    {
        $search_length = DB::table('affiliations as a')
            ->selectRaw('
                MIN(a.parish) as min_parish, MAX(a.parish) as max_parish,
                MIN(a.team) as min_team, MAX(a.team) as max_team,
                MIN(a.group) as min_group, MAX(a.group) as max_group
            ')
            ->where([
                ['a.id', '>', 5],
                ['a.parish', '!=', 99],
            ])
            ->get();

        return $search_length[0];
    }
}