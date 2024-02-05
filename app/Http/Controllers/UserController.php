<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Mail\NuevoUsuario;
use App\User;
use Session;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;
use Auth;
use Cookie;
use Flasher\Prime\FlasherInterface;
use Mail;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(){
        canPassAdminJefe();
        $userlog = User::where('id',userId())->get();
        $usersa = User::where('active',1)->where('id','!=',userId())->orderBy('ap_paterno','asc')->get();
        $usersi = User::where('active','!=',2)->where('active','!=',1)->orderBy('ap_paterno','asc')->get();
        $usersa = $userlog->merge($usersa);
        $usersa = $usersa->merge($usersi);

        $usersactive = User::where('active',1)->count();
        $usersinactive = User::where('active',0)->count();
        $usersdelete = User::where('active',2)->count();
        Session::put('item','6.');
        return view('users.index', compact('usersactive','usersinactive','usersdelete','usersa'));
    }

    public function show( Request $request, $cod){
        canPassAdminJefe();
        $user = User::findOrFail(decode($cod));

        if($user->active != '2'){
            Session::put('item','6.');
            return view('users.show',compact('user'));
        }else{
            abort(404);
        }
        abort(403);
    }

    public function create(){
        canPassAdminJefe();
        $roles = Role::get();
        Session::put('item','6.');
        return view('users.create',compact('roles'));
    }

    public function store(Request $request, FlasherInterface $flasher) {
        canPassAdminJefe();
        $messages = [
            'username.required' => 'El campo nombre de usuario es requerido.',
            'password.required' => 'La contraseña es obligatoria',
            'name.required' => 'El campo Nombre(s) es obligatorio',
            'name.min' => 'El campo Nombre(s) debe tener como mínimo 2 caracteres',
            'name.max' => 'El campo Nombre(s) debe tener como máximo 40 caracteres',
            'ap_paterno.required' => 'El campo Apellido Paterno es obligatorio',
            'ap_paterno.min' => 'El campo Apellido Paterno debe tener como mínimo 2 caracteres',
            'ap_paterno.max' => 'El campo Apellido Paterno debe tener como máximo 50 caracteres',
            'ap_materno.min' => 'El campo Apellido Materno debe tener como mínimo 2 caracteres',
            'ap_materno.max' => 'El campo Apellido Materno debe tener como máximo 50 caracteres',
            'email.required' => 'El campo email es requerido',
            'roles.required' => 'Debe escoger una opcion de rol válida',
        ];
        $validateArray = [
            'username' => 'required|max:45|min:5|unique:users,username',
            'password' => 'required',
            'name' => 'required|max:40|min:2',
            'ap_paterno' => 'required|max:50|min:2',
            'ap_materno' => 'nullable|max:50|min:2',
            'celular'=>'nullable|min:7',
            'roles' => 'required',
        ];

        $validateEmail = [
            'email' => 'required|email:filter',
        ];

        $chMail = $request->checkIncluir;
        if($chMail == 1) $validateArray = array_merge($validateArray, $validateEmail);

        $request->validate($validateArray,$messages);


        DB::beginTransaction();
        try {
            $user = new User();
            $user->username = $request->username;
            $user->name = $request->name;
            $user->ap_paterno = $request->ap_paterno;
            $user->ap_materno = $request->ap_materno;
            $user->email = $chMail == 1 ? $request->email : null;
            $user->celular = $request->celular;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->roles;
            $user->save();

            // ENVIAR MAIL AL CREAR USUARIO
            // if($chMail == 1){
            //     $enviarmail = $user->email;
            //     $mailto = userMail(userId());
            //     if($mailto != ''){
            //         Mail::to($mailto)->cc($enviarmail)->send(new NuevoUsuario($user, $request->password));
            //     }else{
            //         Mail::to($enviarmail)->send(new NuevoUsuario($user, $request->password));
            //     }
            //     toastr()->info('Enviados correctamente.','Mails', ['positionClass' => 'toast-bottom-right']);
            // }
            $flasher->addFlash('success', 'Creado con éxito', 'Usuario');
            DB::commit();
            return  \Response::json(['success' => '1']);
        }
        catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function edit($id){
        canPassAdminJefe();
        $user = User::findOrFail(decode($id));
        $roles = Role::get();
        Session::put('item','6.');
        return view('users.edit',compact('user','roles'));
    }

    public function update(Request $request, FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $user = User::where('id',decode($id))->first();
        // Validacion por request
        $this->validateUpdateUser($request, $user);

        DB::beginTransaction();
        try {
            // PRINCIPALES
            $user->username = $request->username;
            $user->name = $request->name;
            $user->ap_paterno = $request->ap_paterno;
            $user->ap_materno = $request->ap_materno;
            $user->email = $request->email;
            $user->celular = $request->celular;
            $user->role_id = $request->roles;
            // PASSWORD
            if($request->auxpass == 1 && $request->password_first != null && $request->new_password != null ){
                $user->password = bcrypt($request->new_password);
                $flasher->addFlash('success', 'Modificado con éxito', 'Contraseña de '.userFullName($user->id));
            }
            $user->update();

            $flasher->addFlash('info', 'Modificado con éxito', 'Usuario '.userFullName($user->id));
            DB::commit();
            return  \Response::json(['success' => '1']);
        } //termina el try
        catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    /**
     * Carga la ventana de confirmacion para
     */
    public function modalCambioEstado($id){
        canPassAdminJefe();
        $users = User::findOrFail(decode($id));
        return view('users.modalCambioEstado', compact('users'));
    }

    public function cambiarestado(FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $user=User::findOrFail(decode($id));
        if($user->id != userId()){
            if ($user->active=='0') {
                $user->active='1';
                $flasher->addFlash('info', 'Activado correctamente', 'Usuario '.userFullName($user->id));
            }else {
                $user->active='0';
                $flasher->addFlash('warning', 'Desactivado correctamente', 'Usuario '.userFullName($user->id));
            }
            $user->update();
        }
        return back();
    }

    public function modalDelete($id){
        canPassAdminJefe();
        $users = User::findOrFail(decode($id));
        return view('users.modalDelete', compact('users'));
    }

    public function destroy(Request $request, FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $user = User::findOrFail(decode($id));
        $messages = [
            'userborrar.required' => 'El campo es obligatorio',
            'userborrar.in' => 'El campo no coincide con <b>'.$user->username.'</b>',
        ];
        $validateRetiro = [
            'userborrar' => 'bail|required|in:'.$user->username,
        ];
        $request->validate($validateRetiro,$messages);

        if($user->id != userId()){
            if(isset($user->avatar)){
                $ruta = 'public/general/avatar/'.$user->avatar;
                $ruta_thum = 'public/general/avatar/thumbnail/'.$user->avatar;
                if ($user->avatar!='avatar0.png' && Storage::exists($ruta))         Storage::delete($ruta);
                if ($user->avatar!='avatar0.png' && Storage::exists($ruta_thum))    Storage::delete($ruta_thum);
            }
            $user->active = 2;
            $user->update();
            $flasher->addFlash('error', 'Eliminado correctamente', 'Usuario '.userFullName($user->id));
        }
        return  \Response::json(['success' => '1']);
    }

    public function validarUsername(Request $request){
        if ($request->get('query')) {
            $query = $request->get('query');
            $username=User::where('username',$query)->first();

            if($username==null){
                $msg='
                    <span class="help-block" style="color:#5eba00">
                        <i class="fas fa-check-circle" style="color:#5eba00"></i>&nbsp;El nombre de Usuario está disponible
                    </span><br>
                    <input type="hidden" value="0" id="sw">';
            }else{
                $msg='
                <span class="help-block" style="color:#CD201F">
                    <i class="fas fa-times-circle" style="color:#CD201F"></i>&nbsp;El nombre de Usuario ya está en uso
                </span><br>
                <input type="hidden" value="1" id="sw">';
            }

            return response()->json(array('msg'=> $msg), 200);
        }
    }

    public function uploadAvatarImagen(Request $request, FlasherInterface $flasher){
        $image_parts = explode(";base64,", $request->image);
        $file = base64_decode($image_parts[1]);
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $file);
        $tmpFile = new File($tmpFilePath);
        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,true // Mark it as test, since the file isn't from real HTTP POST.
        );
        $user = User::where('id',decode($request->userid))->first();
        if(isset($user->avatar)){
            $ruta='public/general/avatar/'.$user->avatar;
            $ruta_thum='public/general/avatar/thumbnail/'.$user->avatar;
            // Si la imagen es la imagen por defecto no se la eliminara
            if ($user->avatar!='avatar0.png' && Storage::exists($ruta))         Storage::delete($ruta);
            if ($user->avatar!='avatar0.png' && Storage::exists($ruta_thum))    Storage::delete($ruta_thum);
        }
        $name = base64_encode($user->id).'_'.$this->generarCodigoImg(6).'.png';
        $file->storeAs("public/general/avatar/", $name);
        $file->storeAs("public/general/avatar/thumbnail/", $name);

        InterventionImage::make($file)->resize(250,250, function ($constraint){
            $constraint->aspectRatio();
        })->save(storage_path().'/app/public/general/avatar/thumbnail/'.$name,90);

        $user->avatar = $name;
        $user->update();

        $flasher->addFlash('info', 'Modificado con éxito', 'Avatar');
        return response()->json(['success'=>'1']);
    }

    public function uploadFirmaImagen(Request $request) {
        if (auth()->user()->active == '6') abort(403); // Validacion
        $image_parts = explode(";base64,", $request->image);
        $file = base64_decode($image_parts[1]);
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $file);
        $tmpFile = new File($tmpFilePath);
        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        $extension = $file->extension();
        $ext = strtolower($extension);
        if( !($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') ){
            return response()->json(['success' => 'X']);
        }

        $user = User::where('id', decode($request->userid))->first();
        if (isset($user->firma)) {
            if (Storage::exists('public/general/firmas/' . $user->firma . '')) {
                Storage::delete('public/general/firmas/' . $user->firma . '');
            }
        }
        $name = 'firma_' . $this->generarCodigoImg(6) . '.png';
        $file->storeAs("public/general/firmas/", $name);
        $user->firma = $name;
        $user->update();
        return response()->json(['success' => 'Crop Image Uploaded Successfully']);
    }


    public function generarCodigoImg($longitud) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern)-1;
        for($i=0;$i < $longitud;$i++) $key .= $pattern[mt_rand(0,$max)];
        return $key;
    }

    public function export(Request $request){
        $idsEnvia = explode(',', $request->idsExport );
        $usuarios = [];
        foreach ($idsEnvia as $idEnvia) {
            $e = User::find(decode($idEnvia));
            if ( isset($e) ) {
                $usuarios[] = $e;
            }
        }

        if(isset($usuarios) && count($usuarios)>0){
            if($request->tipo == 'excel'){
                libxml_use_internal_errors(true);
                return Excel::download((new UsersExport())->parametros( $usuarios),'usuarios_'.date("Y-m-d").'.xlsx');
            }elseif($request->tipo == 'pdf'){
                $users = $usuarios;
                // Cargar la vista que sera mostrada en el pdf
                $pdf = PDF::loadView("users.pdf", compact('users'));
                // Geberar el PDF
                return $pdf->setOption('margin-bottom',8)->setOption('margin-top',7)
                ->setPaper('A4', 'portrait')
                ->setOption('footer-right', 'Pagina [page] de [toPage] ')
                ->setOption('footer-left', 'Exportado el '. date('d/m/Y') .' a la(s) '.date('H:i'))
                ->stream('usuarios.pdf');
            }
            return back();
        } else {
            Session::flash('messageDelete', 'No hay datos para exportar');
            return back();
        }
    }


    public function perfil(){
        Session::put('item','6.');
        return view('users.profile');
    }

        /**
     * Actualiza el perfil del usuario conectado.
     */
    public function updateProfile(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'name.required'  => 'El campo Nombre(s) es obligatorio',
            'name.max'  => 'El campo Nombre(s) debe tener al menos 2 caracteres',
            'name.min'  => 'El campo Nombre(s) no debe tener más de 50 caracteres',
            'ap_paterno.required'  => 'El campo Apellido Paterno es obligatorio',
            'ap_paterno.min'  => 'El campo Apellido Paterno debe tener al menos 2 caracteres',
            'ap_paterno.max'  => 'El campo Apellido Paterno no debe tener más de 50 caracteres',
            'current_password.required'  => 'EL CAMPO "Contraseña Actual" ES OBLIGATORIO',
            'password_first.min'  => 'El campo Contraseña Nueva debe contener al menos 8 caracteres.',
            'password_first.required'  => 'EL CAMPO "Contraseña Nueva" ES OBLIGATORIO',
            'password_first.regex'  => 'EL CAMPO "Contraseña Nueva" NO CUMPLE CON LOS REQUERIMIENTOS',
            'password_first.required_with' => 'El campo contraseña nueva es obligatorio cuando Contraseña actual está presente.',
            'new_password.min'  => 'El campo Confirmar Contraseña Nueva debe contener al menos 8 caracteres.',
            'new_password.required'  => 'EL CAMPO "Confirmar Contraseña Nueva" ES OBLIGATORIO',
            'new_password.regex'  => 'EL CAMPO "Confirmar Contraseña Nueva" NO CUMPLE CON LOS REQUERIMIENTOS',
            'new_password.same' => 'Los campos "Contraseña nueva" y "Confirmar contraseña nueva" deben ser iguales',
            'new_password.same' => 'Los campos "Contraseña nueva" y "Confirmar contraseña nueva" deben ser iguales',
        ];

        $validateArray = [
            'name' => 'required|max:40|min:2',
            'ap_paterno' => 'required|max:50|min:2',
            'ap_materno' => 'nullable|max:50|min:2',
            // 'email' => 'required|email:filter',
            'celular' => 'max:20|min:3',
        ];

        if($request->auxpass == 1 ){
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        return $fail(__('La contraseña ingresada no coincide con la almacenada en el sistema.'));
                    }
                }],
                'password_first' => 'bail|required_with:current_password|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'new_password' => 'bail|required_with:password_first|min:8|same:password_first|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'name' => 'required|max:40|min:2',
                'ap_paterno' => 'required|max:50|min:2',
                'ap_materno' => 'nullable|max:50|min:2',
                // 'email' => 'required|email:filter',
                'celular' => 'max:20|min:3',
            ],$messages);
            $flasher->addFlash('success', 'Modificada con éxito', 'Contraseña');
        }else{
            $request->validate($validateArray,$messages);
        }

        $user = User::findOrFail(decode($id));

        // CHECK DE QUITAR AVATAR
        if ($request->checkAvatar != null) {
            $ruta='public/general/avatar/'.$user->avatar;
            if ($user->avatar != 'avatar0.png' && Storage::exists($ruta)){
                $ruta_thum='public/general/avatar/thumbnail/'.$user->avatar;
                Storage::delete($ruta);
                Storage::delete($ruta_thum);
            }
            $user->avatar = 'avatar0.png';
        }
        if($request->auxpass == 1 && $request->current_password != null && $request->password_first != null && $request->new_password != null){
            $user->password = bcrypt($request->new_password);
            if(Cookie::has('login2')){
                Cookie::queue(Cookie::forget('login2'));
            }
        }
        $user->name = $request->name;
        $user->ap_paterno = $request->ap_paterno;
        $user->ap_materno = $request->ap_materno;
        $user->email = $request->email;
        $user->celular = $request->celular;
        $user->update();
        $flasher->addFlash('info', 'Modificados con éxito', 'Datos de perfil');
        return  \Response::json(['success' => '1']);
    }



    // ==============================================================================================================================
    //                                                VALIDACIONES LARAVEL
    // ==============================================================================================================================
    public function validateUpdateUser($request, $user){

        $messages = [
            'name.required' => 'El campo Nombre(s) es obligatorio',
            'name.min' => 'El campo Nombre(s) debe tener como mínimo 2 caracteres',
            'name.max' => 'El campo Nombre(s) debe tener como máximo 40 caracteres',
            'ap_paterno.required' => 'El campo Apellido Paterno es obligatorio',
            'ap_paterno.min' => 'El campo Apellido Paterno debe tener como mínimo 2 caracteres',
            'ap_paterno.max' => 'El campo Apellido Paterno debe tener como máximo 50 caracteres',
            'ap_materno.min' => 'El campo Apellido Materno debe tener como mínimo 2 caracteres',
            'ap_materno.max' => 'El campo Apellido Materno debe tener como máximo 50 caracteres',
            'roles.required' => 'Debe escoger una opción válida',

            // MENSAJES PASSWORD
            'password_first.min'  => 'El campo Contraseña Nueva debe contener al menos 8 caracteres.',
            'password_first.required'  => 'El campo Contraseña Nueva" es obligatorio',
            'password_first.regex'  => 'El campo Contraseña Nueva" NO CUMPLE CON LOS REQUERIMIENTOS',
            'new_password.min'  => 'El campo Confirmar Contraseña Nueva debe contener al menos 8 caracteres.',
            'new_password.required'  => 'El campo Confirmar Contraseña Nueva es obligatorio',
            'new_password.regex'  => 'El campo Confirmar Contraseña Nueva NO CUMPLE CON LOS REQUERIMIENTOS',
            'new_password.same' => 'Los campos "Contraseña nueva" y "Confirmar contraseña nueva" deben coincidir.'
        ];

        $validateArray = [
            'username' => 'bail|required|max:45|min:5|regex:/^[A-Za-z0-9ñáéíóúÁÉÍÓÚÑ@#$%^&*+:;,. -]+$/|unique:users,username,'.$user->id,
            'name' => 'bail|required|max:40|min:2',
            'ap_paterno' => 'bail|required|max:50|min:2',
            'ap_materno' => 'bail|nullable|max:50|min:2',
            'email' => 'bail|nullable|email:filter',
            'celular' => 'nullable|min:3|max:20',
            'roles' => 'required',
        ];

        $validatePassword = [
            'password_first' => 'bail|required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'new_password' => 'bail|required|min:8|same:password_first|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ];
        if($request->auxpass == 1 ){
            $validateArray = array_merge($validateArray,$validatePassword);
        }
        return $request->validate($validateArray,$messages);
    }
}
