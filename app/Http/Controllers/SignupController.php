<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Person;

class SignupController extends Controller
{
    function verifyEmail(Request $request)
    {
        $accountData = Account::where('email', '=', $request->email)->get();
        if (count($accountData) != 0) return 1;
    }


    function createAccount(Request $request)
    {
        $account = new Account;
        $account->email = $request->email;
        $account->password = $request->password;
        $account->save();

        $person = new Person;
        $person->firstname = $request->firstname;
        $person->surname = $request->surname;
        $person->gender = $request->gender;
        $person->account_id = $account->id;
        $person->save();

        setcookie("person_id", $person->id, time() + (86400 * 30), "/"); // 86400 = 1 day

        return redirect('/home');
    }
}
