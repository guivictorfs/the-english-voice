<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Lista cursos.
     */
    public function index()
    {
        $courses = Course::orderBy('course_name')->get();
        return view('admin.users.courses', compact('courses'));
    }

    /**
     * Armazena novo curso.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course,course_name',
        ]);
        Course::create(['course_name' => $request->name]);

        return back()->with('success', 'Curso adicionado com sucesso!');
    }

    /**
     * Remove curso.
     */
    /**
     * Atualiza nome do curso.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100|unique:course,course_name,' . $id . ',course_id',
        ]);
        $course->course_name = $request->name;
        $course->save();

        return back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return back()->with('success', 'Curso removido com sucesso!');
    }
}
