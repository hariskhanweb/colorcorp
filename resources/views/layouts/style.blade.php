@php
$authdata=Auth::user()->vendor_id;
if($authdata){
    $color_data=Helper::getShopData($authdata);
}else{
    $color_data=Helper::getShopData(Auth::user()->id);
}

@endphp

<style type="text/css">
:root {
  --primary: {{$color_data->shop_primary_color}};
  --secondary: {{$color_data->shop_secondary_color}};
  --third: {{$color_data->shop_third_color}};
  --forth: {{$color_data->shop_third_color}};
  --fifth: {{$color_data->shop_fifth_color}};
  --title: {{$color_data->shop_heading_color}};
  --body: {{$color_data->shop_body_color}};
  --white: #ffffff;
  --gray-l: #F6F6F6;
}

.active-green-btn{
  color: {{ $color_data->active_menu_text_color }};
  background-color: {{ $color_data->active_menu_bg_color }}!important;
  border: 1px solid {{ $color_data->active_menu_bg_color }};
}
.active-green-btn:hover{
  background-color: {{ $color_data->menu_bg_color }}!important;
  color:{{ $color_data->menu_text_color }};
  border: 1px solid {{ $color_data->menu_text_color }};
}
.active-green-btn::before {
  background-color: {{ $color_data->menu_bg_color }}!important;
  color:{{ $color_data->menu_text_color }}!important;
  border: 1px solid {{ $color_data->menu_bg_color }};
}
.active-green-btn:hover span{
  color: {{ $color_data->menu_text_color }}!important;
}
.common-btn {
  background-color: {{ $color_data->menu_bg_color }};
  color:{{ $color_data->menu_text_color }};
  border: 1px solid {{ $color_data->menu_text_color }};
}
.common-btn:hover {
  background-color: {{ $color_data->active_menu_bg_color }}!important;
  border: 1px solid {{ $color_data->active_menu_bg_color }};
}
.common-btn:before {
  background-color: {{ $color_data->active_menu_bg_color }}!important;
  border: 1px solid {{ $color_data->active_menu_bg_color }};
}
.common-btn:hover span{
  color: {{ $color_data->active_menu_text_color }}!important;
}
</style>