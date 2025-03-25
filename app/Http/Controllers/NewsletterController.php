<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriber;
use Mail;
use App\Mail\EmailManager;
use Carbon\Carbon;
class NewsletterController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:send_newsletter'])->only('index');
    }

    public function index(Request $request)
    {
        
        if($request->users_type){ 
            switch ($request->users_type) {
                case 'returned_customer':
                    $users = User::whereHas('orders', function ($query) {
                        $query->havingRaw('COUNT(*) > 1');
                    })->get();
                    break;
                case 'new_customer':
                    $users = User::doesntHave('orders')->get();
                    break;
                case 'abonded_customer_cart':  
                    $users = User::whereHas('carts', function ($query)  {
                        $query->where('created_at', '<', Carbon::now()->subHour()->toDateTimeString());
                    })->get();
                    break;
                default:
                     $users = User::all();
                    break;
            }
        }else{
            $users = User::all();
        }
        
        $subscribers = Subscriber::all();
        $sort_value = $request->users_type ?? null ;
        return view('backend.marketing.newsletters.index', compact('users', 'subscribers','sort_value'));
    }

    public function send(Request $request)
    {
        if (env('MAIL_USERNAME') != null) {
            //sends newsletter to selected users
        	if ($request->has('user_emails')) {
                foreach ($request->user_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_FROM_ADDRESS');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        //dd($e);
                    }
            	}
            }

            //sends newsletter to subscribers
            if ($request->has('subscriber_emails')) {
                foreach ($request->subscriber_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_FROM_ADDRESS');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        //dd($e);
                    }
            	}
            }
        }
        else {
            flash(translate('Please configure SMTP first'))->error();
            return back();
        }

    	flash(translate('Newsletter has been send'))->success();
    	return redirect()->route('admin.dashboard');
    }

    public function testEmail(Request $request){
        $array['view'] = 'emails.newsletter';
        $array['subject'] = "SMTP Test";
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = "This is a test email.";

        try {
            Mail::to($request->email)->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }

        flash(translate('An email has been sent.'))->success();
        return back();
    }
}
