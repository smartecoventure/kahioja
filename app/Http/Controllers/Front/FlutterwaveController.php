<?php

namespace App\Http\Controllers\Front;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Pagesetting;
use App\Models\PaymentGateway;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\User;
use App\Models\Bag;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Auth;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use DB;

class FlutterwaveController extends Controller
{
    public function initialize(Request $request)
    {
        if(Auth::user()){
            $user_id = Auth::user()->id;
            $user_type = 'user';
        }else if(Session::has('guest')){
            $user_id = Session::get('guest');
            $user_type = 'guest';
        }else{
            $user_id = 'guest_'.Str::random(5).time();
            $user_type = 'guest';
            Session::put('guest', $user_id);
        }

        if(Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
            $currency_name = $curr->name;
        }else{
            $curr = Currency::where('is_default','=',1)->first();
            $currency_name = $curr->name;
        }

        $gs = Generalsetting::findOrFail(1);
        $orderId = Str::random(4).time();
        

        if($request->productid == null){
            //Checkout from cart
            $update_order_id = DB::select("UPDATE `bags` SET `order_no`='$orderId' WHERE `user_id`='$user_id' && `paid`='unpaid'");
            $bag = DB::select("SELECT DISTINCT bags.id as 'bagId', bags.quantity, bags.paid, products.id, products.user_id, products.ship_fee, products.price FROM bags, products, users WHERE bags.product_id = products.id && bags.user_id = '$user_id' && bags.paid = 'unpaid' ORDER BY bags.id DESC");
        
        }else{
            //Buy Now
            Bag::create([
                'product_id'=> $request->productid,
                'user_id'=> $user_id,
                'user_type'=> $user_type,
                'quantity'=> $request->productquantity,
                'paid'=> 'unpaid',
                'order_no'=> $orderId
            ]);
            $bag = DB::select("SELECT DISTINCT bags.id as 'bagId', bags.quantity, bags.paid, products.id, products.user_id, products.ship_fee, products.price FROM bags, products, users WHERE bags.product_id = products.id && bags.user_id = '$user_id' && bags.paid = 'unpaid' ORDER BY bags.id DESC LIMIT 1");
        }
        
        // return $response = \Response::json($bag, 200);
        
        $reference = Flutterwave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $request->amount,
            'email' => $request->email,
            'tx_ref' => $reference,
            'currency' => "NGN",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => $request->email,
                "phone_number" => $request->phone,
                "name" => $request->name
            ],

