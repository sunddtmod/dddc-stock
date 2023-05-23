<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\API\SSOController;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */

    private function putSession($user) {
        if (empty(Session::get('role'))) {
            $cid = trim($user->preferred_username);
            $data = DB::table('users')->where('cid', $cid)->first();
            if( empty($data) ) {
                $sso = new SSOController();
                $sso = $sso->ProfileData($cid);
                $fname = $sso['fname'];
                $user_name = $sso['user_name'];
                $dep_id = $sso['dep_id'];
                $email = $sso['email'];
                $role = "viwer";
            }else{
                $fname = $data->fname;
                $user_name = $data->name;
                $dep_id = $data->dep_id;
                $email = $data->email;
                $role = $data->role;
            }

            session()->put('cid', $cid);
            session()->put('fname', $fname);
            session()->put('user_name', $user_name);
            session()->put('dep_id', $dep_id);
            session()->put('email', $email);
            session()->put('role', $role);

            return $role;
        }else{
            return Session::get('role');
        }
    }

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            $val = $this->putSession($user);
            if ($val === 'admin') {
                return true;
            }else{
                return false;
            }
        });

        Gate::define('examiner', function ($user) {
            $val = $this->putSession($user);
            if ($val === 'examiner') {
                return true;
            }else{
                return false;
            }
        });

        Gate::define('user', function ($user) {
            $val = $this->putSession($user);
            if ($val === 'user') {
                return true;
            }else{
                return false;
            }
        });

        Gate::define('viwer', function ($user) {
            $val = $this->putSession($user);
            if ($val === 'viwer') {
                return true;
            }else{
                return false;
            }
        });

    }
}
