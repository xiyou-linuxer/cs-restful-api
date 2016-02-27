<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Requests;

//use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\CsMail;
use App\Models\CsUser;
use Response;

use Input,DB;

class CsMailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly Mail in storage.
     *
     * @param  \Illuminate\Http\Request $request used for
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        $fromuid = $request->get('id');
        $users = explode(',', $request->get('toname'));

        for ($i = 0; $i < count($users); $i++) {
            $tempuid = DB::table ('cs_user')->select('id')->where('name', $users[$i]);
            if ($tempuid == "") {
                $unfind = $users[$i];
            } else {
                $find[$tempuid] = "0";
            }
        }
        $user_json = json_encode($find);
        if ($request->get('id') == null) {
            $result = DB::table('cs_user')->where('id',$id)
                ->update([
                    'fromuid' => $fromuid,
                    'title' => $request->get('title'),
                    'content' => $request->get('title'),
                    'touid' => $user_json
                    ]);    
        } else {
            $result = DB::table('cs_user')->insert(
                ['fromuid' => $request->get('fromuid'),
                 'title'   => $request->get('title'),
                 'content' => $request->get('content'),
                 'touid'   => $user_json
                ]
            );
        }
        if ($unfind == NULL) {
            $str = ($result == true) ? "true":"false";
            return json_encode(array("result"=>$str));
        } else {
            $str = implode(",",$unfind);
            return json_encode(array("result"=>$str));
        }

    }

    /**
     * Display the Mail resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mail = CsMail::findOrFail($id);
        return new Response(json_encode($mail),200);
    }

    /**
     * Remove the specified Mail from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mid = CsMail::find($id);
        $isdraft = $mid->isdraft;

        if ($isdraft == 1) {
            $mid->delete();
        } else {
            $array = json_decode($mid->touid);
            foreach ( $array as $key => $value) {
                if($key == $id) {
                    $value = 2;
                }
                $array->{$key} = "$value";
            }
            $new_json = json_encode($array);
            $mid->touid = $new_json;
            $result = $mid->save();
        }
        return new Response(json_encode(array("res" => $result)),201);
    }
    public function mail_all($id) {
        
       $result = DB::table('cs_mail')->select('cs_mail.id', 'title','sdate as date', 'cs_user.name as fromuser','touid','content','fromuid')->leftjoin('cs_user','cs_mail.fromuid','=','cs_user.id')->where('cs_mail.touid','like',"%\"".$id."\":\"_\"%")->where('cs_mail.isdraft',0)->orderBy('sdate','desc')->get();
        //var_dump(DB::getQueryLog());
        //var_dump($result);
        if(empty($result)) {
            return new Response(json_encode(array("res" => 'empty')),200);
        }
        
        for ( $i = 0; $i < count($result); $i ++ ) {
            foreach ( $result[$i] as $key=>$value) {
                if ( $key == "touid" ) {
                    $touid = json_decode($value);
                    $status = $touid->{$id};
                    if ( $status == 1 ) {
                        $status = "已读";
                    } else if( $status == 0) {
                        $status = "未读";
                    }
                    $new_result[$i]["status"] = $status;
                    continue;
                }
                $new_result[$i]["$key"] = "$value";
            }
        }
        return new Response(json_encode($new_result),200);
    }

    public function mail_unread(Request $request, $id) {
        
       $result = DB::table('cs_mail')->select('cs_mail.id', 'title','sdate as date', 'cs_user.name as fromuser','touid','content','fromuid')->leftjoin('cs_user','cs_mail.fromuid','=','cs_user.id')->where('cs_mail.touid','like',"%\"".$id."\":\"0\"%")->where('cs_mail.isdraft',0)->orderBy('sdate','desc')->get();
        //var_dump(DB::getQueryLog());
        //var_dump($result);
        if($result == null) {
            return Response(json_encode(array("result" => 'empty')),200);
        }
        return Response($result,200);
    }

    public function mail_read(Request $request, $id) {
        
       $result = DB::table('cs_mail')->select('cs_mail.id', 'title','sdate as date', 'cs_user.name as fromuser','touid','content','fromuid')->leftjoin('cs_user','cs_mail.fromuid','=','cs_user.id')->where('cs_mail.touid','like',"%\"".$id."\":\"1\"%")->where('cs_mail.isdraft',0)->orderBy('sdate','desc')->get();
        if($result == null) {
            return Response(json_encode(array("result" => 'empty')),200);
        }
        return Response($result,200);
    }

    public function mail_send($id) {
        
       $result = DB::table('cs_mail')->select('cs_mail.id', 'title','sdate as date', 'cs_user.name as fromuser','touid','content','fromuid')->leftjoin('cs_user','cs_mail.fromuid','=','cs_user.id')->where('cs_mail.isdraft',0)->where('cs_mail.fromuid',$id)->orderBy('sdate','desc')->get();
        //  var_dump(DB::getQueryLog());
        /*$result =array($result);
        $result1 = $result[0];
        foreach($result1 as $res) {
            $res->status = "已发";
        }*/
        //$result1 = json_encode($result1);
        //dd($result1);
        if ( $result == null ) {
            return new Response(json_encode(array("result"=>"empty")),200);
        }

        return Response($result,200);

    }
    

    public function mail_draft($id) {
        
       $result = DB::table('cs_mail')->select('cs_mail.id', 'title','sdate as date', 'cs_user.name as fromuser','touid','content','fromuid')->leftjoin('cs_user','cs_mail.fromuid','=','cs_user.id')->where('cs_mail.isdraft',1)->where('cs_mail.fromuid',$id)->orderBy('sdate','desc')->get();
        if ( $result == null ) {
            return Response(json_encode(array("result"=>"empty")),200);
        }

        return Response($result,200);
    }

    public function count($name) {
        /*$all_count = DB::table('cs_mail')
            ->whereRaw("touid like %\"$uid\:\"0\"% or touid like %\"$uid\:\"0\"%")
            ->where('isdraft', '=', '0')->count();
        $unread_count = DB::table('cs_mail')->count('id')
            ->whereRaw("touid like '%\"$uid\":\"0\"%' and isdraft=0")->get();
        $read_count = DB::table('cs_mail')->count('id')
            ->whereRaw("touid like '%\"$uid\":\"1\"%' and isdraft=0")->get();
        $draft_count = DB::table('cs_mail')->count('id')
            ->whereRaw("fromuid=$uid and isdraft=1")->get();
        return new Response(json_encode(array("all"=>$all_count, "unread"=>$unread_count,     "read"=>$read_count, "draft"=>$draft_count)),200);*/
    }

    public function set_readed(Requests $request, $id) {
        $result = DB::table('cs_mail')->select('touid')->where('id', '=', $id);
        $touid = json_decode($result);
        $touid->{$request->get('uid')} = "1";
        $touid_json = json_encode($touid);
        $result = DB::table('cs_mail')->where('id', '=', $id)->update(['touid' => $touid_json]);
    }

    public function match($name='') {
        $result = DB::table('cs_user')->select('name')
            ->where('name', 'like', "%".$name."%")->get();
        if ($result == null) {
            return new Response(json_encode(['res' => null]),200);
        } else {
            return new Response($result,200);
        }
    }
}
