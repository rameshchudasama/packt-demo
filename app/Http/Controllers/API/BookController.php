<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookValidateRequest;
use Auth;
class BookController extends Controller
{
    public function index(Request $request)
    {
        $userinfo = auth('sanctum')->user();
        $arrBooks = [];
        $books = Book::query();
        if($request->search){
            $books->where(
                function($query) use ($request) {
                  return $query
                         ->where('title', 'LIKE','%'.$request->search.'%')
                         ->orWhere('author', 'LIKE','%'.$request->search.'%')
                         ->orWhere('genre', 'LIKE','%'.$request->search.'%')
                         ->orWhere('isbn', 'LIKE','%'.$request->search.'%');
                 });
        }
        $books = $books->orderBy('id','desc')->paginate(100);
        if($books->isNotEmpty())
        {
            $arrBooks = $books->toArray(); 
            $arrBooks["userInfo"] = $userinfo;
        }
        return array_reverse($arrBooks);
    }

    public function add(BookValidateRequest $request)
    {
        $request->validated();
        $input = $request->all();
        $imageName = NULL;
        if ($image = $request->file('file')) {
            $destinationPath = 'img/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
        }

        Book::create($input);

        return response()->json(['success'=> 'Book has been created successfully']);

    }

    public function edit($id)
    {
        $books = Book::find($id);
        return response()->json($books);
    }

    public function update($id, BookValidateRequest $request)
    {
        
        $books = Book::find($id);
        $request->validated();
        $input = $request->all();
        $imageName = NULL;
        if ($image = $request->file('file')) {
            $destinationPath = 'img/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
            if(!empty($books->image)){
                unlink('img/'.$books->image);
            }
        }
        
        $books->update($input);

        return response()->json(['success'=> 'Book has been update successfully']);
    }

    public function delete($id)
    {
        $book = Book::find($id);
        $book->delete();
        if(!empty($books->image)){
            unlink('img/'.$book->image);
        }
        return response()->json(['success'=> 'Book has been deleted successfully']);

    }
}
