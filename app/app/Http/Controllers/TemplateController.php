<?php

namespace App\Http\Controllers;

use App\Models\template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\document_type;
use App\Models\templates;


class TemplateController extends Controller
{
    // upload new document form
    public function create()
    {

        $breadcrumb_title='Upload Templates';
        $document_types=document_type::all();
        return view('templates.create',['breadcrumb_title'=>$breadcrumb_title,'document_types'=>$document_types]);

    }


    // save file details
    public function upload(Request $request)
    {

        if ($request->file('input_file')) {

            $file = $request->file('input_file');
            //$the_file=$request->validate([
            //         'title' => 'required',
            //         'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            //         'description' => 'required',
            //     ]);

            //$original_filename = $request->file('input_file')->getClientOriginalName();
            //$extension = $request->file('input_file')->getClientOriginalExtension();


            $filename = time() . '_' . $file->getClientOriginalName();
            $dir = public_path('documents');
            $path = $dir . '/' . $filename;

            $uploaded_template = new template;
            $uploaded_template->ref_num = $request->ref_num;
            $uploaded_template->name = $request->title;
            $uploaded_template->path = $path;
            $uploaded_template->template_type = $request->document_type;
            $uploaded_template->description = $request->description;
            // insert records
            $uploaded_template->save();

            // Upload file (copies file to destination)
            $path = $file->move($dir, $filename);

        } else {

            dd('can not upload');
        }

        return Redirect()->back()->with('success', 'Record Inserted Successfully.' );
    }

    // list all template documents
    public function index()
    {



        $documents = template::join('document_types','document_types.id','templates.template_type')
        ->select('templates.*','document_types.document_type')
        ->get();
        $breadcrumb_title = 'All Template Documents';
        return view('templates.index', ['documents' => $documents, 'breadcrumb_title' => $breadcrumb_title]);

    }

    public function delete($id)
    {

        //TODO: transactions
        //delete (using unlink) the physical file from filesystem
        $document = template::find($id);
        unlink($document->path);
        //delete record of file from db
        $isDeleted = template::find($id)->delete();
        if ($isDeleted) {
            return Redirect()->back()->with('success', 'Record Deleted Successfully.');
        } else {
            return Redirect()->back()->with('warning', 'Record was NOT Deleted. Please Try Again');
        }
    }

    public function edit($id)
    {

        $document = template::find($id);
        $breadcrumb_title = 'Edit Document Details';
        return view('templates.edit', ['document' => $document, 'breadcrumb_title' => $breadcrumb_title]);

    }

    public function update(Request $request, $id){



        if ($request->file('input_file')) {

            $file = $request->file('input_file');
            //$the_file=$request->validate([
            //         'title' => 'required',
            //         'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            //         'description' => 'required',
            //     ]);

            //$original_filename = $request->file('input_file')->getClientOriginalName();
            //$extension = $request->file('input_file')->getClientOriginalExtension();


            $filename = time() . '_' . $file->getClientOriginalName();
            $dir = public_path('documents');
            $path = $dir . '/' . $filename;

            //remove the old file
            $document = template::find($id);
            unlink($document->path);

            //update template upload
            template::where('id',$id)->update([
                'ref_num' => $request->ref_num,
                'name' => $request->title,
                'path' => $path,
                'document_type' => $request->document_type,
                'description' => $request->description
            ]);

            // Upload file (copies file to destination)
            $path = $file->move($dir, $filename);

        }
        else {
            dd('can not update');
        }



        return Redirect()->back()->with('success', 'Record Updated Successfully.');

    }
    public function download($id)
    {

        $document = template::find($id);
        $breadcrumb_title = 'Edit Document Details';
        return view('templates.edit', ['document' => $document, 'breadcrumb_title' => $breadcrumb_title]);

    }




    //     return redirect()->route('posts.index')
    //                     ->with('success','Post has been created successfully.');
    // }
}
//TODO
// validation, how to restrict duplicate document upload (which to show during listing of documents)
