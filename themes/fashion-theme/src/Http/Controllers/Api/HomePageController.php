<?php

namespace Theme\FashionTheme\Http\Controllers\Api;

use Core\Models\TlBlog;
use Core\Models\TlPage;
use Illuminate\Http\Request;
use Core\Models\TlBlogsCategory;
use App\Http\Controllers\Controller;
use Plugin\Ecommerce\Models\Product;
use Illuminate\Support\Facades\Cache;


use Illuminate\Support\Facades\Session;

use Plugin\Ecommerce\Repositories\ProductRepository;

use Theme\FashionTheme\Http\Resources\BlogsResource;
use Theme\FashionTheme\Http\Resources\DealResource;;

use Plugin\Ecommerce\Http\Resources\CollectionResource;

class HomePageController extends Controller
{

    public function __construct(public ProductRepository $productRepository) {}
    /**
     * Will return active home page sections
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function homePageSections()
    {
        if (isActivePlugin('pagebuilder')) {
            $page = TlPage::where('is_home', true)->first();
            if ($page == null) {
                return response()->json(
                    [
                        'success' => false
                    ]
                );
            }
            $cache_key = $page->id . '_' . Session::get('api_locale');
            if (Cache::has($cache_key)) {
                return Cache::get($cache_key);
            }
            if ($page != null) {
                $response = $this->pageBuilderResponse($page);
                Cache::put($cache_key, $response, now()->addMinutes(60));
                return $response;
            }
        }
    }


    /**
     * Page Builder Plugin Response
     */
    public function pageBuilderResponse($page)
    {
        if (isset($page->page_image)) {
            $page->page_image = getFilePath($page->page_image, true);
        }
        $page->title = $page->translation('title', Session::get('api_locale'));
        $page->content = $page->translation('content', Session::get('api_locale'));

        $page_sections = '';
        $page_builder_widgets = '';

        if ($page->page_type == 'builder') {
            $page_sections = \Plugin\PageBuilder\Helpers\BuilderHelper::getSectionLayoutWidgets($page->id, Session::get('api_locale'));
            $page_builder_widgets = \Plugin\PageBuilder\Helpers\BuilderHelper::$widget_list;
        }

        return response()->json(
            [
                'success' => true,
                'page' => $page,
                'page_sections' => $page_sections,
                'active_pagebuilder' => true,
                'page_builder_widgets' => $page_builder_widgets
            ]
        );
    }



    /**
     * Will return deals Details
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dealsDetails(Request $request)
    {
        try {
            $data = \Plugin\Flashdeal\Models\FlashDeal::with(['products', 'deal_translations'])->where('id', $request['id'])->first();
            $dealsDetails = new DealResource($data);
            $products = new \Plugin\Ecommerce\Http\Resources\ProductCollection($data->products()->take(6)->get());
            return response()->json(
                [
                    'success' => true,
                    'dealsDetails' => $dealsDetails,
                    'products' => $products
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        } catch (\Error $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        }
    }
    /**
     * Will return deal products
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dealProducts(Request $request)
    {
        $product_ids = \Plugin\Flashdeal\Models\FlashDealProducts::where('deal_id', $request['id'])->pluck('product_id');
        $products = $this->productRepository->productQuery()->whereIn('id', $product_ids)->where('status', config('settings.general_status.active'))->get()->take(6);
        return new \Plugin\Ecommerce\Http\Resources\ProductCollection($products);
    }
    /**
     * Will return collection details
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectionDetails(Request $request)
    {
        try {
            $data = \Plugin\Ecommerce\Models\ProductCollection::with(['collection_translations'])->findOrFail($request['id']);
            $details = new CollectionResource($data);
            return response()->json(
                [
                    'success' => true,
                    'details' => $details,
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        } catch (\Error $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        }
    }
    /**
     * Will return collections all products
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectionAllProducts(Request $request)
    {
        $product_ids = \Plugin\Ecommerce\Models\CollectionHasProducts::where('collection_id', $request['id'])->pluck('product_id');
        $products = Product::whereIn('id', $product_ids)->where('status', config('settings.general_status.active'))->paginate($request['perPage']);
        return new \Plugin\Ecommerce\Http\Resources\ProductCollection($products);
    }

    /**
     * Will return home page blogs
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function homePageBlogs(Request $request)
    {

        try {
            $blogs = [];
            if ($request->has('content')) {
                if ($request['content'] == 'latest') {
                    $blogs = TlBlog::where([
                        ['publish_at', '<', currentDateTime()],
                        ['is_publish', '=', config('settings.blog_status.publish')],
                    ])
                        ->orderBy('id', 'DESC')
                        ->take($request['quantity'])
                        ->get();
                }
                if ($request['content'] == 'featured') {
                    $blogs = TlBlog::where([
                        ['publish_at', '<', currentDateTime()],
                        ['is_publish', '=', config('settings.blog_status.publish')],
                    ])
                        ->where('is_featured', config('settings.general_status.active'))
                        ->orderBy('id', 'DESC')
                        ->take($request['quantity'])
                        ->get();
                }

                if ($request['content'] == 'category' && $request->has('category')) {
                    $blogs = TlBlog::where('is_publish', config('settings.general_status.active'))
                        ->whereIn('id', TlBlogsCategory::where('category_id', $request['category'])->pluck('blog_id'))
                        ->orderBy('id', 'DESC')
                        ->take($request['quantity'])
                        ->get();
                }
            }

            return new BlogsResource($blogs);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
}
