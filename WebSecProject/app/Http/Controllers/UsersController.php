<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Web\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    use ValidatesRequests;

    public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed',
            Password::min(8)->numbers()->letters()->mixedCase()->symbols()]
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);  
        // Automatically verify email
        $user->email_verified_at = now();
        $user->save();

        // Assign default user role
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $user->roles()->attach($userRole->id);

        return redirect("/");
    }

    public function verify(Request $request) {
        try {
            $decryptedData = json_decode(Crypt::decryptString($request->token), true);
            $user = User::find($decryptedData['id']);
            if(!$user) abort(401);
            $user->email_verified_at = Carbon::now();
            $user->save();
            
            return view('emails.verified', compact('user'));
        } catch (DecryptException $e) {
            return redirect('/')->withErrors(['message' => 'Invalid or expired verification link.']);
        }
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
        $user = User::where('email', $request->email)->first();

        if(!$user)
            return redirect()->back()->withInput($request->input())
                ->withErrors('No email found.');

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
            $user = User::where('email', $request->email)->first();
            Auth::setUser($user);
        
        return redirect('/');
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {
        $user = $user ?? Auth::user();
    
        // Authorization Check
        if (Auth::id() !== $user?->id && !Auth::user()->isEmployee()) {
            abort(401);
        }
    
        return view('users.profile', compact('user'));
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('status', 'Email already verified.');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            $request->user()->update(['verification_sent_at' => now()]);
            
            return back()->with('status', 'Verification link has been resent to your email address.');
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to send verification email. Please try again later or contact support.');
        }
    }
}