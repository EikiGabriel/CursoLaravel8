<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::latest()->paginate();

        return view('admin/posts/index', [
            'posts' => $posts,
        ]);
    }



    public function create()
    {
        return view('admin/posts/create');
    }



    public function store(StoreUpdatePost $request)
    {

        $data = $request->all();

        if($request->image->isValid()){

            $nameFile = Str::of($request->title)->slug('-') . '.' .$request->image->getClientOriginalExtension();

            $image = $request->image->storeAs('posts', $nameFile);
            $data['image'] = $image;
        }

        ///dd('$request->title');  ///$request->title, $request->content, $request->all()  
        Post::create($data);

        return redirect()
                        ->route('posts.index')
                        ->with('message', 'Criado!');

    }




    public function show($id)
    {
        ///$post = Post::where('id', $id)->first();     ///->get()  para retornar todos
        $post = Post::find($id);

        if(!$post){        ///find() filtra a busca por... 
            return redirect()->route('posts.index');
        }
            return view('admin.posts.show', compact('post'));
        dd($post);
    }



    public function destroy($id)
    {


        if(!$post = Post::find($id)){
            return redirect()->route('posts.index');
        }
        
        if(Storage::exists($post->image)){
            Storage::delete($post->image);
        } 
        
        $post->delete();

        return redirect()
                        ->route('posts.index')
                        ->with('message', 'Post Deletado');
    } 



    public function edit($id)
    {
        if(!$post = Post::find($id)){
            return redirect()->route('posts.index');
        }
            return view('admin.posts.edit', compact('post'));
    }



    public function update(StoreUpdatePost $request, $id)
    {
        if(!$post = Post::find($id)){
            return redirect()->route('posts.index');
        }

        $data =  $request->all();

        if($request->image && $request->image->isValid()){
            if(Storage::exists($post->image)){
                Storage::delete($post->image);
            }

            $nameFile = Str::of($request->title)->slug('-') . '.' .$request->image->getClientOriginalExtension();

            $image = $request->image->storeAs('posts', $nameFile);
            $data['image'] = $image;
        }

        
        $post->update($data);

        return redirect()
                        ->route('posts.index')
                        ->with('message', 'Editado com Sucesso!');
    }



    public function search(Request $request)
    {
        $filters = $request->except('_token');

        $posts = Post::where('title', 'LIKE', "%{$request->search}%")
                            ->orWhere('content', 'LIKE', "%{$request->search}%")
                            ->paginate();

        return view('admin.posts.index', compact('posts', 'filters'));

    }
}
