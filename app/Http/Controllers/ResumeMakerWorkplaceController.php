<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResumeMakerWorkplaceCollection;
use App\Models\Educational_record;
use App\Models\Personal_resume;
use App\Models\Skill;
use App\Models\Social_network;
use App\Models\User;
use App\Models\User_data;
use App\Models\Work_experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeMakerWorkplaceController extends Controller
{
    public function index()
    {
        try {
            $id = 1;

            $User_data = User_data::where('user_id' , $id)->get();

            $User_data_id = $User_data[0]->id;

            $image = User_data::find($User_data_id)->getMedia();

            $avatar_id = $image[0]->getUrl();

            if ($User_data_id !== null)
            {
               $educational_record = self::FindEducationalRecord($User_data_id);

               $work_experience = self::FindWorkExperience($User_data_id);

               $skill = self::FindSkill($User_data_id);

               $social_network = self::FindSocialNetwork($User_data_id);

               $personal_resume = self::FindPersonalResume($User_data_id);

                return (new ResumeMakerWorkplaceCollection($User_data))->additional([
                    'avatar'=>$avatar_id,
                    'educational_record' => $educational_record,
                    'work_experience'=> $work_experience,
                    'skill'=> $skill,
                    'social_network'=> $social_network,
                    'personal_resume' => $personal_resume
                ]);
            }else{
                return response()->json([
                    "resume"=>null
                ]);
            }
        }catch (\Throwable $th){
            return response()->json($th->getMessage());
        }
    }

    //

    public function FindEducationalRecord($id)
    {
      return Educational_record::where('user_data_id' , $id)->get();
    }

    //

    public function FindWorkExperience($id)
    {
      return Work_experience::where('user_data_id' , $id)->get();
    }

    //

    public function FindSkill($id)
    {
      return Skill::where('user_data_id' , $id)->get();
    }

    //

    public function FindSocialNetwork($id)
    {
        return Social_network::where('user_data_id' , $id)->get();
    }

    //

    public function FindPersonalResume($id)
    {
      $personal_resumes =  Personal_resume::where('user_data_id' , $id)->get();

      $resume = [];

      foreach ($personal_resumes as $personal_resume)
      {
          $resume_name = $personal_resume->name;

          $url = Storage::url($resume_name);

          $resume []= ['url'=>$url , 'name'=>$resume_name ];
      }

      return $resume;
    }
}
