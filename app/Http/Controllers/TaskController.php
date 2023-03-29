<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /* get all the data from the table related to logged in user id */
    public function index(){
        return Task::where('user_id',Auth::user()->id)->latest()->get();
    }
    public function show($id){
        $taskId=Task::findOrFail($id);
        return Task::where('id',$id)->get();
    }
    /* Store new task in db */
    public function store(Request $request){
        $request->validate([
            'title'=>['required','unique:tasks,title'],
            'date'=>['required'],
            'description'=>['required']
        ]);

        Task::create([
            'title'=>$request->input('title'),
            'date'=>$request->input('date'),
            'description'=>$request->input('description'),
            'user_id'=>Auth::user()->id
        ]);
        return response()->json(['status'=>'success']);
    }
        /*Update existing task   */
    public function update(Request $request,Task $task){
        $request->validate([
            'title'=>['required'],
            'date'=>['required'],
            'description'=>['required']
        ]);

        $task->update($request->all());
        return response()->json(['status'=>'success']);
    }

    /* Delete selected data */
    public function destroy(Task $task){
        $task->delete();
        return response()->json(['status'=>'success']);

    }
    /* mark incompleted task as completed task */
    public function markcompleted($id){
        $taskId=Task::findOrFail($id);
        Task::where('id',$id)->update([
            'status'=>1
        ]);
        return response()->json(['status'=>'success']); 
    }
  /* mark completed task as incompleted task */
    public function markincompleted($id){
        $taskId=Task::findOrFail($id);
        Task::where('id',$id)->update([
            'status'=>0
        ]);
        return response()->json(['status'=>'success']); 
    }
}
