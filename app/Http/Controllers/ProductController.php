<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Storage;

class ProductController extends Controller
{

    /**
     * @var Product $products
     */
    protected $products;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $frd = $request->all();
        $type = $frd['type'] ?? null;
        $frd['search'] = $frd['search'] ?? '';
        $products = $this->products->filter($frd)->orderbyDesc('id')->get()->all();
        return Inertia::render('Products/Index/Index', ['search' => $frd['search'], 'products' => $products, 'type' => $type]);
    }

    /**
     * @return Response
     */
    public function create()
    {
        $typesList = ProductType::getList();
        return Inertia::render('Products/Create/Index', ['typesList' => $typesList]);
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
            'name' => ['required', Rule::unique('products')],
            'unit' => ['required'],
            'image' => ['required', 'mimes:jpeg,jpg,png', 'max:1024'],
            'product_type_id' => ['required'],
        ])->validateWithBag('storeProduct');
        $extension = $request->image->extension();
        $name = \Str::of($validated['name'])->ascii()->slug();
        $request->image->storeAs('/public/products/', $name . "." . $extension);
        $url = Storage::url('products/' . $name . "." . $extension);
        $this->products->create([
            'name' => $frd['name'],
            'unit' => $frd['unit'],
            'image_url' => $url,
            'product_type_id' => $frd['product_type_id'],
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
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        $typesList = ProductType::getList();
        return Inertia::render('Products/Edit/Index', ['product' => $product, 'typesList' => $typesList, 'modal_opened' => true]);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @throws ValidationException
     */
    public function update(Request $request, Product $product)
    {
        $frd = $request->all();
        $validated = Validator::make($frd, [
            'name' => ['required', Rule::unique('products')->ignore($product)],
            'unit' => ['required'],
            'image' => ['mimes:jpeg,jpg,png', 'max:1024'],
            'product_type_id' => ['required'],
        ])->validateWithBag('updateProduct');

        if ($request->image) {
            $extension = $request->image->extension();
            $name = \Str::of($validated['name'])->ascii()->slug();
            $request->image->storeAs('/public/products/', $name . "." . $extension);
            $url = Storage::url('products/' . $name . "." . $extension);
        } else {
            $url = $product->getImageUrl();
        }
        $product->update([
            'name' => $frd['name'],
            'unit' => $frd['unit'],
            'image_url' => $url,
            'product_type_id' => $frd['product_type_id'],
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

    /**
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function add(Request $request, Product $product)
    {
        $frd = $request->all();
        $validated = Validator::make($frd, [
            'unit_value' => ['required'],
        ])->validateWithBag('addProduct');
        $user = \Auth::getUser();
        $user->products()->detach($product->getKey());
        $product->user()->attach(\Auth::id(), [
            'unit_value' => $frd['unit_value'],
        ]);
        return back()->with([
            'modal_opened' => false
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function usersProductsIndex(Request $request)
    {
        $frd = $request->all();
        $frd['search'] = $frd['search'] ?? '';
        $user = Auth::getUser();
        $products = $user->products()->filter($frd)->get()->toArray();

        return Inertia::render('Products/User/Index/Index', ['search' => $frd['search'], 'products' => $products]);
    }

    /**
     * @param Product $product
     */
    public function usersProductsDestroy(Product $product)
    {
        $user = \Auth::getUser();
        $user->products()->detach($product->getKey());
        return back()->with([
            'modal_opened' => false
        ]);
    }

    public function usersProductsUpdate(Request $request, Product $product)
    {
        $frd = $request->all();
        $validated = Validator::make($frd, [
            'unit_value' => ['required'],
        ])->validateWithBag('updateProduct');
        $user = \Auth::getUser();
        $user->products()->detach($product->getKey());
        $product->user()->attach(\Auth::id(), [
            'unit_value' => $frd['unit_value'],
        ]);
        return back()->with([
            'modal_opened' => false
        ]);
    }
}
