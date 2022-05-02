<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $accountData = DB::select('SELECT * FROM account WHERE email=?', [$email]);
        if (count($accountData) == 0) return 1; // if email is incorrect

        $accountData = DB::select('SELECT * FROM account WHERE email=? AND password=?', [$email, $password]);
        if (count($accountData) == 0) return 2; // if password is incorrect

        $accountData = json_decode(json_encode($accountData), true);
        $record = DB::select('SELECT * FROM account,person WHERE account.id=person.account_id AND account.id=?', [$accountData[0]["id"]]);
        $record = json_decode(json_encode($record), true);
        setcookie("person_id", $record[0]["id"], time() + (86400 * 30), "/"); // 86400 = 1 day
        return 3; // if successfully logged in
    }
}
