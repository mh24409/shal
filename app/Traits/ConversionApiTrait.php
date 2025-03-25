<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use Illuminate\Support\Carbon;
trait ConversionApiTrait
{
    public static function ViewContent($detailedProduct,$event_id)
    {
        $access_token = env('CONVERSIONS_API_PIXEL_TOKEN');
        $pixel_id = env('CONVERSIONS_API_PIXEL_ID');
        if(empty($access_token) || empty($pixel_id))
        {
            return null;
        }
        $api = Api::init(null, null, $access_token);
        $api->setLogger(new CurlLogger());
        $user_data = (new UserData())
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');
        $content = (new Content())
            ->setProductId($detailedProduct->id);
        $custom_data = (new CustomData())
            ->setContents(array($content))
            ->setCurrency("SAR")
            ->setValue($detailedProduct->unit_price);
        $event = (new Event())
            ->setEventName('ViewContent')
            ->setEventTime(time())
            ->setEventSourceUrl(route('product', ['slug' => $detailedProduct->slug]))
            ->setUserData($user_data)
            ->setCustomData($custom_data)
            ->setEventId($event_id)
            ->setActionSource(ActionSource::WEBSITE);
        $events = array();
        array_push($events, $event);
        $request = (new EventRequest($pixel_id))
            ->setEvents($events)->setTestEventCode(env('CONVERSATION_TEST_CODE'));
        $response = $request->execute();
    }
    public static function AddToCart($data,$event_id = null)
    {
        $slug = Product::find($data['product_id'])->slug;
        $access_token = env('CONVERSIONS_API_PIXEL_TOKEN');
        $pixel_id = env('CONVERSIONS_API_PIXEL_ID');
        if(empty($access_token) || empty($pixel_id))
        {
            return null;
        }
        $api = Api::init(null, null, $access_token);
        $api->setLogger(new CurlLogger());
        $user_data = (new UserData())
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');
        $content = (new Content())
            ->setProductId($data['product_id']);
        $custom_data = (new CustomData())
            ->setContents(array($content))
           ->setCurrency("SAR")
            ->setValue($data['price']);
        $event = (new Event())
            ->setEventName('AddToCart')
            ->setEventTime(time())
            ->setEventSourceUrl(route('product', ['slug' => $slug]))
            ->setUserData($user_data)
            ->setCustomData($custom_data)
            ->setEventId($event_id)
            ->setActionSource(ActionSource::WEBSITE);
        $events = array();
        array_push($events, $event);
        $request = (new EventRequest($pixel_id))
            ->setEvents($events)->setTestEventCode(env('CONVERSATION_TEST_CODE'));
        $response = $request->execute();
        
    }
    public static function InitiateCheckout($data,$event_id = null)
    {
        $ids = $data->pluck('product_id')->toArray();
        $count = $data->pluck('product_id');
        $commaSeparatedIds = implode(',', $ids);

        $price = $data->sum('price');
        $access_token = env('CONVERSIONS_API_PIXEL_TOKEN');
        $pixel_id = env('CONVERSIONS_API_PIXEL_ID');
        if(empty($access_token) || empty($pixel_id))
        {
            return null;
        }
        $api = Api::init(null, null, $access_token);
        $api->setLogger(new CurlLogger());
        $user_data = (new UserData())
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');
        $content = (new Content())
            ->setProductId($commaSeparatedIds);
        $custom_data = (new CustomData())
            ->setContents(array($content))
            ->setNumItems(count($data))
             ->setCurrency("SAR")
            ->setValue($price);
        $event = (new Event())
            ->setEventName('InitiateCheckout')
            ->setEventTime(time())
            ->setEventSourceUrl(route('checkout.shipping_info'))
            ->setUserData($user_data)
            ->setCustomData($custom_data)
            ->setEventId($event_id)
            ->setActionSource(ActionSource::WEBSITE);
        $events = array();
        array_push($events, $event);
        $request = (new EventRequest($pixel_id))
        ->setEvents($events)->setTestEventCode(env('CONVERSATION_TEST_CODE'));

        $response = $request->execute();
    }


    public static function Purchase($data,$event_id = null)
    {
        $totalItemsPurchased = 0;
        $totalShippingCost = 0;
        $orders = Order::where('combined_order_id', $data->id)->get();
        $PurchaseData = [];
        foreach ($orders as $order) {
            $order_details = OrderDetail::where('order_id', $order->id)->get();
            $categoryNames = [];
            foreach ($order_details as $order_detail) {
                $product = $order_detail->product;
                $products_name[] = $product->name;
                $totalItemsPurchased += $order_detail->quantity;
                $contentData[] = [
                    'id' => $order_detail->product_id,
                    'quantity' => $order_detail->quantity,
                    'item_price' => $order_detail->price,
                ];
                $content_ids[] = $order_detail->product_id;
                $category = $product->category;
                if ($category) {
                    $categoryNames[] = $category->name;
                }
                $totalShippingCost += $order_detail->shipping_cost;
            }
        }
        $content_ids = array_unique($content_ids);
        $PurchaseData['category_name'] = implode(', ', array_unique($categoryNames));
        $PurchaseData['content_ids'] = $content_ids;
        $PurchaseData['content_name'] = implode(', ', array_unique($products_name));
        $PurchaseData['content'] = $contentData;
        $PurchaseData['num_of_items'] = $totalItemsPurchased;
        $PurchaseData['shipping_cost'] = $totalShippingCost;
        $PurchaseData['total'] = $data->grand_total;
        $PurchaseData['user_role'] = auth()->check() ? 'user' : 'guest';
        $PurchaseData['value'] = $data->grand_total;
        $PurchaseData['shipping'] = $orders[0]['shipping_type'];
        $PhoneNumber = $orders[0]['phone'];
        $UserName = $orders[0]['name'];
        $Email = $orders[0]['email'] ?? "";
        $access_token = env('CONVERSIONS_API_PIXEL_TOKEN');
        $pixel_id = env('CONVERSIONS_API_PIXEL_ID');
        $shipping_address = json_decode($orders[0]['shipping_address']);
        if(empty($access_token) || empty($pixel_id))
        {
            return null;
        }
        $api = Api::init(null, null, $access_token);
        $api->setLogger(new CurlLogger());
        $user_data = (new UserData())
            ->setFirstName($UserName)
            ->setPhone($PhoneNumber)
            ->setState($shipping_address->state)
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');
        $custom_data = (new CustomData())
            ->setNumItems($PurchaseData['num_of_items'])
             ->setCurrency("SAR")
            ->setValue($PurchaseData['value'])
            ->setContentType('product')
            ->setContentCategory(implode(', ', array_unique($categoryNames)))
            ->setContentName(implode(', ', array_unique($products_name)))
            ->setOrderId($data->id);
        $event = (new Event())
            ->setEventName('Purchase')
            ->setEventTime(time())
            ->setEventSourceUrl(route('order_confirmed',$orders[0]->code))
            ->setUserData($user_data)
            ->setCustomData($custom_data)
            ->setEventId($event_id)
            ->setActionSource(ActionSource::WEBSITE);
        $events = array();
        array_push($events, $event);
        $request = (new EventRequest($pixel_id))
            ->setEvents($events)->setTestEventCode(env('CONVERSATION_TEST_CODE'));
        $response = $request->execute();
    }
}
