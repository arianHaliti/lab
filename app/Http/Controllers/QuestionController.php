<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except'=> ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$q = Question::all();
        //$question = Question::orderBy('created_at', 'desc')->paginate(10);
        //$q = Question::orderBy('created_at','desc')->get();
        
        return view('pages.index');

        //PRINTS SAME AS PAGES.INDEX !
        //NDRRO CODIN NALT PER ME NDRYSHU /questions !
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' =>'required',
            'body' => 'required'
        ]);

        //KRIJIMI I PYTJES
        $quest = new Question;

        $quest->question_title = $request->input('title');
        $quest->question_desc = $request->input('body');
        $quest->question_views = 0;
        $quest->question_active = 0;
        $quest->user_id = auth()->user()->id;
        $quest->save();

        //MERR ID E PYTJES TE KRIJUAR
        $last_id = $quest->question_id;
        
        return redirect('/questions/'.$last_id)->with('success','Pytja u krijua');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $question =  Question::find($id);
        return view('questions.show')->with('question',$question);
      //  return view('questions.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        if(auth()->user()->id !== $question->user_id){
            return redirect('/questions');
        }

        return view('questions.edit')->with('question',$question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' =>'required',
            'body' => 'required'
        ]);

        //KRIJIMI I PYTJES
        $quest = Question::find($id);

        $quest->question_title = $request->input('title');
        $quest->question_desc = $request->input('body');
        $quest->save();

        //MERR ID E PYTJES TE KRIJUAR
        $last_id = $quest->question_id;
        
        return redirect('/questions/'.$last_id)->with('success','Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quest = Question::find($id);
        if(auth()->user()->id !== $quest->user_id){
            return redirect('/questions');
        }
        $quest->question_active =1;

        $quest->save();

        return redirect('/questions')->with('success','Post Deleted');
    }
}
