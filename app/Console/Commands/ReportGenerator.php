<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Affiliation;
use App\Models\Report;
use App\Models\Member;
use App\Models\Attendance;
use DB;

class ReportGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a report by group every week';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::transaction(function () {
            // Report 생성
            $affiliations = Affiliation::all();

            foreach ($affiliations as $key => $affiliation) {
                $report = new Report;
                $report->affiliation_id = $affiliation['id'];
                $report->save();
            }

            // 생성된 Report에 Member의 Attendance 생성
            $data = DB::table('members')
                ->leftJoin('reports', 'members.affiliation_id', '=', 'reports.affiliation_id')
                ->selectRaw('members.id as member_id, MAX(reports.id) as report_id')
                ->where('grade_id', '!=', 1)
                ->groupBy('member_id')
                ->get()
                ->map(function($item) {
                    return get_object_vars($item);
                });

            foreach ($data as $value) {
                $attendance = new Attendance;
                $attendance->report_id = $value['report_id'];
                $attendance->member_id = $value['member_id'];
                $attendance->save();
            }
        });
    }
}
