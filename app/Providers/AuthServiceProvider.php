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
            $data = DB::table('users')->where('id', $cid)->first();
            if( empty($data) ) {
                $sso = new SSOController();
                $sso = $sso->ProfileData($cid);
                $user_name = $sso['user_name'];
                $role = "viwer";

                $role_parcel = 0;
                $role_report = 0;
                $role_setting = 0;
            }else{
                $user_name = $data->name;
                $role = "admin";

                $role_parcel = $data->role_parcel;
                $role_report = $data->role_report;
                $role_setting = $data->role_setting;
            }

            session()->put('cid', $cid);
            session()->put('user_name', $user_name);
            session()->put('role', $role);
            session()->put('role_parcel', $role_parcel);
            session()->put('role_report', $role_report);
            session()->put('role_setting', $role_setting);

            return $role;
        }else{
            return Session::get('role');
        }
    }

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('parcel', function ($user) {
            $val = $this->putSession($user);
            if ( Session::get('role_parcel') == 1) {
                return true;
            }else{
                return false;
            }
        });

        Gate::define('report', function ($user) {
            $val = $this->putSession($user);
            if ( Session::get('role_report') == 1) {
                return true;
            }else{
                return false;
            }
        });

        Gate::define('setting', function ($user) {
            $val = $this->putSession($user);
            if ( Session::get('role_setting') == 1) {
                return true;
            }else{
                return false;
            }
        });
    }
}
