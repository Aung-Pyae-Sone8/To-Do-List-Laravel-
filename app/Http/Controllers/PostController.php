<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Post;
use Illuminate\Http\Request;

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // customer create page
    public function create()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(3); // all == get
        // $posts = Post::where('id','<','6')->where('address','=','pyin oo lwin')->get();
        // $posts = Post::pluck('title');
        // $posts = Post::select('title','price')->get();
        // $posts = Post::where('id','<',11)->get()->random();
        // $posts = Post::where('id','<',30)->where('address','=','mandalay')->get();
        // $posts = Post::orderBy('price','asc')->get();
        // $posts = Post::select('id','address','price')->whereBetween('price',[5000,25000])->orderBy('price','asc')->dd();
        // $posts = DB::table('posts')->select('id','address','price')->whereBetween('price',[5000,25000])->orderBy('price','asc')->dd();
        // $posts = Post::where('address', 'mandalay')->orderBy('price', 'asc')->value('title');
        // $posts = Post::select('title', 'price')->where('address', 'mandalay')->orderBy('price', 'asc')->get();
        // $posts = Post::find(3); (or) Post::where('id',3)->first();
        // $posts = Post::count();
        // $posts = Post::max('price');
        // $posts = Post::min('price');
        // $posts = Post::avg('price');
        // $posts = Post::where('address','mandalay')->exists();
        // $posts = Post::where('address','london')->doesntExist();
        // $posts = Post::select('id','title as post_title','title as b_title')->get();
        // $posts = Post::select('address',DB::raw('count(address) as address_count'),DB::raw('sum(price) as total_price'))->groupBy('address')->get();

        // map each through (map == each)
        // $posts = Post::get()->map(function ($p) {
        //     $p->title = strtoupper($p->title);
        //     $p->description = strtoupper($p->description);
        //     $p->price = $p->price * 2;
        //     return $p;
        // });
        // $posts = Post::paginate(5)->through(function ($p) {
        //     $p->title = strtoupper($p->title);
        //     $p->description = strtoupper($p->description);
        //     $p->price = $p->price * 2;
        //     return $p;
        // });
        // map each => paginate => data only
        // through => paginate => pagination + data (not use whit get())

        // dd($posts);
        // dd($posts->toArray());

        // data searching

        // http://localhostd:8000/customer/createPage?key=code lab
        // $searchKey = $_REQUEST['key'];
        // $posts = Post::where('title','like','%'.$searchKey.'%')->get();
        // $posts = Post::when(request('key'),function($p){
        //     $searchKey = $_REQUEST['key'];
        //     $p->where('title','like','%'.$searchKey.'%');
        // })->get();

        // dd($posts->toArray());

        $posts = Post::when(request('searchKey'), function ($query) {
            $key = request('searchKey');
            $query->orWhere('title', 'like', '%' . $key . '%')
                ->orWhere('description', 'like', '%' . $key . '%');
        })->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('create', compact('posts'));
    }

    // post create
    public function postCreate(Request $request)
    {
        // dd($request->hasFile('postImage') ? 'yes' : 'no');
        // dd($request->file('postImage')); (or) dd($request->postImage);
        // dd($request->file('postImage')->path()); // location of image
        // dd($request->file('postImage')->getClientOriginalName()); // get image name and type

        $this->postValidationCheck($request);
        $data = $this->getPostData($request);
        if ($request->hasFile('postImage')) {
            // $request->postImage->store('myImage');
            $fileName = uniqid() . $request->file('postImage')->getClientOriginalName(); // Name + file type
            $request->file('postImage')->storeAs('public', $fileName); // should no use (over write)
            $data['image'] = $fileName;
        }

        Post::create($data);
        return redirect()->route('post#createPage')->with(['insertSuccess' => 'Post ဖန်တီးခြင်းအောင်မြင်ပါသည်']);
        // return redirect('URL'); for url (server side)
        // return redirect()->route('name'); for name (server side)
    }

    // post delete
    public function postDelete($id)
    {
        // first way
        Post::where('id', $id)->delete();
        return redirect()->route('post#createPage'); // return back();

        //secont way
        // $post = Post::find($id->delete());
        // return back();
    }

    // direct update page
    public function updatePage($id)
    {
        $post = Post::where('id', $id)->first();
        return view('update', compact('post'));
    }

    // edit page
    public function editPage($id)
    {
        $item = Post::where('id', $id)->get()->toArray();
        // dd($item);
        return view('edit', compact('item'));
    }

    // update post
    public function update(Request $request)
    {
        // dd($request->all());
        $this->postValidationCheck($request);

        $updateData = $this->getPostData($request);
        $id = $request->postId;

        if ($request->hasFile('postImage')) {

            // delete old image
            $oldImageName = Post::select('image')->where('id',$request->postId)->first()->toArray();
            $oldImageName = $oldImageName['image'];

            if($oldImageName != null){
                Storage::delete('public/'.$oldImageName);
            }

            // $request->postImage->store('myImage');
            $fileName = uniqid() . $request->file('postImage')->getClientOriginalName(); // Name + file type
            $request->file('postImage')->storeAs('public', $fileName); // should no use (over write)
            $updateData['image'] = $fileName;
        }

        Post::where('id', $id)->update($updateData);
        return redirect()->route('post#createPage')->with(['updateSuccess' => 'Update လုပ်ခြင်းအောင်မြင်ပါသည်။']);
    }

    // get update data
    // private function getUpdateData($request){
    //     return[
    //         'title' => $request->updateName ,
    //         'desciption' => $request->updateDescription
    //     ];
    // }

    // get post data
    private function getPostData($request)
    {
        $data = [
            'title' => $request->postTitle,
            'description' => $request->postDescription
        ];
        $data['price'] = $request->postFee == null ? 2000 : $request->postFee;
        $data['address'] = $request->postAddress == null ? 'Taunggyi' : $request->postAddress;
        $data['rating'] = $request->postRating == null ? 2 : $request->postRating;
        // return [
        //     'title' => $request->postTitle,
        //     'description' => $request->postDescription,
        //     'price' => $request->postFee,
        //     'address' => $request->postAddress,
        //     'rating' => $request->postRating,
        // ];
        return $data;
    }

    private function postValidationCheck($request)
    {

        $validationRules = [
            'postTitle' => 'required|min:5|unique:posts,title,' . $request->postId,
            'postDescription' => 'required|min:6',
            // 'postFee' => 'required',
            // 'postAddress' => 'required',
            // 'postRating' => 'required',
            'postImage' => 'mimes:jpg,jpeg,png|file',
        ];

        $validationMessage = [
            'postTitle.required' => 'post title ဖြည့်ရန်လိုအပ်ပါသည်။',
            'postDescription.required' => 'post description ဖြည့်ရန်လိုအပ်ပါသည်။',
            'postTitle.min' => 'အနည်းဆုံး ၅ လုံးရှိရန်လိုအပ်ပါသည်။',
            'postTitle.unique' => 'ခေါင်းစဥ်တူရှိနေပါသည်။',
            'postDescription.min' => 'အနည်းဆုံး ၆ လုံးရှိရန်လိုအပ်ပါသည်။',
            'postFee.required' => 'Post Fee ဖြည့်ရန်လိုအပ်ပါသည်။',
            'postAddress.required' => 'Post Address ဖြည့်ရန်လိုအပ်ပါသည်။',
            'postRating.required' => 'Post Rating ဖြည့်ရန်လိုအပ်ပါသည်။',
            'postImage.mimes' => 'Image သည် jpeg,jpg,png type သာဖြစ်ရပါမည်။',
            'postImage.file' => 'Image သည် file type သာဖြစ်ရပါမည်။'
        ];

        Validator::make($request->all(), $validationRules, $validationMessage)->validate();
    }
}
