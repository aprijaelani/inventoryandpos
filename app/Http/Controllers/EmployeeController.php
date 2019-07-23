<?php

namespace App\Http\Controllers;

use App\Employee;
use Alert;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_user = Auth::user();
        if ($data_user) {
            $employees = Employee::orderBy('id','asc')->paginate(10);
            return view('master.employee.list_employee', compact('employees'));
        }else{
            return redirect('/');
        }
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.employee.create_employee');
        }else{
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Employee::create([
            'nama' => request('nama')
            ]);
        Alert::success('Tambah Sales Berhasil', 'Berhasil');
        return redirect("/employee");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.employee.edit_employee',compact('employee'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        Employee::where('id',$employee->id)->update([
            'nama' => request('nama')
            ]);
        Alert::success('Ubah Sales Berhasil', 'Berhasil');
        return redirect("/employee");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $employee = Employee::find($request->id);
        $employee->delete();
        return redirect("/employee");

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkSales (Request $request)
    {
        // echo $request;exit();
        $sales = Employee::where('nama', $request->sales)->count();

        return Response::json($sales);
    }
}
