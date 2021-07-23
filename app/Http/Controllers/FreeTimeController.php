<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use App\Models\Skill;
use App\Models\Staff;
use App\Models\WorkPlan;
use Illuminate\Http\Request;

const HOUR = 8;

class FreeTimeController extends Controller
{
    public const DEFAULT_PAGINATE = 4;
    public function formatFeild($jobs){
        foreach ($jobs as $job){
            $job->object = $job->assigner->name;
            $job->department = $job->assigner->department->name;
            $job->manday_lsx = ($job->lsx_amount) - ($job->assign_amount);
            $job->total_hour = ($job->manday_lsx) * HOUR;
        }
    }
    public function list(){
        $staffs = Staff::orderBy('name', 'ASC')->get();
        $jobs = Job::with('assigner.department')->get();
        $skills = Skill::orderBy('name', 'ASC')->paginate(self::DEFAULT_PAGINATE);
        $this->formatFeild($jobs);
        return view('site.free-time.free-time', compact('staffs', 'jobs', 'skills'));
    }
    public function search(Request $request){
        $totalFreeTime = $request->free_time;
        $species = $request->species;
        $skills = Skill::orderBy('name', 'ASC')->paginate(self::DEFAULT_PAGINATE);
        $staffs = Staff::orderBy('name', 'ASC')->get();
        if ($species == 0){
            $jobs = Job::with(['assigner.department', 'jobAssigns.workPlans'])
                ->whereHas('assigner', function ($q) use($request) {
                    $q->where('id', $request->staff);
                })
                ->get();
            return view('site.free-time.free-time-search', compact('staffs', 'jobs', 'skills'));
        }
        $jobs = Job::with('assigner.department')
            ->whereHas('assigner', function ($q) use($request) {
                $q->where('id', $request->staff);
            })
            ->get();
        $this->formatFeild($jobs);
        return view('site.free-time.free-time-search', compact('staffs', 'jobs', 'skills'));
    }
}
