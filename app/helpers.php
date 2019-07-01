<?php

use App\Models\Category;


function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

//  选择返回  active 
function category_nav_active($category_id){

	return active_class(if_route('categories.show') && if_route_param('category',$category_id));
}

// 分类 菜单按钮
function category_menu(){

	return Category::all();
}