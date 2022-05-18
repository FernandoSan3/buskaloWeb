<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\PriceRange;
use App\Models\Subservices;
use App\Models\Category;
use App\Models\ChildSubservices;
use App\Models\Questions;
use App\Models\QuestionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class QuestionsController extends Controller
{
    
    public function index()
    {        
        $questions = Questions::leftjoin('category','questions.category_id','=','category.id')
        ->leftjoin('services','questions.services_id','=','services.id')
        ->leftjoin('sub_services','questions.sub_services_id','=','sub_services.id')
        ->leftjoin('child_sub_services','questions.child_sub_service_id','=','child_sub_services.id')
        ->select('questions.*','category.es_name as category_name','sub_services.es_name as sub_services_name','services.es_name as services_name','child_sub_services.es_name as child_subservice_name')
        ->latest()
        ->where('questions.deleted_at',NULL)
        ->get(); 
        return view('backend.questions.index',compact('questions'));
    }
   

    public function create()
    {    
    	
        $subservices = array();
        $childsubservices = array();
        $price_ranges = PriceRange::all()->where('deleted_at',NULL);
        $categories = Category::all()->where('deleted_at',NULL);
        $services = Services::all()->where('deleted_at',NULL);
        $subservices = Subservices::all()->where('deleted_at',NULL);
        $childsubservices = ChildSubservices::all()->where('deleted_at',NULL);
        return view('backend.questions.create',compact('subservices','services','childsubservices','price_ranges','categories'));
    }
  
    
    public function store(Request $request)
    {   
       //echo "<pre>"; print_r($request->all()); die;
       
        $request->validate([
            'category_id' => 'required',
            'services_id' => 'required',
            'sub_services_id' => 'required',
            'question_type' => 'required',
            'is_related' => 'required',
            'en_title' => 'required',
            'es_title' => 'required',
        ]);


        // if($request->question_type == 'radio' || $request->question_type == 'checkbox' || $request->question_type == 'select') {

        //     $request->validate([                
        //         'en_title' => 'required',
        //         'es_title' => 'required',
        //     ]);            
            
        // } else {
        //     $request->validate([                
        //         'en_title' => 'required',
        //         'es_title' => 'required',
        //     ]);
        // }

        if($request->is_related == 'Yes') {
            $request->validate([                
               // 'related_question_id' => 'required',
               // 'related_option_id' => 'required',
            ]);

        }

        if($request->is_related == 'Yes') {
            $insert_qns['is_related'] = 1; 
            $insert_qns['related_question_id'] = $request->related_question_id;
            $insert_qns['related_option_id'] = $request->related_option_id;
        }else{
             $insert_qns['is_related'] = 0; 
        }

        $insert_qns['category_id'] = $request->category_id;
        $insert_qns['services_id'] = $request->services_id;
        $insert_qns['sub_services_id'] = $request->sub_services_id;
        $insert_qns['child_sub_service_id'] = $request->child_sub_services_id;
        $insert_qns['en_title'] = $request->en_title;
        $insert_qns['es_title'] = $request->es_title;
        $insert_qns['question_order'] = $request->question_order;
        $insert_qns['question_type'] = $request->question_type;       
             
        $insert_qns['created_at'] = Carbon::now();
        $insert_qns['updated_at'] = Carbon::now();

        $question_id = DB::table('questions')->insertGetId($insert_qns);

         //if($request->question_type == 'radio' || $request->question_type == 'checkbox' || $request->question_type == 'select') {

                $get_array_index = array_keys($request->ans['en']);
//print_r($request->ans['en']);exit;
                if(array_filter($request->ans['en']))
                {
                     foreach ($get_array_index as $key => $value) {
                        $insert_ans['question_id'] = $question_id;
                        $insert_ans['en_option'] = $request->ans['en'][$value];
                        $insert_ans['es_option'] = $request->ans['es'][$value];
                        $insert_ans['price'] = $request->ans['price'][$value];
                        $insert_ans['factor'] = $request->ans['factor'][$value];
                       // $insert_ans['quantity'] = $request->ans['quantity'][$value];
                        $insert_ans['created_at'] = Carbon::now();
                        $insert_ans['updated_at'] = Carbon::now();

                        QuestionOptions::insert($insert_ans);
                    }
                }
            //}
   
        return redirect()->route('admin.questions.index')->with('success','Questions created successfully.');
    }
   
    
    public function show($question_id)
    {   
        $question_details = Questions::leftjoin('category as C1','questions.category_id','=','C1.id')
        ->leftjoin('services as S1','questions.services_id','=','S1.id')
        ->leftjoin('sub_services as S2','questions.sub_services_id','=','S2.id')
        ->leftjoin('child_sub_services as S3','questions.child_sub_service_id','=','S3.id')
        ->select('questions.*','C1.en_name as en_category_name','C1.es_name as es_category_name','S2.en_name as en_subservice_name','S2.es_name as es_subservice_name','S1.en_name as en_service_name','S1.es_name as es_services_name','S3.en_name as en_childsubservice_name','S3.es_name as es_childsubservice')
        ->where('questions.id',$question_id)
        ->first();

        $question_details->options = QuestionOptions::leftjoin('price_range','question_options.price','=','price_range.id')->select('question_options.*','price_range.start_price','price_range.end_price','price_range.percentage')->where('question_options.question_id',$question_id)->where('question_options.deleted_at',NULL)->get();

        // echo "<pre>"; print_r($question_details->toArray()); die; 
        return view('backend.questions.show',compact('question_details'));
    }
   
    
    public function edit($question_id)
    {   
                
        $categories = Category::all()->where('deleted_at',NULL);
        $services = Services::all()->where('deleted_at',NULL);
        $subservices = Subservices::all()->where('deleted_at',NULL);
        $childsubservices = ChildSubservices::all()->where('deleted_at',NULL);

        $price_ranges = PriceRange::all()->where('deleted_at',NULL);
        $question_details = Questions::where('id',$question_id)->first();
        $question_details->options = QuestionOptions::where('question_id',$question_id)->where('deleted_at',NULL)->get();

        return view('backend.questions.edit',compact('question_details','subservices','price_ranges','categories','services','childsubservices'));
    }
  
    
    public function update(Request $request)
    {
        $request->validate([            
            'question_id' => 'required',
            'en_title' => 'required',
            'es_title' => 'required',
        ]);

        $update_qns['en_title'] = $request->en_title;
        $update_qns['es_title'] = $request->es_title;
        $update_qns['question_order'] = $request->question_order;
        $update_qns['updated_at'] = Carbon::now();

        DB::table('questions')->where('id',$request->question_id)->update($update_qns);
        

            if(isset($request->ans['en']) && !empty($request->ans['en']) && count($request->ans['en'])>0)
            {
                $en_ans_arr = $request->ans['en'];
                $es_ans_arr = $request->ans['es'];
                $new_arr = array();
                $new_arr = array_combine($en_ans_arr,$es_ans_arr);

                QuestionOptions::where('question_id',$request->question_id)->delete();

                // foreach ($new_arr as $key => $value) { 
                    
                //     $insert_ans['question_id'] = $request->question_id;
                //     $insert_ans['en_option'] = $key;
                //     $insert_ans['es_option'] = $value;
                //     $insert_ans['created_at'] = Carbon::now();
                //     $insert_ans['updated_at'] = Carbon::now();

                //     QuestionOptions::insert($insert_ans);
                // }
               
                $get_array_index = array_keys($request->ans['en']);
              
                foreach ($get_array_index as $key => $value) {

                    $insert_ans['question_id'] = $request->question_id;
                    $insert_ans['en_option'] = $request->ans['en'][$value];
                    $insert_ans['es_option'] = $request->ans['es'][$value];
                    $insert_ans['price'] = $request->ans['price'][$value];
                    $insert_ans['factor'] = $request->ans['factor'][$value];
                   // $insert_ans['quantity'] = $request->ans['quantity'][$value];
                    $insert_ans['created_at'] = Carbon::now();
                    $insert_ans['updated_at'] = Carbon::now();

                    QuestionOptions::insert($insert_ans);
                }
            }

     
       
        return redirect()->route('admin.questions.index')->with('success','Question updated successfully');
    }
  
    
    public function destroy($question_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();
        Questions::where('id',$question_id)->update($updateArr);
        QuestionOptions::where('question_id',$question_id)->update($updateArr);  
        return redirect()->route('admin.questions.index')->with('success','Question deleted successfully');
    }

    public function getSubservices(Request $request) {       

        $services_id = $request->input('services_id');
        //echo $services_id; die('as');
        $subservices = Subservices::all()->where('services_id',$services_id)->where('deleted_at',NULL);
       // echo "<pre>"; print_r($subservices->toArray()); die;
        $html = view('backend.questions.get_sub_services')->with(compact('subservices'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    } 

    public function getServices(Request $request) {       

        $category_id = $request->input('category_id');
        //echo $category_id; die('asdas');
        
        $services = Services::all()->where('category_id',$category_id)->where('deleted_at',NULL);
        
        //echo "<pre>"; print_r($services->toArray()); die('sadas');
        $html = view('backend.questions.get_services')->with(compact('services'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function getChildSubservices(Request $request) {       

        $subservices_id = $request->input('sub_services_id');
        $childsubservices = ChildSubservices::all()->where('sub_services_id',$subservices_id)->where('deleted_at',NULL);
        $html = view('backend.questions.get_child_sub_services')->with(compact('childsubservices'))->render();
        //  echo "<pre>"; print_r($html); die('asda');
        return response()->json(['success' => true, 'html' => $html]);
    } 

    public function getOptionView(Request $request) {       

        $added_input = $request->input('added_input');
        $price_ranges = PriceRange::all()->where('deleted_at',NULL);
        $html = view('backend.questions.get_option_view')->with(compact('price_ranges','added_input'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    } 

    public function getRelatedQuestions(Request $request) {       

        //echo "<pre>"; print_r($request->all()); die('here Question');
        $services_id = $request->input('services_id');
        $sub_services_id = $request->input('sub_services_id');
        $related_questions = Questions::where('services_id',$services_id)->where('sub_services_id',$sub_services_id)->whereIn('question_type',['radio','checkbox','select'])->where('deleted_at',NULL)->get();

       
        $rel_option_id = DB::table('questions')->where('is_related',1)->where('deleted_at',NULL)->pluck('related_option_id')->toArray();


        foreach ($related_questions as $key => $value) {
           
           $question_option = DB::table('question_options')->where('question_id',$value->id)->where('deleted_at',NULL)->get();
           $qu_op = count($question_option);
            
        }       
        
        $html = view('backend.questions.get_related_questions')->with(compact('related_questions'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    } 

    public function getRelatedOptions(Request $request) {       

       $rel_option_id = DB::table('questions')->where('is_related',1)->where('deleted_at',NULL)->pluck('related_option_id')->toArray();
        
        $question_id = $request->input('question_id');
        $options = QuestionOptions::all()->where('question_id',$question_id)->where('deleted_at',NULL);
        //echo '<pre>'; print_r($options);//exit;
        $html = view('backend.questions.get_related_options')->with(compact('options'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }
}