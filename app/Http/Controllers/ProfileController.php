<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Person;

class ProfileController extends Controller
{
    function move_to_profile_through_ID($id)
    {
        $personData = Person::where('id', '=', $id)
            ->get([
                'id', DB::raw("CONCAT(people.firstname , ' ', people.surname) AS person_name"),
                'gender', 'profile_pic', 'bio'
            ]);
        $personData = json_decode(json_encode($personData), true);

        if (isset($_COOKIE["person_id"])) {
            if ($id == $_COOKIE["person_id"]) {
                $personData[0] += ["owner" => 1];
            } else {
                $personData[0] += ["owner" => 0];
            }
        } else {
            $personData[0] += ["owner" => 0];
        }

        $personPosts = Post::join('people', 'posts.person_id', '=', 'people.id')
            ->where('posts.person_id', '=', $id)
            ->orderBy('posts.id', 'DESC')
            ->get([
                'posts.id', 'posts.caption', 'posts.date', 'posts.person_id',
                DB::raw("CONCAT(people.firstname , ' ', people.surname) AS person_name"),
                'people.profile_pic'
            ]);
        $personPosts = json_decode(json_encode($personPosts), true);

        $personPosts = array_map(function ($post) {
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
        }, $personPosts);

        return View('screens.profile', ["personData" => $personData[0], "personPosts" => $personPosts]);
    }


    function editBio(Request $request)
    {
        $person = Person::find($_COOKIE["person_id"]);
        $person->bio = $request->bio;
        $person->save();
    }


    function uploadProfileImg(Request $request)
    {
        // This creates a folder if the folder does not exist
        $path = 'profile-pics';
        if(!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $image = $request->image;

        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name = time() . "-PID=" . $_COOKIE["person_id"] . '.png';
        $destination = 'profile-pics/' . $image_name;
        $path = public_path($destination);

        $upload = file_put_contents($path, $image);

        if ($upload) {
            // Delete Older Profile Image If Exists
            $imageData = Person::where('id', '=', $_COOKIE["person_id"])->get(['profile_pic']);
            $imageData = json_decode(json_encode($imageData), true);
            $image = $imageData[0]["profile_pic"];
            if ($image) {
                unlink($image);
            }
            // Upload new pic in database
            $person = Person::find($_COOKIE["person_id"]);
            $person->profile_pic = $destination;
            $person->save();
            return response()->json(['status' => 1, 'msg' => 'Image has been cropped successfully.']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong, try again later']);
        }
    }
}
