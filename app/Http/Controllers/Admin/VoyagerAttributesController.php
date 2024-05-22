<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Redirect;

class VoyagerAttributesController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function addnewattribute()
    {
        return Voyager::view('voyager::attributes.addattribute');
    }

    public function editattribute($id=NULL){
        $dataid = $id;
    	$recordattrb = Attribute::select('*')->where('id','=',$id)->first();
        $recordattrbopts = AttributeOption::select('*')->where('attribute_id','=',$id)->get();
    	return Voyager::view('voyager::attributes.editattribute', compact('dataid','recordattrb','recordattrbopts'));
    }

    public function processattribute(Request $request){


         $validated = $request->validate([
            'attributecode' => 'required',
            'name' => 'required',
            'type'=> 'required',
            'is_price'=> 'required',
        ]);

        if(count($request['type_value'])>0){
            $tvals = $request['type_value'];
        }

        $quesTyp = trim($request['type']);

        $data = new Attribute;
        $data->attributecode = $request['attributecode'];
        $data->name = ucwords(trim($request['name']));
        $data->type = $request['type'];
        if($quesTyp=='text'){
            $data->is_price = 0;
        } else {
            $data->is_price = $request['is_price'];
        }

        if($data->save()){
            $attrid = $data->id;
            if($quesTyp=='select'){
                foreach($tvals as $tvallst){
                    $dataopt = new AttributeOption;
                    $dataopt->attribute_id = $attrid;
                    $dataopt->options = trim(ucwords($tvallst));
                    $dataopt->save();
                }
            } else{
                $dataopt = new AttributeOption;
                $dataopt->attribute_id = $attrid;
                $dataopt->options = "Yes/No";
                $dataopt->save();
            }
        }

        return redirect()
            ->route("voyager.attributes.index")
            ->with([
                    'message'    => 'Successfully Added Attribute',
                    'alert-type' => 'success',
                ]);
    }

    public function processeditattribute(Request $request){
        
        if(isset($request['type_value']) && count($request['type_value'])>0){
            $tvals = $request['type_value'];
            $toptnvals = $request['type_valueid'];
        }

        $quesTyp = trim($request['type']); 
        $pattid = $request['attributeid'];         

        $data = Attribute::findOrFail($request['attributeid']);
        $data->name = ucwords(trim($request['name']));
        $data->type = $request['type'];

        if($quesTyp=='text'){
            $data->is_price = 0;
        } else {
            $data->is_price = $request['is_price'];
        }
        
        $data->save();

        if($quesTyp=='select'){
            $chkids = [];
            foreach($tvals as $tkey => $tvallst){
                $topsid = $toptnvals[$tkey];
                $reccount = AttributeOption::where('id','=',$topsid)->where('attribute_id','=',$pattid)->count();
                if($reccount>0){
                    $dataopt = AttributeOption::findOrFail($topsid);
                    $dataopt->attribute_id = $pattid;
                    $dataopt->options = trim(ucwords($tvallst));
                    $dataopt->save();

                    $chkids[] = $dataopt->id;
                } else {
                    $dataopt = new AttributeOption;
                    $dataopt->attribute_id = $pattid;
                    $dataopt->options = trim(ucwords($tvallst));
                    $dataopt->save();

                    $chkids[] = $dataopt->id;
                }
            }

            if(count($chkids)>0){
                $recdel = AttributeOption::whereNotIn('id',$chkids)->where('attribute_id','=',$pattid)->delete();
            }
        }

        return redirect()
            ->route("voyager.attributes.index")
            ->with([
                'message'    => 'Successfully Updated Attribute',
                'alert-type' => 'success',
            ]);
        
    }

    public function deleteattribute(Request $request){
        $recdel = AttributeOption::where('attribute_id','=',$request['attrbid'])->delete();
        $recparentdel = Attribute::where('id','=',$request['attrbid'])->delete();

        return redirect()
        ->route("voyager.attributes.index")
        ->with([
            'message'    => 'Successfully Deleted Attribute',
            'alert-type' => 'success',
        ]);
    }
}
