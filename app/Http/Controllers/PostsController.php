<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    function liked(Request $request)
    {
        $person_id = $_COOKIE["person_id"];
        $post_id = $request->postID;
        $like_id = DB::table('likes')->insertGetId(["post_id" => "$post_id", "person_id" => "$person_id"]);
    }


    function unliked(Request $request)
    {
        $person_id = $_COOKIE["person_id"];
        $post_id = $request->postID;
        $nrd = DB::delete('DELETE FROM likes WHERE post_id=? AND person_id=?;', [$post_id, $person_id]);
    }


    function addComment(Request $request)
    {
        $person_id = $_COOKIE["person_id"];
        $comment = $request->comment;
        $post_id = $request->postID;
        $comment_id = DB::table('comments')->insertGetId(["comment_message" => "$comment", "post_id" => "$post_id", "person_id" => "$person_id"]);
    }


    function deletePost(Request $request)
    {
        $postId = $request->postID;
        $imageData = DB::select('SELECT image FROM images WHERE post_id=?;', [$postId]);
        if (count($imageData) != 0) {
            $imageData = json_decode(json_encode($imageData), true);
            $image_path = $imageData[0]["image"];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        DB::delete('DELETE FROM posts WHERE id=?;', [$postId]);
    }


    function updatePost(Request $request)
    {
        $date = date('M d, Y');
        $caption = $request->input('caption');
        $post_id = $request->input('id');

        DB::update('UPDATE posts SET caption = ?, date = ? WHERE id=?;', [$caption, $date, $post_id]);

        $imageData = DB::select('SELECT image FROM images WHERE post_id=?;', [$post_id]);

        $quality = 60; //Medium Quality
        if ($request->hasFile('image')) {
            $pic = $request->file('image');
            $picName = time() . "-PID=" . $_COOKIE["person_id"] . "__" . $pic->getClientOriginalName();
            $img = \Image::make($pic);
            $destination = 'post-pics/' . $picName;
            $img->save(\public_path($destination), $quality);

            if (count($imageData) == 0) {
                $image_id = DB::table('images')->insertGetId(["image" => "$destination", "post_id" => "$post_id"]);
            } else if (count($imageData) != 0) {
                $imageData = json_decode(json_encode($imageData), true);
                $image_path = $imageData[0]["image"];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                DB::update('UPDATE images SET image = ? WHERE post_id=?;', [$destination, $post_id]);
            }
        }

        return redirect()->back();
    }


    function get_post_data_through_ID($id)
    {
        $postData = DB::select('SELECT posts.caption, images.image FROM posts,images WHERE posts.id=images.post_id AND posts.id=?;', [$id]);
        $postData = json_decode(json_encode($postData), true);

        if (count($postData) == 0) {
            $postData = DB::select('SELECT caption FROM posts WHERE id=?;', [$id]);
            $postData = json_decode(json_encode($postData), true);
        }

        return $postData[0];
    }


    function createPost(Request $request)
    {
        $person_id = $_COOKIE["person_id"];
        $date = date('M d, Y');
        $caption = $request->input('caption');
        $post_id = DB::table('posts')->insertGetId(["caption" => "$caption", "date" => "$date", "person_id" => "$person_id"]);

        $quality = 60; /* Medium Quality */
        if ($request->hasFile('image')) {
            $pic = $request->file('image');
            $picName = time() . "-PID=" . $_COOKIE["person_id"] . "__" . $pic->getClientOriginalName();
            $img = \Image::make($pic);
            $destination = 'post-pics/' . $picName;
            $img->save(\public_path($destination), $quality);

            $image_id = DB::table('images')->insertGetId(["image" => "$destination", "post_id" => "$post_id"]);
        }

        return redirect()->back();
    }


    function getPosts()
    {
        $postsData = DB::select("
        SELECT posts.id,posts.caption,posts.date,posts.person_id,CONCAT(people.firstname , ' ', people.surname) AS person_name, people.profile_pic
        FROM posts,people
        WHERE posts.person_id=people.id
        ORDER BY posts.id DESC;
        ");

        $postsData = json_decode(json_encode($postsData), true);
        $postsData = array_map(function ($post) {
            $postId = $post["id"];

            $comment_data = DB::select("SELECT CONCAT(people.firstname , ' ', people.surname) AS person_commented,comments.comment_message,comments.person_id,people.profile_pic FROM comments,people WHERE people.id=comments.person_id AND post_id=?;", [$postId]);
            $comment_data = json_decode(json_encode($comment_data), true);

            $likes_count = DB::select('SELECT COUNT(*)AS likes_count FROM likes WHERE post_id=?;', [$postId]);
            $likes_count = json_decode(json_encode($likes_count), true);

            if (isset($_COOKIE["person_id"])) {
                $current_person_liked = DB::select('SELECT COUNT(*)AS liked FROM likes WHERE post_id=? AND person_id=?;', [$postId, $_COOKIE["person_id"]]);
                $current_person_liked = json_decode(json_encode($current_person_liked), true);
                $post += ["current_person_liked" => $current_person_liked[0]["liked"]];

                if ($post["person_id"] == $_COOKIE["person_id"]) {
                    $post += ["own" => 1];
                } else {
                    $post += ["own" => 0];
                }
            } else {
                $post += ["current_person_liked" => 0];
                $post += ["own" => 0];
            }

            $images = DB::select('SELECT image FROM images WHERE post_id=?;', [$postId]);
            $images = json_decode(json_encode($images), true);

            $post += ["comments" => $comment_data];
            $post += ["likes_count" => $likes_count[0]["likes_count"]];
            $post += ["images" => $images];

            return $post;
        }, $postsData);

        return View('screens.home', ["postsData" => $postsData]);
    }
}
