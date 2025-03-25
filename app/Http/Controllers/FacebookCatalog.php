<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\FacebookCatalog as ModelsFacebookCatalog;
use App\Models\FaceCatalog;
use App\Models\Product;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Storage;

class FacebookCatalog extends Controller
{
    public function index()
    {
        $catalogs = ModelsFacebookCatalog::orderBy('id', 'DESC')->paginate(20);
        return view('backend.marketing.catalog.index', compact('catalogs'));
    }
    public function edit($id)
    {
        $catalog = ModelsFacebookCatalog::findOrFail($id);
        return view('backend.marketing.catalog.edit', compact('catalog'));
    }
    public function update(Request $request, $id)
    {
        $catalog = ModelsFacebookCatalog::findOrfail($id);
        Storage::delete('xml/facebook/cataloug/dokkan/agency/' . $catalog->catalog_name . $catalog->password . '.xml');
        $ids = [];

        if ($request->has('categories') && $request->has('products')) {
            foreach ($request->categories as $category_id) {
                $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();
                $ids = array_merge($ids, $productIds);
            }
            $requestProductIds = $request->input('products');
            $ids = array_merge($ids, $requestProductIds);
        } elseif ($request->has('categories')) {
            foreach ($request->categories as $category_id) {
                $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();
                $ids = array_merge($ids, $productIds);
            }
        } else {
            $ids = $request->input('products');
        }

        $categories_ids =  ($request->categories);

        $catalog->update([

            'catalog_name' => $request->catalog_name,
            'products' => $ids,
            'categories' => $categories_ids,
            'password' => $request->password
        ]);
        // $face_catalog->update([

        // ]);

        $products = Product::whereIn('id', $ids)->get();

        $atom = new \DOMDocument('1.0', 'utf-8');
        $atom->formatOutput = true;

        $feed = $atom->createElement('feed');
        $feed->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $atom->appendChild($feed);

        $title = $atom->createElement('title', $request->catalog_name);
        $feed->appendChild($title);

        $link = $atom->createElement('link');
        $link->setAttribute('rel', 'self');
        $link->setAttribute('href', route('feed.atom'));
        $feed->appendChild($link);
        foreach ($products as $product) {
            if ($product->current_stock > 0) {
                $is_available = "InStock";
            } else {
                $is_available = "OutStock";
            }
            if ($product->brand_id == null) {
                $brand = env('APP_NAME');
            } else {
                $brand = Brand::find($product->brand_id)->name;
            }
            $image_link = Upload::find($product->thumbnail_img)->file_name;
            $entry = $atom->createElement('entry');
            $id = $atom->createElement('g:id', $product->id);
            $entry->appendChild($id);
            $title = $atom->createElement('g:title', $product->name);
            $entry->appendChild($title);

            $content = $atom->createElement('g:description', strip_tags(html_entity_decode($product->description)));
            $entry->appendChild($content);

            $availability = $atom->createElement('g:availability', $is_available);
            $entry->appendChild($availability);

            $condition = $atom->createElement('g:condition', "new");
            $entry->appendChild($condition);

            $brandElement = $atom->createElement('g:brand', $brand);
            $entry->appendChild($brandElement);

            $price = $atom->createElement('g:price', $product->unit_price);
            $entry->appendChild($price);

            $linkElement = $atom->createElement('g:link', route('product', ['slug' => $product->slug]));
            $entry->appendChild($linkElement);
            $imageLinkElement = $atom->createElement('g:image_link', env('APP_URL') . '/public' . '/' . $image_link);
            $entry->appendChild($imageLinkElement);
            $feed->appendChild($entry);
        }
        $publicXmlPath = public_path('xml/facebook/cataloug/dokkan/agency/');
        if (!File::isDirectory($publicXmlPath)) {
            File::makeDirectory($publicXmlPath, 0755, true, true);
        }
        $xmlFilePath = $publicXmlPath . '/' . $request->catalog_name . $request->password . '.xml';


        $atom->save($xmlFilePath);
        return  response()->download($xmlFilePath, $request->catalog_name . $request->password . '.xml', ['Content-Type' => 'application/xml']);

        return Response::make(file_get_contents($xmlFilePath), '200')->header('Content-Type', 'application/atom+xml');
        return redirect()->route('catalog.index');
    }
    public function create()
    {
        return view('backend.marketing.catalog.create');
    }

