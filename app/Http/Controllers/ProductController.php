<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Rules\IngredientsRequireRule;
use App\Rules\ProductsRequireRule;
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
            'image' => ['required', 'mimes:jpeg,jpg,png,webp', 'max:1024'],
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
            'image' => ['mimes:jpeg,jpg,png,webp', 'max:1024'],
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
            'unit_value' => ['required', 'max:8'],
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
        $products = $user->products()->filter($frd)->orderbyDesc('id')->get()->toArray();

        return Inertia::render('Products/User/Index/Index', ['search' => $frd['search'], 'products' => $products]);
    }

    /**
     * @param Product $product
     * @return RedirectResponse
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
            'unit_value' => ['required', 'max:8'],
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

    /**
     * @return Response
     */
    public function scan()
    {
        return Inertia::render('Products/Scan/Index');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getProductsFromQr(Request $request)
    {
        $frd = $request->all();
        $url = "https://irkkt-mobile.nalog.ru:8888/v2/mobile/users/lkfl/auth";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Host: irkkt-mobile.nalog.ru:8888",
            "Accept: */*",
            "Device-OS: iOS",
            "Device-Id: 7C82010F-16CC-446B-8F66-FC4080C66521",
            "clientVersion: 2.9.0",
            "Accept-Language: ru-RU;q=1, en-US;q=0.9",
            "User-Agent: billchecker/2.9.0 (iPhone; iOS 13.6; Scale/2.00)",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '{"inn":"' . env('MIX_NALOG_LOGIN') . '","client_secret":"' . env('MIX_CLIENT_SECRET') . '","password":"' . env('MIX_NALOG_PASSWORD') . '"}';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $resp = curl_exec($curl);
        curl_close($curl);
        $sessionId = json_decode($resp)->{'sessionId'};
        $url = "https://irkkt-mobile.nalog.ru:8888/v2/ticket";
        $data = '{"qr":"' . $frd['result'] . '"}';
        $headers = array(
            "Host: irkkt-mobile.nalog.ru:8888",
            "Accept: */*",
            "Device-OS: iOS",
            "Device-Id: 7C82010F-16CC-446B-8F66-FC4080C66521",
            "clientVersion: 2.9.0",
            "Accept-Language: ru-RU;q=1, en-US;q=0.9",
            "User-Agent: billchecker/2.9.0 (iPhone; iOS 13.6; Scale/2.00)",
            "Content-Type: application/json",
            "sessionId: " . $sessionId . "",
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $resp = curl_exec($curl);

        curl_close($curl);
        $ticketId = json_decode($resp)->{'id'};

        $url = "https://irkkt-mobile.nalog.ru:8888/v2/tickets/" . $ticketId . "";
        $headers = array(
            "Host: irkkt-mobile.nalog.ru:8888",
            "sessionId: " . $sessionId . "",
            "Accept: */*",
            "Device-OS: iOS",
            "Device-Id: 7C82010F-16CC-446B-8F66-FC4080C66521",
            "clientVersion: 2.9.0",
            "Accept-Language: ru-RU;q=1, en-US;q=0.9",
            "User-Agent: billchecker/2.9.0 (iPhone; iOS 13.6; Scale/2.00)",
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $resp = curl_exec($curl);
        curl_close($curl);
        if (isset(json_decode($resp, true)['ticket'])) {
            $products = json_decode($resp, true)['ticket']['document']['receipt']['items'];

        } else {
            return Inertia::render('Products/Scan/Index');
        }
        foreach ($products as $product) {
            $name = explode(' ', trim($product['name']))[0];
            $name = rtrim($name, ",");
            $frd['products'][] = $name;
        }
//        $products = $this->products->filter($frd)->orderbyDesc('id')->get()->all();
//        return Inertia::render('Products/Scan/Products', ['search' => $frd['search'] ?? null, 'products' => $products]);
        $selectedProducts = $this->products->filter($frd)->get(['name', 'unit', 'id'])->toArray();
        $productsList = Product::getList();
        return Inertia::render('Products/Scan/Products', ['selectedProducts' => $selectedProducts, 'productsList' => $productsList]);
    }

    public function addProductsFromQr(Request $request)
    {
        $frd = $request->all();
        $selectedProducts = $frd['selectedProducts'];
        $validated = Validator::make($frd, [
            'selectedProducts' => ['required', new ProductsRequireRule()],
        ])->validateWithBag('addProducts');
        $user = \Auth::getUser();
        foreach ($selectedProducts as $selectedProduct) {
            $product = Product::find($selectedProduct['id']);
            $user->products()->detach($product->getKey());
            $product->user()->attach($user->getKey(), [
                'unit_value' => $selectedProduct['unit_value'],
            ]);
        }
        return redirect(route('my.products'));
    }
}
