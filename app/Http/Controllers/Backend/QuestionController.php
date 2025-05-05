<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function UserQuestion(Request $request)
    {

        $validatedData = $request->validate([
            'course_id'     => 'required|integer|exists:courses,id',
            'instructor_id' => 'required|integer|exists:users,id',
            'subject'       => 'required|string|min:3|max:255',
            'question'      => 'required|string|min:3',
        ], [
            'subject.required' => 'Subject required',
            'subject.min' => 'Subject no less than 3 character',
            'question.required' => 'Question required',
            'question.min' => 'Question min 3 character',
        ]);

        Question::create([
            'course_id' => $validatedData['course_id'],
            'user_id' => Auth::id(),
            'instructor_id' => $validatedData['instructor_id'],
            'subject' => $validatedData['subject'],
            'question' => $validatedData['question'],
        ]);

        return response()->json([
            'message' => 'Question Added Successfully'
        ]);
    }

    function InstructorAllQuestion()
    {
        $id = Auth::id();
        $questions = Question::where('instructor_id', $id)->where('parent_id', null)->orderBy('id', 'desc')->get();

        return view('instructor.question.all_question', compact('questions'));
    }

    public function QuestionDetails($id)
    {


        $question = Question::with('user')->where('id', $id)->firstOrFail();
        $replies = Question::with('user')->where('parent_id', $id)->orderBy('id', 'asc')->get();

        return view('instructor.question.question_details', compact('question', 'replies'));
    }
    public function InstructorDetail(Request $request)
    {

        $validatedData = $request->validate([
            'question_id' => 'required|integer|exists:questions,id',
            'course_id' => 'required|integer|exists:courses,id',
            'user_id' => 'required|integer|exists:users,id',
            'instructor_id' => 'required|integer|exists:users,id',
            'question' => 'required|string|min:3',
        ], [
            'replay.required' => 'Replay required',
            'replay.min' => 'Replay min 3 character',
        ]);

        Question::create([
            'user_id' => $validatedData['user_id'],
            'course_id' => $validatedData['course_id'],
            'parent_id' => $validatedData['question_id'],
            'instructor_id' => $validatedData['instructor_id'],
            'question' => $validatedData['question'],
        ]);

        $notification = array(
            'message' => 'Replay Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('instructor.all.question')->with($notification);
    }
}