    public function store(Request $request)
    {
        $ids = [];
        if ($request->has('categories') && $request->has('products')) {
            foreach ($request->categories as $category_id) {
                $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();
                $ids = array_merge($ids, $productIds);
            }
            $requestProductIds = $request->input('products');
            $ids = array_merge($ids, $requestProductIds);
        } elseif ($request->has('categories')) {
            foreach ($request->categories as $category_id) {
                $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();
                $ids = array_merge($ids, $productIds);
            }
        } else {
            $ids = $request->input('products');
        }
        $categories_ids =  ($request->categories);
        $face_catalog = ModelsFacebookCatalog::create([
            'catalog_name' => $request->catalog_name,
            'products' => $ids,
            'categories' => $categories_ids,
            'password' => $request->password
        ]);
        $products = Product::whereIn('id', $ids)->get();
        $atom = new \DOMDocument('1.0', 'utf-8');
        $atom->formatOutput = true;

        $feed = $atom->createElement('feed');
        $feed->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $atom->appendChild($feed);

        $title = $atom->createElement('title', $request->catalog_name);
        $feed->appendChild($title);

        $link = $atom->createElement('link');
        $link->setAttribute('rel', 'self');
        $link->setAttribute('href', route('feed.atom'));
        $feed->appendChild($link);
        foreach ($products as $product) {
            if ($product->current_stock > 0) {
                $is_available = "InStock";
            } else {
                $is_available = "OutStock";
            }
            if ($product->brand_id == null) {
                $brand = env('APP_NAME');
            } else {
                $brand = Brand::find($product->brand_id)->name;
            }
            if($product->discount_type == "percent")
            {            
                $sale_price = $product->unit_price - ($product->discount * 100) ;
            }else{
                $sale_price = $product->unit_price - $product->discount;
            }

            $image_link = Upload::find($product->thumbnail_img)->file_name;
            $entry = $atom->createElement('entry');
            $id = $atom->createElement('g:id', $product->id);
            $entry->appendChild($id);
            $title = $atom->createElement('g:title', $product->name);
            $entry->appendChild($title);

            $content = $atom->createElement('g:description', strip_tags(html_entity_decode($product->description)));
            $entry->appendChild($content);

            $availability = $atom->createElement('g:availability', $is_available);
            $entry->appendChild($availability);

            $condition = $atom->createElement('g:condition', "new");
            $entry->appendChild($condition);

            $brandElement = $atom->createElement('g:brand', $brand);
            $entry->appendChild($brandElement);

            $price = $atom->createElement('g:price', $product->unit_price);
            $entry->appendChild($price);

            $Saleprice = $atom->createElement('g:sale_price', $sale_price);
            $entry->appendChild($Saleprice);

            $linkElement = $atom->createElement('g:link', route('product', ['slug' => $product->slug]));
            $entry->appendChild($linkElement);
            $imageLinkElement = $atom->createElement('g:image_link', env('APP_URL') . '/public' . '/' . $image_link);
            $entry->appendChild($imageLinkElement);
            $feed->appendChild($entry);
        }
        $publicXmlPath = public_path('xml/facebook/cataloug/dokkan/agency/');
        if (!File::isDirectory($publicXmlPath)) {
            File::makeDirectory($publicXmlPath, 0755, true, true);
        }
        $xmlFilePath = $publicXmlPath . '/' . $request->catalog_name . $request->password . '.xml';
        $atom->save($xmlFilePath);
        return response()->download($xmlFilePath, $request->catalog_name . $request->password . '.xml', ['Content-Type' => 'application/xml']);

        return Response::make(file_get_contents($xmlFilePath), '200')->header('Content-Type', 'application/atom+xml');
        return redirect()->route('catalog.index');
    }
    public function destroy($id)
    {
        $catalog = ModelsFacebookCatalog::findOrfail($id);
        Storage::delete('xml/facebook/cataloug/dokkan/agency/' . $catalog->catalog_name . '.xml');
        $catalog->delete();
        return back();
    }
}
