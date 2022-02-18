<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employe;
use App\Company;

class EmployeController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employe.employe_list');
    }
    public function getlist(Request $request)
    {
        // try{
            $json = array();
            $dir = $request->order[0]['dir'];
            $sortBy = $request->columns[$request->order[0]['column']]['name'];
            $sortOrder = ($dir) ? $dir : config('pager.sortOrder');
            $recordsPerPage = ($request->has('length')) ? $request->get('length') : config('pager.recordsPerPage');
            $skip = $request->input('start');
            $take = $recordsPerPage;
            $sql =  Employe::withoutTrashed();
            if(isset($request->search['value'])){
                $search = $request->search['value'];
                $sql->where(function ($query) use ($search) {
                    $query->orWhere('first_name', 'like', '%' . $search. '%')
                    ->orWhere('last_name', 'like', '%' . $search. '%');
                });  
                $sql->orwhereHas('get_company',function($query )use($search) {
                    $query->where(function($q) use($search){
                        $q->orWhere('name', 'like', '%' . $search. '%');
                    });
                });
            }
            $recordsFiltered= $sql->count();                
            $data = $sql->limit($request->length)
                    ->skip($request->start)
                    ->orderBy($sortBy, $sortOrder)
                    ->skip($skip)
                    ->take($take)->get();
            foreach($data as $k=>$v){
                $company_name='';
                if(!empty($v->get_company)) $company_name=$v->get_company->name;
               
                $data[$k]->company_name=$company_name;
            }
            $recordsTotal = $data->count();
            $json['data'] = $data;
            $json['draw'] = $request->draw;
            $json['recordsTotal'] = $recordsTotal;
            $json['recordsFiltered'] = $recordsFiltered;
            return json_encode($json);
        // } catch (\Exception $e) {
          
        //       $flashArr = array(
        //         'msg' => 'Employe listing failed '.$e->getMessage() 
        //     );
        //     $request->session()->flash('err_message', $flashArr);
        //     return redirect()->route('employe');
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$id='')
    {
        $company=Company::withoutTrashed()->get();
        try{
            if(empty($id)){
                return view('employe.employe_edit',compact('company'));
            }
            $data=Employe::where('status','!=','2')->find($id);
            if(!empty($data)){ 
                return view('employe.employe_edit',compact('data','company'));
            }else{
                $flashArr = array(
                    'msg' => 'Employe details not match'
                );
                $request->session()->flash('err_message',$flashArr);
                return redirect()->route('employe');
            }
        } catch(Exception $e){
            $flashArr = array(
               'msg' => 'Employe creations failed '.$e->getMessage()
            );
            $request->Session()->flash('err_message',$flashArr);
            return redirect()->route('employe');
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
        try {       
            $rules = array(
                'company_id.required'=>'Company  required',
                'first_name.required'=>'Firstname required',
                'last_name.required'=>'Lastname required',
                'email_address.required'=>'Email required',
                'position.required'=>'Position required',
                'city.required'=>'City required',
                'country.required'=>'Country required',
                'status.required'=>'Status required',
                'unique'=>'This company is already registerd with us,Please try another',

            );
            $validation=app('validator')->make($request->all(),[
                'company_id'        => 'required', 
                'first_name'        => 'required', 
                'last_name'         => 'required', 
                'email_address'     => 'required|unique:tbl_employe,email_address', 
                'position'          => 'required', 
                'city'              => 'required', 
                'country'           => 'required', 
                'status'            => 'required', 
            ],$rules);
            if(!$validation->passes())
            {
                return redirect()->back()->withInput()->withErrors($validation);
            }


            $obj=new Employe;
            $obj->company_id =$request->company_id;
            $obj->first_name =$request->first_name;
            $obj->last_name =$request->last_name;
            $obj->email_address =$request->email_address;
            $obj->position =$request->position;
            $obj->city =$request->city;
            $obj->country =$request->country;
            $obj->status =$request->status;         
            $res=$obj->save();
            if($res){               
                $flashArr = array(
                    'msg' => 'Employe created successfully' 
                );
                $request->Session()->flash('succ_message',$flashArr);

            }else{
                $flashArr = array(
                    'msg' => 'Employe created failed'  
                );
                $request->Session()->flash('err_message',$flashArr);
            }
        } catch (Exception $e) {
             $flashArr = array(
                'msg' => 'Employe created failed '.$e->getMessage()
            );
            $request->Session()->flash('err_message',$flashArr);
        }
        return redirect()->route('employe');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {       
            $rules = array(
                'company_id.required'=>'Company  required',
                'first_name.required'=>'Firstname required',
                'last_name.required'=>'Lastname required',
                'email_address.required'=>'Email required',
                'position.required'=>'Position required',
                'city.required'=>'City required',
                'country.required'=>'Country required',
                'status.required'=>'Status required',
            );
            $validation=app('validator')->make($request->all(),[
                'company_id'        => 'required', 
                'first_name'        => 'required', 
                'last_name'         => 'required', 
                'email_address'     => 'required', 
                'position'          => 'required', 
                'city'              => 'required', 
                'country'           => 'required', 
                'status'            => 'required', 
            ],$rules);
            if(!$validation->passes())
            {
                return redirect()->back()->withInput()->withErrors($validation);
            }
            $id=$request->id;
            if(empty($id)){
                return redirect()->route('employe');
            }
            $obj=Employe::find($id);
            if(empty($obj)){
                $flashArr = array(
                    'msg' => 'Employe details dose not exist'  
                );
                $request->Session()->flash('succ_message',$flashArr);
                return redirect()->route('employe.edit',['id'=>$id]);
            }        
            $obj->company_id =$request->company_id;
            $obj->first_name =$request->first_name;
            $obj->last_name =$request->last_name;
            $obj->email_address =$request->email_address;
            $obj->position =$request->position;
            $obj->city =$request->city;
            $obj->country =$request->country;
            $obj->status =$request->status;               
            $res=$obj->save();
            if($res){               
                $flashArr = array(
                    'msg' => 'Employe updated successfully' 
                );
                $request->Session()->flash('succ_message',$flashArr);

            }else{
                $flashArr = array(
                    'msg' => 'Employe updated failed'  
                );
                $request->Session()->flash('err_message',$flashArr);
            }
        } catch (Exception $e) {
            $flashArr = array(
                'msg' => 'Employe updated failed '.$e->getMessage()

            );
            $request->Session()->flash('err_message',$flashArr);
        }
        return redirect()->route('employe.edit',['id'=>$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id='')
    {
        try{
            $id= $request->id;
            $getData=Employe::find($id); 
            if(empty($getData)){
                $flashArr = array(
                    'msg' => 'Employe details dose not exist',
                    'status' => 'fail'
                );
                return $flashArr;
            }
            $res=$getData->delete();
            if($res){
                $flashArr = array(
                    'msg' => 'Employe delete successfully',
                    'status' => 'success'
                );
            }else{
                $flashArr = array(
                    'msg' => 'Employe delete failed',
                    'status' => 'fail'
                );
            }
        }catch(Exception $e){
            $flashArr = array(
                'msg' =>'Employe delete failed'.$e->getMessage() 
            );

        }
        return $flashArr;
    }

   
}
