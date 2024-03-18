<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\Course\Modality;
use App\Http\Requests\StoreCourse;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $courses = Course::where('name', 'ilike', "%$search%")
            ->orWhereHas('modality',function($query) use ($search) {
                $query->where('description','ilike',"$search");
            })
            ->orderBy('name')
            ->with('modality')->paginate();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Modality::count() < 1){
            return back()->with('error','Você ainda não cadastrou modalidades');
        };
        return view('admin.courses.edit', [
            'course'        => new Course(),
            'modalities'    => Modality::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourse $request)
    {        
        $course = Course::create($request->all());
        return redirect()->route('admin.courses.edit', ['course' => $course])
            ->with('success', "O curso {$course->name} foi criado com sucesso");
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {                
        return view('admin.courses.edit', [
            'course'        => $course,
            'modalities'    => Modality::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCourse $request, Course $course)
    {
        $this->authorize('updateOrDelete',$course);
        $course->update($request->all());
        return redirect()->route('admin.courses.edit', ['course' => $course])
            ->with('success', "O curso {$course->name} foi atualizado com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $this->authorize('updateOrDelete',$course);
        $slug = $course->slug;
        $course->delete();
        return redirect()->route('admin.courses.index')
            ->with('success', "O Curso $slug foi excluído com sucesso");
    }
}
