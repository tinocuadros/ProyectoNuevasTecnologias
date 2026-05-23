<?php
namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\Facades\Mail;


class ForgotPasswordController extends Controller {
    public function enviarNuevaContrasenia(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Este correo no está registrado en nuestro sistema.'
        ]);
    }
}