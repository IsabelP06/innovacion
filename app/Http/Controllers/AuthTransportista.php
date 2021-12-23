<?php

namespace App\Http\Controllers;

use App\Models\Transportista;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthTransportista extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('panel_transportista.home');
    }
    public function login()
    {
        return view('panel_transportista.login');
    }
    public function autenticatedWithToken(Request $request)
    {
        try{
        $secret = env("SECRET_KEY_IONIC");
        $jwt = $request->header("Authorization");
        if ($jwt) {
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];
            $expiration = json_decode($payload)->exp;
            $base64UrlHeader = base64_encode($header);
            $base64UrlPayload = base64_encode($payload);
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignature = base64_encode($signature);
            $signatureValid = ($base64UrlSignature === $signatureProvided);
            $header = json_decode($header);
            if ($signatureValid) {
                $xtiempoactual = time();
                if ($expiration > $xtiempoactual) {
                    $payload = json_decode($payload);
                    $user = Transportista::find($payload->user_id);
                    return response(["success" => true, "data" =>  $user, "message" => "Bienvenido"]);
                } else {
                    return response(["success" => false, "message" => "Su token a caducado"]);
                }
            }
        } else {

            return response(["success" => false, "message" => "No se envio un token"]);
        }
        return response(["success" => false, "message" => "Token no valido"]);
        }catch(Exception $e){
            return response(["success" => false, "message" => $e->getMessage()]);
        }
    }
    public function loginWithToken(Request $request)
    {
        try {
            $secret = env("SECRET_KEY_IONIC");
            $durationtoken = env("TIMESTAMP_TOKEN_IONIC");
            $header = json_encode([
                'typ' => 'JWT',
                'alg' => 'HS256'
            ]);
            $usuario = $request->correo;
            $password = $request->password;
            $usuarioatenticar = DB::table('transportista')->where("usuario", $usuario)->first();
            $tiempovencimiento = time() + $durationtoken;
            if ($usuarioatenticar) {
                if (Hash::check($password, $usuarioatenticar->password)) {
                    $payload = json_encode([
                        'user_id' => $usuarioatenticar->id,
                        'exp' => $tiempovencimiento
                    ]);
                    $base64UrlHeader = base64_encode($header);
                    $base64UrlPayload = base64_encode($payload);
                    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
                    $base64UrlSignature = base64_encode($signature);
                    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
                    return response()->json(["jwt" => $jwt, "success" => true, "data" => $usuarioatenticar]);
                } else {
                    return response(["success" => false, "message" => "Contraseña incorrecta"]);
                }
            } else {
                return response(["success" => false, "message" => "Usuario incorrecto"]);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()]);
        }
    }
    public function logout(Request $request)
    {
        Auth::guard('transportista')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/transportista/login');
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::guard('transportista')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOMETRANSPORTISTA);
        }
        return back()->withErrors([
            'email' => 'Usuario o contraseña incorrectos.',
        ]);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            "password" => "required",
            "password_new" => "required",
            "password_verify" => "required"
        ]);
        if ($request->password_new != $request->password_verify) {
            return back()->with("error", "Las contraseñas indicadas no coinciden ingrese correctamente");
        }
        $transortista = Transportista::find(auth("transportista")->user()->id);
        if (!Hash::check($request->password, $transortista->password)) {
            return back()->with("error", "La contraseña indicada no es correcta");
        }
        $transortista->password = Hash::make($request->password_new);
        $transortista->save();
        return back()->with("success", "Contraseña actualizada correctamente");
    }
    public function changePassword2(Request $request)
    {
        if(!$request->transportista){
            return response()->json(["success" => false, "message" => "No se envio el id del transportista"]);
        }
        if ($request->newpassword != $request->confirmpassword) {
            return response()->json(["success" => false, "message" => "Las contraseñas indicadas no coinciden ingrese correctamente"]);
        }
        $transortista = Transportista::find($request->transportista);
        if (!Hash::check($request->password, $transortista->password)) {
            return response()->json(["success" => false, "message" => "La contraseña indicada no es correcta"]);
        }
        $transortista->password = Hash::make($request->newpassword);
        $transortista->save();
        return response()->json(["success" => true, "message" => "Contraseña actualizada correctamente"]);
    }
    public function changeCorreo(Request $request)
    {
        $request->validate([
            "correo" => "required"
        ]);
        $transortista = Transportista::find(auth("transportista")->user()->id);
        $transortista->correo = $request->correo;
        $transortista->save();
        return back()->with("success", "Su correo a sido actualizado");
    }
    public function changeCorreo2(Request $request)
    {
        if(!$request->correo || !$request->transportista){
            return response()->json(["success" => false, "message" => "No se envio el correo o el id del transportista"]);
        }
        $transortista = Transportista::find($request->transportista);
        $transortista->correo = $request->correo;
        $transortista->save();
        return response()->json(["success" => true, "message" => "Su correo a sido actualizado"]);
    }
    public function create()
    {
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
