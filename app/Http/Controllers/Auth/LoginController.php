<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;



class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'backend/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
            UserRepositoryInterface $userRepo,
            RoleRepositoryInterface $roleRepo
    ) {
        $this->middleware('guest')->except('logout');
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {
//        $pathAndroid = public_path('android');
//        $files = scandir($pathAndroid, SCANDIR_SORT_DESCENDING);
//        $newest_file = $files[0];
//        $data['url_apk'] = url('android/' . $newest_file);

//        dd('sadsada');
        return view('backend.auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username() {
        return 'email';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user) {
        //

        /**
         * Set User & Menu
         * to Session after Login
         */
        $user = $this->userRepo->find($user->id);
        $role = $user->role;
        $menus = $this->roleRepo->find($role->id)->menuses()->orderBy('order', "ASC")->get();
        $data = (object) [
                    'user' => $user,
                    'role' => $role,
                    'menus' => $menus,
        ];

        $request->session()->put('data', $data);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }

}
