<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Book;
use App\User;

class BookController extends Controller
{
    public function StoreBook(Request $request){
        $book = new Book;
        $user = User::find(Auth::user()->id);

        try{
            $book->title = $request->title;
            // $book->file_path = $request->file_path;
            $book->author = $request->author;
            $book->description = $request->description;
            $book->user_id = Auth::user()->id;
            $cover = '';
            $file_path = '';

            // $fileName = $user->id."_".time().'.'.$request->cover->extension();  
   
            if($request->cover!=''){
                // $cover = $user->id."_".time().".".$request->cover->extension();
                $cover = $user->id."_".time().".jpg";
                file_put_contents("storage/book/cover/".$cover, base64_decode($request->cover));
                // $request->cover->move(public_path("storage/book/cover/"), base64_decode($cover));
                $book->cover = $cover;
            }

            if($request->file_path!=''){
                $file_path = $user->id."_".time().".".$request->file_path->extension();
                $request->file_path->move(public_path("storage/book/"), $file_path);
                $book->file_path = $file_path;
            }

            $book->save();

            $ch = curl_init("https://fcm.googleapis.com/fcm/send");
            $header = array("Content-Type:application/json","Authorization: key=AAAAdlYYbg4:APA91bH_KFzyMeqFZvR4TS8Pz3kG-BJc1qjqWKrpFpaCTD38mUd5xWX8QNMfq6ybyCwa3j8NRWbIwSjgIH4f9zWbt3Jikc5dUQZzz5sGA-EegGy9SsBlR0dJy2ZfG05_VE4v2N51K5nC");

            $data = json_encode(array("to" => "/topics/storing_book","data" => array("title" => "Notification", "message" => "Someone is Storing Book With Title "."'".$request->title."'")));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_exec($ch);
            
            return response()->json([
                'success' => true,
                'Book' => $book,
                'User' => $user
            ]);
            
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ]);
        }

    }

    public function EditBook(Request $request){
        $book = Book::where('id',$request->id)->first();
        $user = $book->user_id;

        if(!$book){
            return response()->json([
                'success' => false,
                'message' => 'No Book with this ID'
            ]);
        }else{
            try{
                $book->title = $request->title;
                // $book->file_path = $request->file_path;
                $book->author = $request->author;
                $book->description = $request->description;
                $cover = '';

                if($request->cover!=''){
                    // $cover = $user->id."_".time().".".$request->cover->extension();
                    $cover = $user."_".time().".jpg";
                    file_put_contents("storage/book/cover/".$cover, base64_decode($request->cover));
                    // $request->cover->move(public_path("storage/book/cover/"), base64_decode($cover));
                    $book->cover = $cover;
                }

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
        $book = Book::where('id',$request->id)->first();
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

    public function getAllBooks(){
        $book = Book::get();
        if($book){
            return response()->json([
                'success' => true,
                'book' => $book
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Failed To Get All Books"
            ]);
        }
    }

    public function getBooks(){
        $user = Auth::user();
        $book = Book::where('user_id', $user->id)->get();
        // $book = new Book;

        if($book){
            return response()->json([
                'success' => true,
                'book' => $book
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Failed to Fetch Data"
            ]);
        }
    }
}
