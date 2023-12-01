<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookValidateRequest;
use Auth;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BookController extends Controller
{
    private $elasticsearch;

    public function __construct(Client $client) {
        $this->elasticsearch = $client;
    }

    // public function index(Request $request)
    // {
    //     $userinfo = auth('sanctum')->user();
    //     $arrBooks = [];
    //     $books = Book::query();
    //     if($request->search){
    //         $books->where(
    //             function($query) use ($request) {
    //               return $query
    //                      ->where('title', 'LIKE','%'.$request->search.'%')
    //                      ->orWhere('author', 'LIKE','%'.$request->search.'%')
    //                      ->orWhere('genre', 'LIKE','%'.$request->search.'%')
    //                      ->orWhere('isbn', 'LIKE','%'.$request->search.'%');
    //              });
    //     }
    //     $books = $books->orderBy('id','desc')->paginate(100);
    //     if($books->isNotEmpty())
    //     {
    //         $arrBooks = $books->toArray(); 
    //         $arrBooks["userInfo"] = $userinfo;
    //     }
    //     return $arrBooks;
    // }
    public function index(Request $request)
    {
        $userinfo = auth('sanctum')->user();
        $arrBooks = [];
        if($request->search != null){
            $books = $this->searchOnElasticsearch($request->search, $request->page, 100);
        }else{
            $books = Book::orderBy('id','desc')->paginate(100);
        }
        if($books->isNotEmpty()){
            $arrBooks = $books->toArray(); 
            $arrBooks["userInfo"] = $userinfo;
        }

        return $arrBooks;
    }

    private function searchOnElasticsearch(string $query, $pageNo, $perPage = 100): array
    {
        $model = new Book;
        $from = ($pageNo > 1) ? (($pageNo - 1) * $perPage) : 0;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'from' => $from,
                'size' => $perPage,
                'query' => [
                    'query_string' => [
                        'fields' => ['title', 'author', 'genre', 'isbn'],
                        'query' => '*'.$query.'*',
                    ],
                ],
            ],
        ]);

        return $items['hits'];
    }

    private function buildCollection(array $items)
    {
        $ids = Arr::pluck($items['hits'], '_id');

        return Book::findMany($ids)
            ->sortBy(function ($book) use ($ids) {
                return array_search($book->getKey(), $ids);
            });
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
