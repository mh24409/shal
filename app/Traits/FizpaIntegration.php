<?php

namespace App\Traits;

use App\Models\Order;

trait FizpaIntegration
{
    public function fizpaNewOrder($combined_order, $city_id)
    {
        $authorizationToken = env('FIZPA_AUTHORIZATION_KEY');
        $Referer = env('FIZPA_Referer');

        $ch = curl_init();
        $order = $combined_order->orders()->latest()->first();
        $shipping_info = json_decode($order->shipping_address);
        $postData = json_encode([
            "SenderPhone" => "966541604084",
            "SenderName" => "SHAL Store | متجر شال",
            "SenderCityId" => 1,
            "SenderAddress" => $shipping_info->address,
            "SenderNeighborhood" => $shipping_info->address,
            "RecipientCityId" => $city_id ?? 1,
            "RecipientName" => $shipping_info->name,
            "RecipientPhone1" => $shipping_info->phone,
            "RecipientNeighborhood" => $shipping_info->address,
            "RecipientAddress" => $shipping_info->address,
            "OrderRef" => $order->id,
            "CodAmount" => $order->grand_total,
            "OrderPiecesCount" => $order->orderDetails->sum('quantity')
        ]);
        curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL",
            "Referer: https://shal.store"
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        $order->tracking_code  = $response->OrderId;
        $order->shipping_barcode  = $response->labelId;
        $order->save();
        return $response;
    }

    public function fizpaDeleteOrder($id)
    {
        $authorizationToken = env('FIZPA_AUTHORIZATION_KEY');
        $Referer = env('FIZPA_Referer');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/orders/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: .$authorizationToken",
            "Referer: .$Referer"
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public function fizpaPrintLabel($id)
    {
        $authorizationToken = env('FIZPA_AUTHORIZATION_KEY');
        $Referer = env('FIZPA_Referer');


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/orders/label/$id/ar/A4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL",
            "Referer: https://shal.store"
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $filename = 'label.pdf';
        file_put_contents($filename, $response);
        return response()->file($filename)->deleteFileAfterSend(true);
    }
    public function fizpaTrackingOrder($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/Tracking/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL",
            "Referer: https://shal.store"
        ));
        $response = curl_exec($ch);
        return $response;
    }
}
