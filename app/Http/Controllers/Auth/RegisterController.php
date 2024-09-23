<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Handle a registration request for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if (get_option('enable_recaptcha_registration') == 1) {
            $this->validate($request, ['g-recaptcha-response' => 'required']);

            $secret = get_option('recaptcha_secret_key');
            $gRecaptchaResponse = $request->input('g-recaptcha-response');
            $remoteIp = $request->ip();

            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
            $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
            if (! $resp->isSuccess()) {
                return redirect()->back()->with('error', 'reCAPTCHA is not verified');
            }
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users,phone',
            'phone_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
            'password' => bcrypt($data['password']),
            'user_type' => 'user',
            'active_status' => '1',
        ]);
    }

    public function showRegistrationForm()
    {
        $countries = Country::whereNotNull('calling_code')
            ->distinct()
            ->pluck('calling_code', 'name');

        // Pass the countries to the registration view
        return view('auth.register', compact('countries'));
    }

    public function registerAuctionHouse()
    {
        $countries = Country::whereNotNull('calling_code')
            ->distinct()
            ->pluck('calling_code', 'name');

        // Pass the countries to the registration view
        return view('auth.register_auction_house', compact('countries'));
    }

    public function submitAuctionHouse(Request $request)
    {
        $this->validator($request->all())->validate();

        if (get_option('enable_recaptcha_registration') == 1) {
            $this->validate($request, ['g-recaptcha-response' => 'required']);

            $secret = get_option('recaptcha_secret_key');
            $gRecaptchaResponse = $request->input('g-recaptcha-response');
            $remoteIp = $request->ip();

            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
            $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
            if (! $resp->isSuccess()) {
                return redirect()->back()->with('error', 'reCAPTCHA is not verified');
            }
        }

        event(new Registered($user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone_code' => $request->phone_code,
            'password' => bcrypt($request->password),
            'user_type' => 'auction house',
            'is_auction_verified' => "0"
        ])));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
