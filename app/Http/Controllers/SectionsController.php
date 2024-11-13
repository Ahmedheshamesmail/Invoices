<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections =section::all();
        return view('sections.sections',compact('sections'));
    }


      public function create()
    {
        //
    }


    public function store(Request $request)
    {
            // التحقق من صحة المدخلات
    $request->validate([
        'section_name' => 'required|string|max:255|Unique:sections',
        'description' => 'required|nullable|string|max:1000',
    ],[
        'section_name.required' =>'يرجي إدخال إسم القسم ',
        'section_name.unique' =>'إسم القسم مسجل مسبقا ',
        'description.required' => 'يرجي إدخال الوصــف',

    ]);

       // التحقق مما إذا كان القسم موجودًا مسبقًا
       if (section::where('section_name', $request->section_name)->exists()) {
        // تخزين رسالة في الجلسة
        session()->flash('Error', 'خطأ: القسم موجود مسبقا....');
    }
        else{
        section::create([
            'section_name' =>$request->section_name,
            'description' =>$request->description,
            'Created_by' =>(Auth::user()->name),
        ]);
        session()->flash('Add', '  تم إضافة القســم بنجــاح.....');
    }
    return redirect('/sections');
}


    public function show(section $sections)
    {

    }

    public function edit(section $sections)
    {
        //
    }

    public function update(Request $request)
    {
        $id = $request->id;

        // التحقق من صحة البيانات
        $request->validate([
            'section_name' => 'required|string|max:255|unique:sections,section_name,'.$id,
            'description' => 'required|nullable|string|max:1000'
        ], [
            'section_name.required' => 'يرجي إدخال إسم القسم ',
            'section_name.unique' => 'إسم القسم مسجل مسبقا ',
            'description.required' => 'يرجي إدخال الوصــف',
        ]);

        // العثور على القسم
        $section = section::find($id);
        if (!$section) {
            return redirect('/sections')->with('error', 'القسم غير موجود.');
        }

        // تحديث القسم
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        // رسالة نجاح
        session()->flash('update', 'تم التعديل بنجاح ');
        return redirect('/sections');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->id;

        $section = section::find($id);

        if (!$section) {
            return redirect('/sections')->with('error', 'القسم غير موجود.');
        }
            // حذف القسم
        $section->delete();
        session()->flash('update', 'تم الحذف بنجاح ');
        return redirect('/sections');
    }
}