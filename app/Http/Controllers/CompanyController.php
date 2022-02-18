<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.company_list');
    }
    public function getlist(Request $request)
    {
        try{
            $json = array();
            $dir = $request->order[0]['dir'];
            $sortBy = $request->columns[$request->order[0]['column']]['name'];
            $sortOrder = ($dir) ? $dir : config('pager.sortOrder');
            $recordsPerPage = ($request->has('length')) ? $request->get('length') : config('pager.recordsPerPage');
            $skip = $request->input('start');
            $take = $recordsPerPage;
            $sql =  Company::withoutTrashed();
            if(isset($request->search['value'])){
                $search = $request->search['value'];
                $sql->where(function ($query) use ($request,$search) {
                        $query->orWhere('name', 'like', '%' . $search. '%')
                        ->orWhere('website', 'like', '%' . $search. '%');
                    });  
            }
            $recordsFiltered= $sql->count();                
            $data = $sql->limit($request->length)
                    ->skip($request->start)
                    ->orderBy($sortBy, $sortOrder)
                    ->skip($skip)
                    ->take($take)->get();
                foreach($data as $k=>$v){
                    $type='';
                    if($v->type=='0') $type='PUBLIC';
                    if($v->type=='1') $type='private';
                    if($v->type=='2') $type='Registerd';

                    $data[$k]->type=$type;
                }
            $recordsTotal = $data->count();
            $json['data'] = $data;
            $json['draw'] = $request->draw;
            $json['recordsTotal'] = $recordsTotal;
            $json['recordsFiltered'] = $recordsFiltered;
            return json_encode($json);
        } catch (\Exception $e) {
          
              $flashArr = array(
                'msg' => 'Company listing failed '.$e->getMessage() 
            );
            $request->session()->flash('err_message', $flashArr);
            return redirect()->route('company');
        }
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
                return view('company.company_edit',compact('company'));
            }
            $data=Company::withoutTrashed()->find($id);
            if(!empty($data)){ 
                return view('company.company_edit',compact('data','company'));
            }else{
                $flashArr = array(
                    'msg' => 'Company details not match'
                );
                $request->session()->flash('err_message',$flashArr);
                return redirect()->route('company');
            }
        } catch(Exception $e){
            $flashArr = array(
               'msg' => 'Company creations failed '.$e->getMessage()
            );
            $request->Session()->flash('err_message',$flashArr);
            return redirect()->route('company');
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
                'name.required'=>'Name  required',
                'type.required'=>'Type required',
                'website.required'=>'Website required',
                'description.required'=>'description required',
                'unique'=>'This company is already registerd with us,Please try another',
            );
            $validation=app('validator')->make($request->all(),[
                'name'        => 'required|unique:tbl_company,name', 
                'type'        => 'required', 
                'website'         => 'required', 
                'description'     => 'required', 
                
            ],$rules);
            if(!$validation->passes())
            {
                return redirect()->back()->withInput()->withErrors($validation);
            }


            $obj=new Company;
            $obj->name =$request->name;
            $obj->type =$request->type;
            $obj->website =$request->website;
            $obj->description =$request->description;       
            $res=$obj->save();
            if($res){               
                $flashArr = array(
                    'msg' => 'Company created successfully' 
                );
                $request->Session()->flash('succ_message',$flashArr);

            }else{
                $flashArr = array(
                    'msg' => 'Company created failed'  
                );
                $request->Session()->flash('err_message',$flashArr);
            }
        } catch (Exception $e) {
             $flashArr = array(
                'msg' => 'Company created failed '.$e->getMessage()
            );
            $request->Session()->flash('err_message',$flashArr);
        }
        return redirect()->route('company');
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
                'name.required'=>'Name  required',
                'type.required'=>'Type required',
                'website.required'=>'Website required',
                'description.required'=>'description required',

            );
            $validation=app('validator')->make($request->all(),[
                'name'=>'required',
                'type'        => 'required', 
                'website'         => 'required', 
                'description'     => 'required', 
                
            ],$rules);
            if(!$validation->passes())
            {
                return redirect()->back()->withInput()->withErrors($validation);
            }
            $id=$request->id;
            if(empty($id)){
                return redirect()->route('company');
            }
            $obj=Company::find($id);
            if(empty($obj)){
                $flashArr = array(
                    'msg' => 'Company details dose not exist'  
                );
                $request->Session()->flash('succ_message',$flashArr);
                return redirect()->route('company.edit',['id'=>$id]);
            }        
            $obj->name =$request->name;
            $obj->type =$request->type;
            $obj->website =$request->website;
            $obj->description =$request->description;     
            $res=$obj->save();
            if($res){               
                $flashArr = array(
                    'msg' => 'Company updated successfully' 
                );
                $request->Session()->flash('succ_message',$flashArr);

            }else{
                $flashArr = array(
                    'msg' => 'Company updated failed'  
                );
                $request->Session()->flash('err_message',$flashArr);
            }
        } catch (Exception $e) {
            $flashArr = array(
                'msg' => 'Company updated failed '.$e->getMessage()

            );
            $request->Session()->flash('err_message',$flashArr);
        }
        return redirect()->route('company.edit',['id'=>$id]);
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
            $getData=Company::withoutTrashed()->find($id); 
            if(empty($getData)){
                $flashArr = array(
                    'msg' => 'Company details dose not exist',
                    'status' => 'fail'
                );
                return $flashArr;
            }
            $res=$getData->delete();
            if($res){
                $flashArr = array(
                    'msg' => 'Company delete successfully',
                    'status' => 'success'
                );
            }else{
                $flashArr = array(
                    'msg' => 'Company delete failed',
                    'status' => 'fail'
                );
            }
        }catch(Exception $e){
            $flashArr = array(
                'msg' =>'Company delete failed'.$e->getMessage() 
            );

        }
        return $flashArr;
    }
}
