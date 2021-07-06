<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobAssign;
use App\Models\JobType;
use App\Models\Priority;
use App\Models\ProcessMethod;
use App\Models\Project;
use App\Models\Staff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        return view('jobs.index');
    }

    public function show($id)
    {
        $job = Job::with(['parent', 'type', 'project', 'priority', 'assigner'])->where('id', $id)->get()[0];
        return response($job);
    }

    public function create()
    {
        // to-do
        // get all jobs created
        // get all staff info 
        // get all parent jobs 
        // get all projects
        // get all job types 
        // get all priorities 
        // get all process methods

        $jobs = Job::all();
        $staff = Staff::all();

        $projects = Project::all();
        
        $jobTypes = JobType::all();
        $priorities = Priority::all();
        $processMethods = ProcessMethod::all();
         
        return view('jobs.create', compact('staff', 'jobs', 'projects', 'jobTypes', 'priorities', 'processMethods'));

    }



    public function action(Request $request)
    {
        dd($request->all());
        $action = $request->input('action');
        switch ($action) {
            case 'save': 
                $result = $this->save($request->all());
                if ($result['status']) {
                    return view('jobs.create');
                }
                else {
                    return view('jobs.create', $result['message']);
                }
            case 'save_copy': 
                $result = $this->save($request->all());
                if ($result['status']) {
                    $job = $result['message'];
                    return redirect()->to('jobs.create', 200)->with('job', $job);
                }
                else {
                    return redirect()->to('jobs.create', 400)->with('error', $result['message']);
                }
            case 'edit': 
                return view('jobs.create', ['edit' => true]); 
            case 'delete': 
                $id = $request->input('job_id');
                $result = $this->destroy($id);
                if ($result) {
                    return redirect()->to('jobs.create')->with('message', 'Xóa công việc thành công');
                }
            case 'search': 
                return redirect()->to('jobs.search');
        }


    }
    
    private function destroy ($id) 
    {
        $job = Job::findOrFail($id);
        return $job->delete();
        
    } 

    private function save($data) {
        $validator = Validator::make($data, [
            'code' => 'unique:jobs', 
            'assigner_id' => 'required', 
            'name' => 'required', 
            'deadline' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return array('status' => false, 'message' => $validator->errors());
        }
        $tableCols = DB::getSchemaBuilder()->getColumnListing('jobs');
        $jobData = array_filter(
            $data, 
            fn($key) => in_array($key, $tableCols),
            ARRAY_FILTER_USE_KEY
        );
        try {
            $job = Job::create($jobData);
            return array('status' => true, 'message' => $job);
        }
        catch (Exception $e) {
            return array('status' => false, 'message' => $e->getMessage());
        }
        
        //TODO: insert into job_assigns table
    }
    
    


}
