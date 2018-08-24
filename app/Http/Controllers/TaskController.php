<?php

namespace App\Http\Controllers;

use App\Task;
use Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $task = new Task;
        $task->schedule = $request->schedule['abbr'];
        $task->description = $request->form['description'];
        $task->user_id = Auth::id();
        $task->branch_id = Auth::user()->branch_id;
        $task->save();
        return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $task = Task::find($id);
        $task->schedule = $request->schedule['abbr'];
        $task->description = $request->form['description'];
        $task->user_id = Auth::id();
        $task->branch_id = Auth::user()->branch_id;
        $task->save();
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function getTasks()
    {
        return Task::all();
    }
}
