<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        App::setLocale(LaravelLocalization::getCurrentLocale());
        $categories = Category::translatedIn(LaravelLocalization::getCurrentLocale())->get();

        return response()->json([
            'stauts'=>true,
            'data'=>$categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        // Dynamic validation rules for each locale
        $rules = [
            'photo' => 'required|image',
        ];

        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules["$locale.title"] = 'nullable|string';
            $rules["$locale.description"] = 'required_with:' . $locale . '.title|string';
        }

        $request->validate($rules);

        // Create a new category with translations
        $category = new Category();
        $category->photo = $request->photo->store('image/category','public');

        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            if(isset($request[$locale]['title']) && isset($request[$locale]['description'])){
                $category->translateOrNew($locale)->title = $request[$locale]['title'];
                $category->translateOrNew($locale)->description = $request[$locale]['description'];
            }
        }

        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully!',
        ], 201);
    }

}
