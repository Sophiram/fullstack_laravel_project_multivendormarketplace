<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Notifications\NewVendorRegistered;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Notification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'] ,// បន្ថែម validation សម្រាប់ role
        ]);

        $roleValue = ($request->role === 'vendor') ? 1 : 2;

       try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $roleValue,
                'is_approved' => ($request->role === 'vendor') ? false : true,
            ]);
        } catch (QueryException $e) {
            // ប្រសិនបើដានរកឃើញកំហុសរកមិនឃើញ Column (SQLSTATE[42S22])
            if ($e->getCode() === '42S22') {
                return redirect()->back()
                    ->withInput($request->only('name', 'email', 'role'))
                    ->withErrors(['email' => 'ប្រព័ន្ធ Database មិនទាន់មានទិន្នន័យ "is_approved" ឡើយ។ សូមទាក់ទង Admin ដើម្បីរត់ Migration!']);
            }

            // បើជាកំហុស Database ផ្សេងទៀត ឱ្យវាបង្ហាញសារធម្មតា
            return redirect()->back()->withInput()->withErrors(['email' => 'មានបញ្ហាបច្ចេកទេសទាក់ទងនឹង Database៖ ' . $e->getMessage()]);
        }
        if ($request->role === 'vendor') {
    // រក Admin (ប្រសិនបើអ្នកកំណត់ role 2 ជា admin ក្នុង database របស់អ្នក សូមប្តូរជាលេខ 2)
            $admin = User::where('role', 0)->first(); // ឧទាហរណ៍ 0 គឺជា Admin

            if ($admin) {
                $admin->notify(new NewVendorRegistered($user));
             }
        }

        event(new Registered($user));

        Auth::login($user);


        if ($user->role === 1) {
            return redirect()->route('home')
            ->with('vendor_registered', 'Registered successfully! Please wait for Admin។');
        }

        // return redirect(route('login'));
        // return redirect()->route('login');
        return redirect()->route('home');
    }
}
