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

    public function list(){
        $staffs = Staff::orderBy('name', 'ASC')->get();
        $jobs = Job::with('assigner.department')->get();
        $skills = Skill::orderBy('name', 'ASC')->paginate(3);
        foreach ($jobs as $job){
            $job->object = $job->assigner->name;
            $job->department = $job->assigner->department->name;
            $job->manday_lsx = ($job->lsx_amount) - ($job->assign_amount);
            $job->total_hour = ($job->manday_lsx)*HOUR;
        }
        return view('site.free-time.free-time', compact('staffs', 'jobs', 'skills'));
    }

    public function search(Request $request){
        // Validate ngày tháng
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if($fromDate > $toDate){
            return redirect()->route('free-time.list')->with('success', 'Ngày bắt đầu không được lớn hơn ngày kết thúc');
        }
        if($fromDate == NULL ||  $toDate == NULL){
            return redirect()->route('free-time.list')->with('success', 'Ngày bắt đầu hoặc ngày kết thúc không được rỗng');
        }

        $totalFreeTime = $request->free_time;

        // Species
        $species = $request->species;
        if ($species == 0){
            $jobs = Job::with(['assigner.department', 'jobAssigns.workPlans'])
                ->where('id', 2)
                ->whereHas('assigner', function ($q) use($request) {
                    $q->where('id', $request->staff);
                })
                ->get();

            dd($jobs[0]->jobAssigns[0]->workPlans);

            foreach ($jobs as $job){
                $job->object = $job->assigner->name;
                $job->department = $job->assigner->department->name;
                $job->manday_lsx = ($job->lsx_amount) - ($job->assign_amount);
                $job->total_hour = ($job->manday_lsx)*HOUR;
            }

            return view('site.free-time.free-time-search', compact('staffs', 'jobs'));
        }


        $staffs = Staff::orderBy('name', 'ASC')->get();
        $jobs = Job::with('assigner.department')
            ->whereHas('assigner', function ($q) use($request) {
                $q->where('id', $request->staff);
            })
            ->get();

        foreach ($jobs as $job){
            $job->object = $job->assigner->name;
            $job->department = $job->assigner->department->name;
            $job->manday_lsx = ($job->lsx_amount) - ($job->assign_amount);
            $job->total_hour = ($job->manday_lsx)*HOUR;
        }

        return view('site.free-time.free-time-search', compact('staffs', 'jobs'));
    }
}
