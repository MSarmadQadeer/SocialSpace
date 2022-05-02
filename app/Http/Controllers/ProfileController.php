<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    function move_to_profile_through_ID($id)
    {
        $personData = DB::select("
        SELECT id,CONCAT(person.firstname , ' ', person.surname) AS person_name, gender, profile_pic, bio
        FROM person
        WHERE id=?
        ", [$id]);
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

        $personPosts =  DB::select('
        SELECT post.id,post.caption,post.date,post.person_id, CONCAT(person.firstname , " ", person.surname) AS person_name, profile_pic
        FROM post,person
        WHERE post.person_id=person.id AND post.person_id=?
        ORDER BY post.id DESC;', [$id]);
        $personPosts = json_decode(json_encode($personPosts), true);

        $personPosts = array_map(function ($post) {
            $postId = $post["id"];

            $comment_data = DB::select("SELECT CONCAT(person.firstname , ' ', person.surname) AS person_commented,comment.comment_message,comment.person_id,profile_pic FROM comment,person WHERE person.id=comment.person_id AND post_id=?;", [$postId]);
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
        }, $personPosts);

        return View('screens.profile', ["personData" => $personData[0], "personPosts" => $personPosts]);
    }


    function editBio(Request $request)
    {
        $bio = $request->bio;
        DB::update('UPDATE person SET bio = ? WHERE person.id=?;', [$bio, $_COOKIE["person_id"]]);
    }


    function uploadProfileImg(Request $request)
    {
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
            $imageData = DB::select('SELECT person.profile_pic FROM person WHERE person.id=?;', [$_COOKIE["person_id"]]);
            $imageData = json_decode(json_encode($imageData), true);
            $image = $imageData[0]["profile_pic"];
            if ($image) {
                unlink($image);
            }
            // Upload new pic in database
            DB::update('UPDATE person SET profile_pic = ? WHERE person.id=?;', [$destination, $_COOKIE["person_id"]]);
            return response()->json(['status' => 1, 'msg' => 'Image has been cropped successfully.']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong, try again later']);
        }
    }
}
