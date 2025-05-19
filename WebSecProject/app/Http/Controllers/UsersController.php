<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Web\Artisan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Laravel\Socialite\Facades\Socialite;

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
        $user->save();


        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

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

        if(!$user->email_verified_at)
            return redirect()->back()->withInput($request->input())
                ->withErrors('Your email is not verified.');

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        // Automatically assign 'customer' role if user has no role
        if ($user->roles()->count() === 0) {
            $user->assignRole('customer');
        }
    
        return redirect('/');
    }

    public function doLogout(Request $request) {

        Auth::logout();

    return redirect('/');
    }

    public function profile(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
    
        // Authorization Check
        if (auth()->id() !== $user?->id && !auth()->user()->hasPermissionTo('show_users')) {
            abort(401);
        }
    
        return view('users.profile', compact('user'));
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'password' => bcrypt(uniqid()), // random password
                ]
            );

            if ($user->roles()->count() === 0) {
                $user->assignRole('customer');
            }

            Auth::login($user);

            return redirect('/'); 
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['msg' => 'Google login failed: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->can('manage_users')) {
            $users = User::all();
        } elseif ($user->can('manage_customers')) {
            $users = User::role('customer')->get();
        } else {
            abort(403, 'Unauthorized');
        }
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();
        if (!$authUser->can('manage_users')) {
            abort(403, 'Unauthorized');
        }
        
        // Get all available roles
        $roles = Role::pluck('name')->toArray();
        
        // Get the user's current role (if any)
        $userRole = $user->roles->first() ? $user->roles->first()->name : null;
        
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();
        if (!$authUser->can('manage_users')) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
        ]);
        
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        
        $user->syncRoles([$validated['role']]);
        
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();
        if (!$authUser->can('manage_users')) {
            abort(403, 'Unauthorized');
        }
        if ($authUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function showChangePasswordForm(User $user)
    {
        $authUser = auth()->user();
        if (!$authUser->can('change_passwords')) {
            abort(403, 'Unauthorized');
        }
        return view('users.change_password', compact('user'));
    }

    public function changePassword(Request $request, User $user)
    {
        $authUser = auth()->user();
        if (!$authUser->can('change_passwords')) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->numbers()->letters()->mixedCase()->symbols()]
        ]);
        $user->password = bcrypt($validated['password']);
        $user->save();
        return redirect()->route('users.index')->with('success', 'Password changed successfully.');
    }

    public function create()
    {
        $authUser = auth()->user();
        if (!$authUser->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        // $roles = ['customer', 'admin', 'employee', 'support agent', 'manager'];
        // return view('users.create', compact('roles'));

        $roles = Role::pluck('name')->toArray();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'role' => 'required|in:admin,customer,employee,support agent,manager',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        $user->assignRole($validated['role']);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

}