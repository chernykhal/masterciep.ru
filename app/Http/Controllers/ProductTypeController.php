<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ProductTypeController extends Controller
{

    /**
     * @var ProductType $productsTypes
     */
    protected $types;

    public function __construct(ProductType $types)
    {
        $this->types = $types;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $frd = $request->all();
        $frd['search'] = $frd['search'] ?? '';
        $types = $this->types->filter($frd)->orderbyDesc('id')->get()->all();
        return Inertia::render('ProductType/Index/Index', ['search' => $frd['search'], 'types' => $types]);
    }

    /**
     * @return Response
     */
    public function create()
    {
        return Inertia::render('ProductType/Create/Index');
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
            'name' => ['required', Rule::unique('products_types')],
            'image' => ['required', 'mimes:jpeg,jpg,png', 'max:1024'],
        ])->validateWithBag('storeType');
        $extension = $request->image->extension();
        $name = \Str::of($validated['name'])->ascii()->slug();
        $request->image->storeAs('/public/productType/', $name . "." . $extension);
        $url = Storage::url('productType/' . $name . "." . $extension);
        $this->types->create([
            'name' => $frd['name'],
            'image_url' => $url,
        ]);
        return back()->with([
            'modal_opened' => false
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * @param ProductType $type
     * @return Response
     */
    public function edit(ProductType $type)
    {
        return Inertia::render('ProductType/Edit/Index', ['type' => $type]);
    }

    /**
     * @param Request $request
     * @param ProductType $productType
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ProductType $type)
    {
        $frd = $request->all();
        $validated = Validator::make($frd, [
            'name' => ['required', Rule::unique('products_types')->ignore($type)],
            'image' => ['mimes:jpeg,jpg,png', 'max:1024'],
        ])->validateWithBag('updateType');
        if ($request->image) {
            $extension = $request->image->extension();
            $name = \Str::of($validated['name'])->ascii()->slug();
            $request->image->storeAs('/public/productType/', $name . "." . $extension);
            $url = Storage::url('productType/' . $name . "." . $extension);
        } else {
            $url = $type->getImageUrl();
        }
        $type->update([
            'name' => $frd['name'],
            'image_url' => $url,
        ]);
        return back()->with([
            'modal_opened' => false
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductType $productType)
    {
        //
    }
}
