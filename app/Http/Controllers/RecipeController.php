<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Recipe;
use App\Rules\IngredientsRequireRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use function GuzzleHttp\Psr7\str;

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
        return Inertia::render('Recipes/Create/Index', ['productsList' => $productsList]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $frd = $request->all();
        $validated = Validator::make($frd, [
            'name' => ['required', Rule::unique('recipes')],
            'image' => ['required', 'mimes:jpeg,jpg,png', 'max:1024'],
            'process' => ['required'],
            'ingredients' => ['required', new IngredientsRequireRule()],
        ])->validateWithBag('storeRecipe');
        $products = $frd['ingredients'];
        $extension = $request->image->extension();
        $name = \Str::of($validated['name'])->ascii()->slug();
        $request->image->storeAs('/public/recipes/', $name . "." . $extension);
        $url = Storage::url('recipes/' . $name . "." . $extension);
        $recipe = $this->recipes->create([
            'name' => $frd['name'],
            'video_url' => $frd['video_url'],
            'image_url' => $url,
            'process' => $frd['process'],
            'rating' => $frd['rating'] ?? null,
        ]);
        foreach ($products as $product) {
            $recipe->products()->attach($product['product_id'], [
                'unit_value' => $product['unit_value']
            ]);
        }

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
        $ingredients = $recipe->products()->get(['name', 'unit', 'product_id', 'unit_value'])->toArray();
        $productsList = Product::getList();
        return Inertia::render('Recipes/Edit/Index', ['recipe' => $recipe, 'ingredients' => $ingredients, 'productsList' => $productsList]);
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
            'name' => ['required', Rule::unique('recipes')->ignore($recipe)],
            'image' => ['mimes:jpeg,jpg,png', 'max:1024'],
            'process' => ['required'],
            'ingredients' => ['required', new IngredientsRequireRule()],
        ])->validateWithBag('updateRecipe');
        if ($request->image) {
            $extension = $request->image->extension();
            $name = \Str::of($validated['name'])->ascii()->slug();
            $request->image->storeAs('/public/recipes/', $name . "." . $extension);
            $url = Storage::url('recipes/' . $name . "." . $extension);
        } else {
            $url = $recipe->getImageUrl();
        }
        $recipe->update([
            'name' => $frd['name'],
            'video_url' => $frd['video_url'],
            'image_url' => $url,
            'process' => $frd['process'],
            'rating' => $frd['rating'] ?? null,
        ]);

        $products = $frd['ingredients'];
        $recipe->products()->detach();
        foreach ($products as $product) {
            $recipe->products()->attach($product['product_id'], [
                'unit_value' => $product['unit_value']
            ]);
        }

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

    /**
     * @param Request $request
     * @return Response
     */
    public function usersRecipesIndex(Request $request)
    {
        $frd = $request->all();
        $frd['search'] = $frd['search'] ?? '';
        $user = Auth::getUser();
        $userProducts = $user->products;
        $recipes = $this->recipes->with('products')->filter($frd)->get();

        foreach ($recipes as $recipe) {
            $recipeProducts = $recipe->products;
            $recipeProductsCount = $recipeProducts->count();
            $recipeDiffScore = 0;
            foreach ($recipeProducts as $recipeProduct) {
                foreach ($userProducts as $userProduct) {
                    if ($userProduct->pivot->product_id === $recipeProduct->pivot->product_id) {
                        if ($userProduct->pivot->unit_value >= $recipeProduct->pivot->unit_value) {
                            $recipeDiffScore++;
                        }
                    }
                }
            }
            if ($recipeDiffScore === $recipeProductsCount) {
                $availableRecipes[] = $recipe;
            } else {
                $availableRecipes = [];
            }
        }
        foreach ($availableRecipes as $recipe) {
            $recipe['process'] = explode("\n", $recipe['process']);
        }

        return Inertia::render('Recipes/User/Index/Index', ['search' => $frd['search'], 'recipes' => $availableRecipes ?? null]);
    }

    /**
     * @param Recipe $recipe
     */
    public function cook(Request $request, Recipe $recipe)
    {
        $frd = $request->all();
//        dd($frd);
        if ($frd['product_waste'] ?? null) {
            $user = Auth::getUser();
            $user->recipes()->attach($recipe);

            $userProducts = $user->products;
            $recipeProducts = $recipe->products()->get();
            foreach ($recipeProducts as $recipeProduct) {
                foreach ($userProducts as $userProduct) {
                    if ($userProduct->pivot->product_id === $recipeProduct->pivot->product_id) {
                        if ($userProduct->pivot->unit_value >= $recipeProduct->pivot->unit_value) {
                            $unitValue = $userProduct->pivot->unit_value - $recipeProduct->pivot->unit_value;
                            $user->products()->detach($userProduct);
                            $userProduct->user()->attach(\Auth::id(), [
                                'unit_value' => $unitValue,
                            ]);
                        }
                    }
                }
            }
        }

        $recipe['process'] = explode("\n", $recipe['process']);
        $products = $recipe->products()->get();
        return Inertia::render('Recipes/Show/Index', ['recipe' => $recipe, 'products' => $products, 'product_waste' => $frd['product_waste'] ?? null]);
    }
}
