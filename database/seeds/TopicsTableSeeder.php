<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;


class TopicsTableSeeder extends Seeder
{
    public function run()
    {
    	// 所有用户 ID 数组，如：[1,2,3,4]
    	$user_id = User::all()->pluck('id')->toArray();

    	// 所有分类 ID 数组，如：[1,2,3,4]
    	$category_id = Category::all()->pluck('id')->toArray();

    	// 获取 Faker 实例
    	$faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)->times(1150)->make()->each(function ($topic) use ($user_id,$category_id,$faker) {
            
            // 从用户 ID 数组中随机取出一个并赋值
        	$topic->user_id = $faker->randomElement($user_id);
        	// 话题分类，同上
        	$topic->category_id = $faker->randomElement($category_id);
        });

        // 将数据集合转换为数组，并插入到数据库中
        Topic::insert($topics->toArray());
    }

}

