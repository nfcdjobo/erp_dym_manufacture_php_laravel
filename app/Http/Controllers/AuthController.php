<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Si déjà connecté, rediriger ou afficher un message
        if (auth()->check()) {
            return redirect()->route('dashboard')
                ->with('info', 'Vous êtes déjà connecté');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Si déjà connecté, rediriger ou afficher un message
    if (auth()->check()) {
            return response()->json([
                'success' => false,
                'errors' => ['Vous êtes déjà connecté']
            ]);
    }
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ]);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'errors' => ['Email ou mot de passe incorrect']
            ]);
        }

        return redirect()->back();
    }

    public function register(Request $request)
    {
        if (auth()->check()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Vous êtes déjà connecté. Veuillez vous déconnecter pour créer un nouveau compte.']
                ]);
        }
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|min:2|max:255',
                'last_name' => 'required|string|min:2|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|regex:/^[0-9+\-\s]{8,15}$/|unique:users',
                'password' => 'required|string|min:8',
            ], [
                'first_name.required' => 'Le prénom est obligatoire',
                'first_name.min' => 'Le prénom doit contenir au moins 2 caractères',
                'last_name.required' => 'Le nom est obligatoire',
                'last_name.min' => 'Le nom doit contenir au moins 2 caractères',
                'email.required' => 'L\'email est obligatoire',
                'email.email' => 'L\'email n\'est pas valide',
                'email.unique' => 'Un utilisateur avec cet email existe déjà',
                'phone.required' => 'Le téléphone est obligatoire',
                'phone.regex' => 'Le numéro de téléphone n\'est pas valide',
                'phone.unique' => 'Un utilisateur avec ce téléphone existe déjà',
                'password.required' => 'Le mot de passe est obligatoire',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ]);
            }

            try {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès',
                    'user_id' => $user->id
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Erreur lors de la création de l\'utilisateur']
                ]);
            }
        }

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
