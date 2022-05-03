<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class LoginController extends Controller
{
    function login(Request $request)
    {
        $accountData = Account::where('email', '=', $request->email)->get();
        if (count($accountData) == 0) return 1; // if email is incorrect

        $accountData = Account::where('email', '=', $request->email)
            ->where('password', '=', $request->password)
            ->get();
        if (count($accountData) == 0) return 2; // if password is incorrect

        $accountData = json_decode(json_encode($accountData), true);
        $record = Account::join('people', 'accounts.id', '=', 'people.account_id')
            ->where('accounts.id', '=', $accountData[0]["id"])
            ->get();

        $record = json_decode(json_encode($record), true);
        setcookie("person_id", $record[0]["id"], time() + (86400 * 30), "/"); // 86400 = 1 day
        return 3; // if successfully logged in
    }
}
