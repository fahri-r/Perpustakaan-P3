<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index(){
        $token = session()->get('token');
        $response = Http::withToken($token)->get('https://apiperpustakaan.herokuapp.com/api/v1/employees');
        //return $response->json();
        return view('Employees.index',['response'=>$response['data']]);
        // return view('Categories.index',[
        //     'response'=>json_decode($response['data'])
        // ]);
    }

    public function getById($id){
        $token = session()->get('token');
        $employees = Http::withToken($token)->get('https://apiperpustakaan.herokuapp.com/api/v1/employees/'.$id);
        return view('Employees.detail',['employees' => $employees['data']]);
        //return $employees->json();
    }

    public function createEmployees(Request $request){
        $token = session()->get('token');

        $name = $request->name;
        $address = $request->address;
        $phone = $request->phone;
        if($request->image){
            $file_name = time().'.'.$request->image;
            $request->image->move(public_path('images'),$file_name);
            $path = "public/images/$file_name";
            $request->image = $path;
        }
        $image = $request->image;
        $email = $request->email;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;

        
        $response = Http::withToken($token)->post('https://apiperpustakaan.herokuapp.com/api/v1/employees/',[
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'image' => $image,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ]);
        // Alert::success('Success Title', 'Success Message');
        return redirect()->back()->with('warning','Please check the email to confirm your account');
        //return $response->json();
    }

    public function updateEmployees($id){
        $token = session()->get('token');
        $employees = Http::withToken($token)->get('https://apiperpustakaan.herokuapp.com/api/v1/employees/'.$id);
        //return $response->json();
        return view('Employees.update',['employees' => $employees['data']]);
    }

    public function update(Request $request,$id){
        
        $token = session()->get('token');
        $name = $request->name;
        $address = $request->address;
        $phone = $request->phone;
        $image = $request->image;
        $email = $request->email;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;

        $response = Http::withToken($token)->put('https://apiperpustakaan.herokuapp.com/api/v1/employees/'.$id,[
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'image' => $image,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ]);
        //return $response->json();
        return redirect('/employees')->with('success','Data successfully updated');
    }

    public function delete($id){
        $token = session()->get('token');
        $response = Http::withToken($token)->delete('https://apiperpustakaan.herokuapp.com/api/v1/employees/'.$id);
        return redirect()->back()->with('error','Data deleted successfully');;
        // return $response->json();
    }
}