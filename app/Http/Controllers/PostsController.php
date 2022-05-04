<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;

class PostsController extends Controller
{
    function liked(Request $request)
    {
        $like = new Like;
        $like->post_id = $request->postID;
        $like->person_id = $_COOKIE["person_id"];
        $like->save();
    }


    function unliked(Request $request)
    {
        Like::where('post_id', '=', $request->postID)
            ->where('person_id', '=', $_COOKIE["person_id"])
            ->delete();
    }


    function addComment(Request $request)
    {
        $comment = new Comment;
        $comment->comment_message = $request->comment;
        $comment->post_id = $request->postID;
        $comment->person_id = $_COOKIE["person_id"];
        $comment->save();
    }


    function deletePost(Request $request)
    {
        $imageData = Image::where('post_id', '=', $request->postID)->get();
        if (count($imageData) != 0) {
            $imageData = json_decode(json_encode($imageData), true);
            $image_path = $imageData[0]["image"];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        Post::where('id', '=', $request->postID)->delete();
    }


    function updatePost(Request $request)
    {
        $post = Post::find($request->input('id'));
        $post->caption = $request->input('caption');
        $post->date = date('M d, Y');
        $post->save();

        $imageData = Image::where('post_id', '=', $request->input('id'))->get();

        $quality = 60; //Medium Quality
        if ($request->hasFile('image')) {
            $pic = $request->file('image');
            $picName = time() . "-PID=" . $_COOKIE["person_id"] . "__" . $pic->getClientOriginalName();
            $img = \Image::make($pic);
            $destination = 'post-pics/' . $picName;
            $img->save(\public_path($destination), $quality);

            if (count($imageData) == 0) {
                $image = new Image;
                $image->image = $destination;
                $image->post_id = $request->input('id');
                $image->save();
            } else if (count($imageData) != 0) {
                $imageData = json_decode(json_encode($imageData), true);
                $image_path = $imageData[0]["image"];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $image = Image::find($imageData[0]["id"]);
                $image->image = $destination;
                $image->post_id = $request->input('id');
                $image->save();
            }
        }

        return redirect()->back();
    }


    function get_post_data_through_ID($id)
    {
        $postData = Post::join('images', 'posts.id', '=', 'images.post_id')
            ->where('posts.id', '=', $id)
            ->get(['posts.caption', 'images.image']);
        $postData = json_decode(json_encode($postData), true);

        if (count($postData) == 0) {
            $postData = Post::where('posts.id', '=', $id)->get(['posts.caption']);
            $postData = json_decode(json_encode($postData), true);
        }

        return $postData[0];
    }


    function createPost(Request $request)
    {
        $post = new Post;
        $post->caption = $request->input('caption');
        $post->date = date('M d, Y');
        $post->person_id = $_COOKIE["person_id"];
        $post->save();

        $quality = 60; /* Medium Quality */
        if ($request->hasFile('image')) {
            // This creates a folder if the folder does not exist
            $path = 'post-pics';
            if(!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $pic = $request->file('image');
            $picName = time() . "-PID=" . $_COOKIE["person_id"] . "__" . $pic->getClientOriginalName();
            $img = \Image::make($pic);
            $destination = 'post-pics/' . $picName;
            $img->save(\public_path($destination), $quality);

            $image = new Image;
            $image->image = $destination;
            $image->post_id = $post->id;
            $image->save();
        }

        return redirect()->back();
    }


    function getPosts()
    {
        $postsData = Post::join('people', 'posts.person_id', '=', 'people.id')
            ->orderBy('posts.id', 'DESC')
            ->get([
                'posts.id', 'posts.caption', 'posts.date', 'posts.person_id',
                DB::raw("CONCAT(people.firstname , ' ', people.surname) AS person_name"),
                'people.profile_pic'
            ]);

        $postsData = json_decode(json_encode($postsData), true);
        $postsData = array_map(function ($post) {
            $postId = $post["id"];

            $comment_data = Comment::join('people', 'people.id', '=', 'comments.person_id')
                ->where('post_id', '=', $postId)
                ->get([
                    DB::raw("CONCAT(people.firstname , ' ', people.surname) AS person_commented"),
                    'comments.comment_message', 'comments.person_id', 'people.profile_pic'
                ]);
            $comment_data = json_decode(json_encode($comment_data), true);

            $likes_count = Like::where('post_id', '=', $postId)->count();
            $likes_count = json_decode(json_encode($likes_count), true);

            if (isset($_COOKIE["person_id"])) {
                $current_person_liked = Like::where('post_id', '=', $postId)
                    ->where('person_id', '=', $_COOKIE["person_id"])
                    ->count();
                $current_person_liked = json_decode(json_encode($current_person_liked), true);
                $post += ["current_person_liked" => $current_person_liked];

                if ($post["person_id"] == $_COOKIE["person_id"]) {
                    $post += ["own" => 1];
                } else {
                    $post += ["own" => 0];
                }
            } else {
                $post += ["current_person_liked" => 0];
                $post += ["own" => 0];
            }

            $images = Image::where('post_id', '=', $postId)->get(['image']);
            $images = json_decode(json_encode($images), true);

            $post += ["comments" => $comment_data];
            $post += ["likes_count" => $likes_count];
            $post += ["images" => $images];

            return $post;
        }, $postsData);

        return View('screens.home', ["postsData" => $postsData]);
    }
}
