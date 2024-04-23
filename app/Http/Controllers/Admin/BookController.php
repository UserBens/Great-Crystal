<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Grade;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        session()->flash('page', (object) [
            'page' => 'books',
            'child' => 'database book',
        ]);

        

        try {


            $form = (object) [
                'book_name' => $request->book_name,
                'grade_id' => $request->grade_id !== 'all' ? $request->grade_id : null,
            ];

            
            
            
            if($form->book_name && $form->grade_id)
            {
                $data = Book::with(['grade' => function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                }])
                ->whereHas('grade', function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                })
                ->where('name', 'LIKE', '%'.$form->book_name.'%')
                ->paginate(15);
            } else if ($form->book_name) {
                $data = Book::with(['grade'])->where('name', 'LIKE', '%'.$form->book_name.'%')->paginate(15);
            } else if ($form->grade_id) {
                $data = Book::with(['grade' => function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                }])
                ->whereHas('grade', function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                })
                ->paginate(15);
            } else {
                
                $data = Book::with(['grade'])->orderBy('id', 'desc')->paginate(15);
            }

            $grades = Grade::orderBy('id', 'asc')->paginate(15);

            return view('components.book.data-book-grade')->with('data', $data)->with('form', $form)->with('grades', $grades);
            
        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);

            $grade = Grade::orderBy('id', 'asc')->get();


            return view('components.book.create-book')->with('grade', $grade);

        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function postCreate(Request $request)
    {
        session()->flash('preloader', true);
        
        session()->flash('page', (object) [
            'page' => 'books',
            'child' => 'database book',
        ]);

        try {
            //code...

            $rules = [
                'name' => $request->name,
                'grade_id' => (int)$request->grade_id,
                'amount' => (int)str_replace(".", "", $request->amount),
                'nisb' => $request->nisb? $request->nisb : null,
            ];
            
            $validator = Validator::make($rules, [
                'name' => 'required|string|min:3',
                'grade_id' => 'required|integer',
                'amount' => 'required|integer|min:10000',
                'nisb' => 'nullable|string|min:5|unique:books'
            ]);
            

            if($validator->fails())
            {
                return redirect('/admin/books/create')->withErrors($validator->messages())->withInput($rules);
            }


            if(Book::where('name', $rules['name'])->where('grade_id', $rules['grade_id'])->first()){

                $grade = Grade::where('id', $rules['grade_id'])->first();

                return redirect('/admin/books/create')
                    ->withErrors([
                        'name' => 'Book name with ' . $rules['name'] . ' on ' . $grade->name . ' ' . $grade->class . ', has already create !!!',
                    ])
                    ->withInput($rules);
            }

            Book::create($rules);

            session()->flash('after_create_book');

            return redirect('/admin/books');
        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function pageEdit($id)
    {
        try {
            //code...
        
            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);

            $book = Book::where('id', $id)->first();
            $grade = Grade::orderBy('id', 'asc')->get();

            return view('components.book.update-book')->with('book', $book)->with('grade', $grade);
            
        } catch (Exception $err) {
            //throw $th;
            return dd($err);
        }
    }

    public function actionUpdate(Request $request, $id) 
    {
        session()->flash('preloader', true);
        
        session()->flash('page', (object) [
            'page' => 'books',
            'child' => 'database book',
        ]);

        try {
            //code...
            $rules = [
                'name' => $request->name,
                'grade_id' => (int)$request->grade_id,
                'amount' => (int)str_replace(".", "", $request->amount),
                'nisb' => $request->nisb? $request->nisb : null,
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string|min:3',
                'grade_id' => 'required|integer',
                'amount' => 'required|integer|min:10000',
                'nisb' => 'nullable|string'
            ]);

            
            if($validator->fails())
            {
                return redirect('/admin/books/edit' .'/'. $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            if($rules['nisb'])
            {
                $nisbExist = Book::where('nisb', $rules['nisb'])->first();
                
                if($nisbExist && $id != $nisbExist->id){
                    
                    
                    return redirect('/admin/books/edit' .'/'. $id)->withErrors([
                        'nisb' => [
                            'The nisb has already been taken.',
                        ]
                    ])->withInput($rules);
                }
            }

            Book::where('id', $id)->update($rules);

            session()->flash('after_update_book');
            
            return redirect('/admin/books');

        } catch (Exception $err) {
            //throw $th;
            return dd($err);
        }
    }


    public function detail($id) 
    {
        try {
            //code...
        
            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);
            $data = Book::with('grade')->where('id', $id)->first();

            // return $data;
            return view('components.book.detail-book')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function destroy($id)
    {
        try {
            //code...
            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);

            if(!Book::where('id', $id)->first())
            {
                return abort(404);
            }

            
            Book::where('id', $id)->delete();

            return (object)[
                'status' => true,
             ];

        } catch (Exception $err) {
            //throw $th;
            return (object)[
                'status' => false,
             ];
        }
    }
}
