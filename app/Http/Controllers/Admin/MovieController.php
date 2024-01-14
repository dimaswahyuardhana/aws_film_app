<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index(){
        $movies = Movie::all();
        return view('admin.movies', ['movies' => $movies]);
    }

    public function create(){
        return view('admin.movie-create');
    }

    public function edit($id){
        $movie = Movie::find($id);

        return view('admin.movie-edit', ['movie' => $movie]);
    }

    public function store(Request $request){
        $data = $request->except('_token'); //untuk mengambil semua data kecuali token

        $request->validate([
            'title' => 'required|string',
            'small_tumbnail' => 'required|image|mimes:jpeg,jpg,png',
            'large_tumbnail' => 'required|image|mimes:jpeg,jpg,png',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'

        ]);

        $smallTumbnail = $request->small_tumbnail;
        $largeTumbnail = $request->large_tumbnail;

        $originalSmallTumbnailName = Str::random(10).$smallTumbnail->getClientOriginalName();
        $originalLargeTumbnailName = Str::random(10).$largeTumbnail->getClientOriginalName();

        $smallTumbnail->storeAs('public/tumbnail', $originalSmallTumbnailName);
        $largeTumbnail->storeAs('public/tumbnail', $originalLargeTumbnailName);

        $data['small_tumbnail'] = $originalSmallTumbnailName;
        $data['large_tumbnail'] = $originalLargeTumbnailName;


        Movie::create($data);

        return redirect()->route('admin.movie')->with('success', 'Movie Created');
    }

    public function update(Request $request, $id){
        $data = $request->except('_token');

        $request->validate([
            'title' => 'required|string',
            'small_tumbnail' => 'image|mimes:jpeg,jpg,png',
            'large_tumbnail' => 'image|mimes:jpeg,jpg,png',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'

        ]);

        $movie = Movie::find($id);

        if($request->small_tumbnail){
            //save new image
            $smallTumbnail = $request->small_tumbnail;
            $originalSmallTumbnailName = Str::random(10).$smallTumbnail->getClientOriginalName();
            $smallTumbnail->storeAs('public/tumbnail', $originalSmallTumbnailName);
            $data['small_tumbnail'] = $originalSmallTumbnailName;

            // delete old image
            Storage::delete('public/tumbnail/'.$movie->small_tumbnail);

        }

        if ($request->large_tumbnail){
            //save new image
            $largeTumbnail = $request->large_tumbnail;
            $originalLargeTumbnailName = Str::random(10).$largeTumbnail->getClientOriginalName();
            $largeTumbnail->storeAs('public/tumbnail', $originalLargeTumbnailName);
            $data['large_tumbnail'] = $originalLargeTumbnailName;

            //delete old image
            Storage::delete('public/tumbnail/'.$movie->large_tumbnail);


        }

        $movie->update($data);
        return redirect()->route('admin.movie')->with('success', 'Movie updated');

    }

    public function destroy($id){
        Movie::find($id)->delete();
        return redirect()->route('admin.movie')->with('success', 'Movie delected');
    }
}
