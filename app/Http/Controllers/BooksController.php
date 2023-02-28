<?php
         
namespace App\Http\Controllers;
          
use App\Models\Book;
use App\Models\applications;
use Illuminate\Http\Request;
use DataTables;
        
class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */


public function index(Request $request)
    {
       //dd($request->ajax());


        if ($request->ajax())
{
    
            $data = Book::latest()->get();
    return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Edit</a>';
    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';
    return $btn;
    
      })
                    ->rawColumns(['action'])
                    ->make(true);
}
      
       return view('Books.books');
       // $data = Book::latest()->get();
       // return response()->view('books');
    }
     


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     
    public function store(Request $request)
    {
        $book= Book::updateOrCreate(['id' => $request->book_id], ['title' => $request->title, 'author' => $request->author]);        
        return response()->json($book);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
       // dd( response()->json($book));
        return response()->json($book);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id)->delete();
     
        return response()->json($book);
    }
}

?>