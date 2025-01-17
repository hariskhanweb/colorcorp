<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Mail\UserOrderStatusMail;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use App\Models\InstallationCharges;
use App\Models\InstallationChargeItems;
use App\Models\OrderItems;
use App\Models\OrderComments;
use App\Models\Orders;
use App\Models\User;
use Helper;
use Mail;
use DB;

class OrdersController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function index(Request $request)
    {
        $vendor_id  = auth()->user()->id;         
        $role_id    = auth()->user()->role_id;  

        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();

        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
                return [$row['field'] => $row->getTranslatedAttribute('display_name')];
            });
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            $query = $model::select($dataType->name.'.*');

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query->{$dataType->scope}();
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';

                $searchField = $dataType->name.'.'.$search->key;
                if ($row = $this->findSearchableRelationshipRow($dataType->rows->where('type', 'relationship'), $search->key)) {
                    $query->whereIn(
                        $searchField,
                        $row->details->model::where($row->details->label, $search_filter, $search_value)->pluck('id')->toArray()
                    );
                } else {
                    if ($dataType->browseRows->pluck('field')->contains($search->key)) {
                        $query->where($searchField, $search_filter, $search_value);
                    }
                }
            }

            $row = $dataType->rows->where('field', $orderBy)->firstWhere('type', 'relationship');
            if ($orderBy && (in_array($orderBy, $dataType->fields()) || !empty($row))) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                if (!empty($row)) {
                    $query->select([
                        $dataType->name.'.*',
                        'joined.'.$row->details->label.' as '.$orderBy,
                    ])->leftJoin(
                        $row->details->table.' as joined',
                        $dataType->name.'.'.$row->details->column,
                        'joined.'.$row->details->key
                    );
                }

                if($role_id==2){
                    $dataTypeContent = call_user_func([
                                    $query->select('orders.*')
                                        ->leftjoin('users','users.id','=','orders.user_id')
                                        ->where('users.vendor_id','=',$vendor_id)
                                        ->orderBy($orderBy, $querySortOrder),
                                    $getter,
                                ]);
                }else{                    
                    $dataTypeContent = call_user_func([
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]);
                }
            } elseif ($model->timestamps) {
                if($role_id==2){
                    $dataTypeContent = call_user_func([$query->select('orders.*')
                                    ->leftjoin('users','users.id','=','orders.user_id')
                                    ->where('users.vendor_id','=',$vendor_id)
                                    ->latest($model::CREATED_AT), $getter]);
                }else{
                    $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
                }
            } else {
                if($role_id==2){
                    $dataTypeContent = call_user_func([$query->select('orders.*')
                        ->leftjoin('users','users.id','=','orders.user_id')
                        ->where('users.vendor_id','=',$vendor_id)
                        ->orderBy($model->getKeyName(), 'DESC'), $getter]);
                }else{
                    $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
                }
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::user()->can('delete', app($dataType->model_name))) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, $sortOrder ?? 'desc']];
        }

        // Define list of columns that can be sorted server side
        $sortableColumns = $this->getSortableColumns($dataType->browseRows);

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortableColumns',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn',
            'vendors'
        ));
    }

    public function update(Request $request, $id)
    {       
        $current_user = Auth::user(); 
        $order = Orders::find($id);



        if($request->order_status_comment != ''){
            $order_comment = new OrderComments; 
            $order_comment->order_id      = $order->id; 
            $order_comment->commentor_id  = $current_user->id;  
            $order_comment->user_id       = $order->user_id;  
            $order_comment->note          = $request->order_status_comment;   
            $order_comment->save();
        }


        if($order->status != $request->status){

            //dd($request->order_status_comment); exit();

            $order = Orders::find($id); 
            $order->status      = $request->status;  
           // $order->shipping      = $request->shipping;       
            $order->save();

            

            $user = User::select('name','email')->where('id', $order->user_id)->first();

            if($request->status == 1){
                $status = 'Pending';
            }else if($request->status == 2){ 
                $status = 'Completed';                
            }else{
                $status = 'Trash';
            }
            $name  = $user->name;            
            $email = $user->email; 
           // $email = "shahinafwork@gmail.com";                      
            $details = [
                'username'  => $name,
                'status'    => $status,
                'note'      => $request->order_status_comment,
                'data1'      => $order
            ];
            //Mail::to($email)->send(new UserOrderStatusMail($details));
            $subject='Order Status';
            $to=$email;
            $toname=$name;
            $mailtype='order-status';
            
            Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);
        }
        $redirect = redirect()->route("voyager.orders.index");
        return $redirect->with([
                'message'    => 'Sucessfully Updated Order',
                'alert-type' => 'success',
        ]);
    }

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);



        $orderItems = OrderItems::where('order_id',$id)->get();
        $order      = Orders::find($id);
        $userData   = User::select('name','email','vendor_id')->where('id', $order->user_id)->first();
        $vendorData = User::select('name')->where('id', $userData->vendor_id)->first();
        $ICdata     = InstallationCharges::where("order_id", $id)->first();

        $OrderComments = OrderComments::where('order_id',$id)->where('user_id', $order->user_id)->orderBy('updated_at', 'desc')->first();

        // dd($OrderComments->note);

        $ICIdata    = [];

        foreach ($dataTypeContent->orderItems as $key => $value) {
            $ICIdata    = InstallationChargeItems::where("order_item_id", $value->id)->first();
            if(!empty($ICIdata)){
                if($value->installation=='yes' && $ICIdata->order_item_id==$value->id){
                    $dataTypeContent->orderItems[$key]['charges'] = $ICIdata->charges;
                }else{
                    $dataTypeContent->orderItems[$key]['charges'] = 0;
                }                
            }else{
                $dataTypeContent->orderItems[$key]['charges'] = 0;
            }  
        }

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted', 'orderItems', 'userData', 'vendorData', 'ICdata','OrderComments'));
    }
}
