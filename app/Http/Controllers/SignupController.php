<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignupController extends Controller
{
    function verifyEmail(Request $request)
    {
        $accountData = DB::select('SELECT * FROM account WHERE email=?', [$request->email]);
        if (count($accountData) != 0) return 1;
    }


    function createAccount(Request $request)
    {
        $firstname = $request->firstname;
        $surname = $request->surname;
        $email = $request->email;
        $password = $request->password;
        $gender = $request->gender;

        $accountId = DB::table('account')->insertGetId(["email" => "$email", "password" => "$password"]);
        $person_id = DB::table('person')->insertGetId(["firstname" => "$firstname", "surname" => "$surname", "gender" => "$gender", "account_id" => "$accountId"]);

        setcookie("person_id", $person_id, time() + (86400 * 30), "/"); // 86400 = 1 day

        return redirect('/home');
    }
}
