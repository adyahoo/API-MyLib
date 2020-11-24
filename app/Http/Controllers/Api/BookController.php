<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Book;

class BookController extends Controller
{
    public function StoreBook(Request $request){
        $book = new Book;

        try{
            $book->title = $request->title;
            $book->file_path = $request->file_path;
            $book->author = $request->author;
            $book->description = $request->description;
            $book->user_id = Auth::user()->id;
            $book->save();
            return response()->json([
                'success' => true,
                'Book' => $book
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ]);
        }

    }

    public function EditBook(Request $request){
        $book = Book::where('title',$request->title)->first();

        if(!$book){
            return response()->json([
                'success' => false,
                'message' => 'No Book with this Title'
            ]);
        }else{
            try{
                $book->title = $request->title;
                $book->file_path = $request->file_path;
                $book->author = $request->author;
                $book->description = $request->description;
                $book->update();
                return response()->json([
                    'success' => true,
                    'Book' => $book
                ]);
            }catch(Exception $e){
                return response()->json([
                    'success' => false,
                    'message' => ''.$e
                ]);
            }
        }     
    }

    public function SearchBook(Request $request){
        $book = Book::where('title',$request->title)->first();

        if($book){
            return response()->json([
                'success' => true,
                'Book' => $book
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No Book with this Title'
            ]);
        }
    }

    public function DestroyBook(Request $request){
        $book = Book::where('title',$request->title)->first();
        $user = Auth::user()->id;

        if($book->user_id == $user){
            Book::destroy($book->id);
            return response()->json([
                'success' => true,
                'message' => 'Delete Success',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User have no permission to delete this book'
            ]);
        }        
    }
}