            "customizations" => [
                "title" => 'Checkout Payment',
                "description" => "Payment for items purchased at Kahioja Stores"
            ]
        ];

        $payment = Flutterwave::initializePayment($data);


        if($payment['status'] !== 'success') {
            return redirect()->route('front.checkoutfailed');
        }

        $order = new Order;

        $item_name = $gs->title." Order";
        $item_number = Str::random(4).time();
        
        $order['user_id'] = $request->user_id;
        $order['cart'] = ''; 
        $order['totalQty'] = 1;
        $order['pay_amount'] = $request->amount;
        $order['customer_email'] = $request->email;
        $order['customer_name'] = $request->name;
        $order['shipping_cost'] = 1;
        $order['packing_cost'] = 1;
        $order['tax'] = 1;
        $order['customer_phone'] = $request->phone;
        $order['order_number'] = $orderId;
        $order['customer_address'] = $request->address;
        $order['customer_country'] = 'Nigeria';
        $order['customer_city'] = $request->city;
        $order['customer_zip'] = $request->zip;
        $order['shipping_email'] = $request->shipping_email;
        $order['shipping_name'] = $request->shipping_name;
        $order['shipping_phone'] = $request->shipping_phone;
        $order['shipping_address'] = $request->shipping_address;
        $order['shipping_country'] = $request->shipping_country;
        $order['shipping_city'] = $request->shipping_city;
        $order['shipping_zip'] = $request->shipping_zip;
        $order['order_note'] = $request->order_notes;
        $order['coupon_code'] = $request->coupon_code;
        $order['coupon_discount'] = $request->coupon_discount;
        $order['dp'] = 0;
        $order['payment_status'] = "Pending";
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;

        if($order['dp'] == 1){
            $order['status'] = 'completed';
        }

        $order->save();

        if($order->dp == 1){
            $track = new OrderTrack;
            $track->title = 'Completed';
            $track->text = 'Your order has completed successfully.';
            $track->order_id = $order->id;
            $track->save();
        }else {
            $track = new OrderTrack;
            $track->title = 'Pending';
            $track->text = 'You have successfully placed your order.';
            $track->order_id = $order->id;
            $track->save();
        }

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();

        //Substracting From Stock
        foreach($bag as $prod){
            $quantity_purchased = $prod->quantity;
            if($quantity_purchased != null){
                $product = Product::findOrFail($prod->id);
                $quantity_left = $product->stock - $quantity_purchased;
                $product->stock = $quantity_left;
                $product->update();  
                
                if($product->stock <= 5){
                    $notification = new Notification;
                    $notification->product_id = $product->id;
                    $notification->save();                    
                }              
            
            }
        }

        //Sending vendor notification
        foreach($bag as $prod){
            if($prod->user_id != 0){
                $vorder =  new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = $prod->user_id;
                $notf[] = $prod->user_id;
                $vorder->qty = $prod->quantity;
                $vorder->price = $prod->price;
                $vorder->ship_fee = $prod->ship_fee;
                $vorder->order_number = $order->order_number;             
                $vorder->save();
            }
        }


        //Sending User Notification
        if(!empty($notf)){
            $users = array_unique($notf);
            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();    
            }
        }

        // Updating Bag 
        foreach($bag as $prod){
            $cart = Bag::findOrFail($prod->bagId);
            $payment_status = 'paid';
            $cart->paid = $payment_status;
            $cart->update();
        }

        Session::put('temporder', $order);
        Session::put('tempbag', $bag);
        Session::put('orderNo', $order['order_number']);
        Session::put('deliveryFee', $request->deliveryFee);
        Session::put('serviceFee', $request->serviceFee);
            
        return redirect($payment['data']['link']);
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        
        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
        
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            $transactID = $data['data']['id'];
            $tx_ref = $data['data']['tx_ref'];
            $amount = $data['data']['amount'];
            $charge_fee = $data['data']['app_fee'];
            $amount_paid = $amount + $charge_fee;
            $currency = $data['data']['currency'];
            $date = date("D M j Y G:i:s",  strtotime($data['data']['created_at'])  + 1 * 3600);
            
            $payment_type = $data['data']['payment_type'];
            
            if($payment_type == 'card'){
                $last_4digits = $data['data']['card']['last_4digits'];
                $card_type = $data['data']['card']['type'];
                $card_details = $payment_type.'-'.$card_type.'-'.$last_4digits;
            }else{
                $card_details = $payment_type;
            }
            
            $customer_email = $data['data']['customer']['email'];
            $customer_phone = $data['data']['customer']['phone_number'];
            
            
            Session::put('transactID', $transactID);
            Session::put('payment_type', $payment_type);
            Session::put('customer_email', $customer_email);
            Session::put('customer_phone', $customer_phone);
            
            $orderNo = Session::get('orderNo');

            $order = Order::where('order_number', $orderNo)->update([
                    'payment_status'=> 'completed',
                    'method'=> $card_details,
                    'txnid'=> $transactID]);
            
            return redirect()->route('front.checkoutsuccess');

        }elseif ($status ==  'cancelled'){
            return redirect()->route('front.checkout');
        }else{
            return redirect()->route('front.checkoutfailed');
        }
    }
    
    public function checkoutfailed()
    {
        return view('front.checkoutfailed');
    }

    public function checkoutsuccess()
    {
        $gs = Generalsetting::findOrFail(1);
        
        if(Session::has('tempbag')){
            $transactID = Session::get('transactID');
            $payment_type = Session::get('payment_type');
            $deliveryFee = Session::get('deliveryFee');
            $serviceFee = Session::get('serviceFee');
            $oldBag = Session::get('tempbag');
            $tempbag = new Bag($oldBag);
            $order = Session::get('temporder');
            
            $order_no = $order->order_number;

            //Getting the items in the bag
            $bags = DB::select("SELECT DISTINCT bags.id as 'bagId', bags.quantity, bags.order_no, bags.paid, products.id, products.name, products.photo, products.price, orders.status, orders.created_at 
                FROM bags, products, orders
                WHERE bags.order_no = '$order_no' && bags.product_id = products.id && orders.order_number = bags.order_no
                ORDER BY bags.id DESC");
        
            // dd($bags);
            return view('front.checkoutsuccess', compact('gs', 'transactID', 'order', 'tempbag', 'deliveryFee', 'serviceFee', 'bags'));
        }

    }
}
