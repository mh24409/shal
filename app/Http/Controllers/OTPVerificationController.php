<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Carbon\Carbon;

use App\Models\Cart;
use App\Models\User;
use App\Utility\SmsUtility;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;

class OTPVerificationController extends Controller
{
    public function verification(Request $request)
    {
        if (Auth::check() && Auth::user()->verification_code != null && Auth::user()->verification_code) {
            return view('otp_systems.frontend.user_verification');
        } elseif (Auth::check() && Auth::user()->verification_code == null) {
            flash('You have already verified your number')->warning();
            return redirect()->route('home');
        } else {
            flash(translate('Your Phone Format Is Not Correct Please Write Phone Without 966'))->warning();
            return redirect()->route('home');
        }
    }
    public function verify_phone(Request $request)
    { 
        $user = Auth::user(); 
        if ($user->verification_code == $request->verification_code   && $user->otp_expire_at !=null && Carbon::now()->lessThan($user->otp_expire_at)) {
            $user->email_verified_at = date('Y-m-d h:m:s');
            $user->verification_code = null;
            $user->otp_expire_at = NULL;
            $user->is_verified = 1;
            $user->save();
            flash(translate('Your account has been verified successfully'))->success();

            $cart = Cart::where('user_id', $user->id)->get();
            if (count($cart) > 0) {
                if($request->redirect == 'cart'){
                    return redirect()->route('cart');
                }else{
                    return redirect()->route('checkout.shipping_info'); 
                }
          
            } else {
                return redirect()->route('home');
            }
        } else if ($user->verification_code == $request->verification_code   && $user->otp_expire_at && Carbon::now()->greaterThan($user->otp_expire_at)) {
            flash(translate('Your verification code has expired. Please request a new one.'))->warning();
            return redirect()->back();
        } else {
            flash('Invalid Code')->error();
            return back(); 
        }
    }
     
    public function resend_verificcation_code(Request $request)
    {
        $user = Auth::user();
        $otpCode = mt_rand(1000, 9999);
        $user->verification_code = $otpCode;
        $user->otp_expire_at = Carbon::now()->addMinutes(10);
        $user->save();
        $phone = str_replace("+", "", $user->phone);
        AuthOtp($phone, $otpCode, $user);
        return back();
    }
    public function reset_password_with_code(Request $request)
    {
        $phone = "+{$request['country_code']}{$request['phone']}";
        if (($user = User::where('phone', $phone)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    flash("Password has been reset successfully")->success();
                    return redirect()->route('admin.dashboard');
                }
                flash("Password has been reset successfully")->success();
                return redirect()->route('home');
            } else {
                flash("Password and confirm password didn't match")->warning();
                return view('otp_systems.frontend.auth.passwords.reset_with_phone');
            }
        } else {
            flash("Verification code mismatch")->error();
            return view('otp_systems.frontend.auth.passwords.reset_with_phone');
        }
    }


    /**
     * @param  User $user
     * @return void
     */

    public function send_code($user)
    {
        $otpCode = mt_rand(1000, 9999);
        $user->verification_code = $otpCode;
        $user->save();
        $phone = str_replace("+", "", $user->phone);
        $ednaMessage = AuthOtp($phone, $otpCode, $user);
        $responseData = json_decode($ednaMessage->getContent(), true);


        if ($responseData['success'] == false) {
            Auth::logout();

            $user->destroy($user->id);
        }
    }
    public function code_for_complete_order($user)
    {
        $otpCode = mt_rand(1000, 9999);
        $user->verification_code = $otpCode;
        $user->save();
        $phone = str_replace("+", "", $user->phone);
        $ednaMessage = OrderOtp($phone, $otpCode, $user);
        $responseData = json_decode($ednaMessage->getContent(), true);
    }
    /**
     * @param  Order $order
     * @return void
     */
    public function send_order_code($order)
    {
        $phone = json_decode($order->shipping_address)->phone;
        if ($phone != null) {
            SmsUtility::order_placement($phone, $order);
        }
    }

    /**
     * @param  Order $order
     * @return void
     */
    public function send_delivery_status($order)
    {
        $phone = json_decode($order->shipping_address)->phone;
        if ($phone != null) {
            SmsUtility::delivery_status_change($phone, $order);
        }
    }

    /**
     * @param  Order $order
     * @return void
     */
    public function send_payment_status($order)
    {
        $phone = json_decode($order->shipping_address)->phone;
        if ($phone != null) {
            SmsUtility::payment_status_change($phone, $order);
        }
    }
}
