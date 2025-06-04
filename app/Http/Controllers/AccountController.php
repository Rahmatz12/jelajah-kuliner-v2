<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\PKL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    //
    public static function index()
    {
        return view('account-list', [
            'account' => Account::all()
        ]);
    }
    //login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        // dd(Auth::attempt($credentials));
        if (Auth::attempt($credentials)) {
            dd('Login successful!'); // Debugging line, remove in production
            // Authentication was successful
            $account = Auth::user();
            if ($account->status != 'alert') {
                session(['account' => $account]);
                return redirect('/dashboard');
            } else {
                // session(['account' => $account]);
                return redirect('/login')->with('alert', 'Anda Di Ban');
            }

            // Redirect to the intended URL after successful login
        }

        // Authentication failed
        return redirect('/login')->with('alert', 'email dan password salah!'); // Redirect back to the login page if authentication fails
    }

    public function loginAccount(Request $request)
    {
        if (session()->has('account')) {
            session()->pull('account');
        }
        return redirect('/');
    }

    public function logoutAccount(Request $request)
    {
        if (session()->has('account')) {
            session()->flush();
            // Remove the 'account' key from the session
        }
        return redirect('/'); // Redirect to the homepage or any other desired location
    }


    public static function showDetail(Account $account)
    {
        return view('profile', [
            'account' => $account
        ]);
    }

    //create
    public function create()
    {
        return view('register');
    }


    //save
    public function store(Request $request)
    {


        if ($request->password == $request->passwordkonf) {

            $valdata = $request->validate([
                'nama' => 'required',
                'email' => 'required',
                'nohp' => 'required',
                'password' => 'required',
                'status' => 'required',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = $valdata['nama'] . "." . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('account', $filename, 'public');

                $valdata['foto'] = $filePath;
            } else {
                $valdata['foto'] = null;
            }

            $cekEmail = Account::firstWhere('email', $valdata['email']);
            if ($cekEmail == null) {
                $valdata['password'] = Hash::make($valdata['password']);
                // Account::create($valdata);
                DB::insert('INSERT INTO accounts (nama, email, nohp, password, status, foto) VALUES (?, ?, ?, ?, ?, ?)', [
                    $valdata['nama'],
                    $valdata['email'],
                    $valdata['nohp'],
                    $valdata['password'],
                    $valdata['status'],
                    $valdata['foto'],
                ]);
            } else {
                return redirect()->back()->with('alert', 'Email ini sudah pernah digunakan');
            }

            if ($valdata['status'] == 'PKL') {
                // dd($request);

                $valdata = $request->validate([
                    'namaPKL' => 'required',
                    'desc' => 'required',
                    'picture' => 'nullable',
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric'
                ]);
                // dd($valdata);


                if ($request->hasFile('picture')) {
                    $file = $request->file('picture');
                    $filename = $valdata['namaPKL'] . "." . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('pkl', $filename, 'public');

                    $valdata['picture'] = $filePath;
                } else {
                    $valdata['picture'] = null;
                }
                // dd($valdata);
                $pkl = new PKL();
                $pkl->namaPKL = $valdata['namaPKL'];
                $pkl->desc = $valdata['desc'];
                $pkl->picture = $valdata['picture'];
                $pkl->latitude = $valdata['latitude'];
                $pkl->longitude = $valdata['longitude'];
                $pkl->idAccount = Account::where('email', $request['email'])->first()->id;
                // dd($pkl);
                $berhasil = $pkl->save();
                // dd($berhasil);
                if ($berhasil) {
                    return redirect('/dashboard');
                } else {
                    return redirect('/account/create')->with('error', 'Gagal menyimpan data PKL.');
                }
            }

            return redirect('/login');
        } else {
            return redirect()->back()->with('alert', 'Password berbeda');
        }
    }

    //edit
    public function editProfile($id)
    {
        $account = Account::find($id)->first();
        return view('edit', ['account' => $account]);
    }

    //update
    public function update(Request $request, Account $account)
    {
        // dd($request);
        $valdata = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'nohp' => 'required'
        ]);
        $account->update($valdata);
        return redirect('profile');
    }

    //delete
    public function destroy(Account $account)
    {
        Account::destroy($account->id);
        return redirect('account-list');
    }
}
