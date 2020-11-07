<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RecipeController extends Controller
{

    /**
     * @var Recipe $recipes
     */
    protected $recipes;

    public function __construct(Recipe $recipes)
    {
        $this->recipes = $recipes;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $frd = $request->all();
        $frd['search'] = $frd['search'] ?? '';
        $recipes = $this->recipes->filter($frd)->orderbyDesc('id')->get()->all();
        return Inertia::render('Recipes/Index/Index', ['search' => $frd['search'], 'recipes' => $recipes]);
    }

    /**
     * @return Response
     */
    public function create()
    {
        $productsList = Product::getList();
        return Inertia::render('Recipes/Create/Index',['productsList'=>$productsList]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $frd = $request->all();
        dd($frd);
        $validated = Validator::make($frd, [
            'name' => ['required', Rule::unique('recipes')],
            'image' => ['required', 'mimes:jpeg,jpg,png', 'max:1024'],
            'process' => ['required'],
        ])->validateWithBag('storeRecipe');
        $extension = $request->image->extension();
        $name = \Str::of($validated['name'])->ascii()->slug();
        $request->image->storeAs('/public/recipes/', $name . "." . $extension);
        $url = Storage::url('recipes/' . $name . "." . $extension);
        $this->recipes->create([
            'name' => $frd['name'],
            'video_url' => $frd['video_url'],
            'image_url' => $url,
            'process' => $frd['process'],
            'rating' => $frd['rating'] ?? null,
        ]);
        return back()->with([
            'modal_opened' => false
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param Recipe $recipe
     * @return Response
     */
    public function edit(Recipe $recipe)
    {
        return Inertia::render('Recipes/Edit/Index', ['recipe' => $recipe]);
    }

    /**
     * @param Request $request
     * @param Recipe $recipe
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Recipe $recipe)
    {
        $frd = $request->all();
        $validated = Validator::make($frd, [
            'name' => ['required', Rule::unique('recipes')],
            'image' => ['required', 'mimes:jpeg,jpg,png', 'max:1024'],
            'process' => ['required'],
        ])->validateWithBag('updateRecipe');
        $extension = $request->image->extension();
        $name = \Str::of($validated['name'])->ascii()->slug();
        $request->image->storeAs('/public/recipes/', $name . "." . $extension);
        $url = Storage::url('recipes/' . $name . "." . $extension);
        $recipe->update([
            'name' => $frd['name'],
            'video_url' => $frd['video_url'],
            'image_url' => $url,
            'process' => $frd['process'],
            'rating' => $frd['rating'],
        ]);
        return back()->with([
            'modal_opened' => false
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
