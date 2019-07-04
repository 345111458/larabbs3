<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;
use App\Handlers\ImageUploadHandler;



class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request , Topic $topic)
	{
		$topics = $topic->withOrder($request->order)->paginate(30);
		return view('topics.index', compact('topics'));
	}

    public function show(Topic $topic,Request $request)
    {
    	if (!empty($topic->slug)  && $topic->slug != $request->slug) {
    		return redirect($topic->link() , 301);
    	}





    	// $path = $request->getUri();

    	// $notifications = \DB::table('notifications')->select('id','data')->where(['notifiable_id'=>Auth::id()])->WhereNull('read_at')->make()->each(function($notifty) use ($path){

    	// 	// $noniftyData = json_decode($notifty->data);
    	// 	$notifty->read_at = now();

    	// })->get();
    	// foreach ($notifications as $k => $v) {
    	// 	$data = json_decode($v->data)
    	// 	foreach ($data as $kk => $vv) {
    	// 		$url = explode('#',$vv->topic_link);
    	// 		if ( $url[0] == $path) {
    	// 			$v->update(['read_at'=>now()]);
    	// 			break;
    	// 		}
    	// 	}
    	// }


    	// print_r($path);
    	// // $notifications = json_decode($notifications[0]->data);
    	// dd($notifications);
    	// // dd($notifications->topic_link);


        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
		$categories = Category::all();

		// dd($categories);

		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request , Topic $topic)
	{
		$topic->fill($request->all());
		$topic->user_id = Auth::id();
		$topic->save();

		return redirect($topic->link())->with('success', '帖子创建成功！');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);

        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '修改成功.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '成功删除.');
	}



	public function uploadImage(Request $request, ImageUploadHandler $uploader){

        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }



}