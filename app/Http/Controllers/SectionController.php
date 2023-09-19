<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{

    public function index()
    {
        //
      $sections=  Section::all();
        return view('sections.show_sections',compact('sections'));
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_name'=>['required','unique:sections,section_name'],
          ],[
            'section_name.required'=>'هذا الحقل مطلوب',
            'section_name.unique'=>'هذاالقسم موجود مسبقا',
        ]);
        Section::create([
            'section_name'=>$request->section_name,
            'description'=>$request->description,
            'created_by'=>Auth::user()->name,
        ]);
        return redirect()->back()->with('success-add-section','تم اضافه القسم بنجاح');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function show(Section $section)
    {

    }

    public function edit(Section $section)
    {
        //
    }
    public function update(Request $request)
    {
        $id=$request->id;
        $request->validate([
            'section_name'=>['required','max:255','unique:sections,section_name,'.$id],

        ],[
            'section_name.required'=>'هذا الحقل مطلوب',
            'section_name.unique'=>'هذاالقسم موجود مسبقا',

        ]);
      $section=Section::findOrFail($id);
        $section->update([
            'section_name'=>$request->section_name,
            'description'=>$request->description,
        ]);
        return redirect()->back()->with('success-update-section','تم تعديل القسم بنجاح');
    }


    public function destroy(Request $request)
    {
        $id=$request->id;
         Section::findOrFail($id)->delete();
         return redirect()->back()->with('success-delete-section','تم حذف القسم بنجاح');
    }
}
