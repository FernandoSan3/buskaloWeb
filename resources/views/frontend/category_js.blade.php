
<!--GET NEXT QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->

<script type="text/javascript">

  function getNextQuestionsByOptionId(firstOptionID)
  {
    var firstQuestID = sessionStorage.getItem('firstQuestID');
    var categoryId = <?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>;
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');

      alert('firstQuestID:-> '+firstQuestID);
      alert('firstOptionID:-> '+firstOptionID);

          //Start Ajax
         $.ajax({
                type: "GET",  
                url: '{!! URL::to("ajax_get_next_questions") !!}',  
                data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&firstQuestID='+firstQuestID+'&firstOptionID='+firstOptionID,
                dataType: "json", 
                success: function(data) 
                {
                     if(data.success == true) 
                      {
                         alert(data.message);
                         //nextQuestionData
                           if(data.nextQuestionData)
                           {
                              var ref_this = $("ul.nav-tabs li a.active");
                              var step_new = ref_this.data("step") + 1;

                              $('#multi-step-application').append(' <li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question'+step_new+'" data-step ="'+step_new+'" ></a></li>');

                              $('#multi-step-dataHere').append('<div class="tab-pane" id="step'+step_new+'"><div class="pro-heading" id="QuestionArea'+step_new+'"><h3 class="modal-title" id="question_step'+step_new+'" ></h3></div><div class="row"><div class="col-md-8"><div class="pro-info"><input type="hidden" name="question[]" id="questionId'+step_new+'"></input><div class="meta-list" id="optionArea'+step_new+'"></div></div><div style="overflow:auto;"><div  class="form-btn"><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >Anterior</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)"  >Siguiente</button></div></div></div></div></div>');

                               sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));
                               sessionStorage.setItem("questionData", JSON.stringify(data.nextQuestionData));
                              // console.log(JSON.stringify(data.nextQuestionData));
                               sessionStorage.setItem("firstOptionData", JSON.stringify(data.nextQuestionData.options));
                         }
                         else
                         {
                            alert("nothing found in next");

                            $('#multi-step-application').append(' <li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question'+step_new+'" data-step ="'+step_new+'" ></a></li>');

                            $('#multi-step-dataHere').append('<div class="tab-pane fade addressTab"  id="step"'+step_new+'><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>Vamos a finalizar tu solicitud de forma segura</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png'); ?>"><div class="media-body"><h5 class="mt-0">Ingresa la dirección de donde necesitas el Servicio</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address" id="address" placeholder="Escribir Aquí"></div></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >Anterior</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >Siguiente</button></div></div></div>');
                         }


                      }else 
                      {
                        alert(data.message);
                      }
                }  
            });
      //End Ajax     

  }


  function mergeNextQuestionArray()
  {

    var ref_this = $("ul.nav-tabs li a.active");
    var step_new = ref_this.data("step") + 1;

    alert("merge data on step"+step_new);

    var questionData = sessionStorage.getItem('questionData');
    var firstOptionData = sessionStorage.getItem('firstOptionData');

    var quesOptDataa=JSON.parse(firstOptionData);
    var question=JSON.parse(questionData);
   
    if(question)
    {

          var questionTitle ='<h3 class="modal-title" id="question_step'+step_new+'" >'+question.en_title+'</h3>';

          $('#QuestionArea'+step_new).append(questionTitle);
          var pp=0;

           if(question.question_type=='select')
              {
                  var selectBox = '<label class="cust-radio"><select name="options[]" onChange="getNextQuestionsByOptionId(this.value)" id="questionTypeSelect"><option>Select Option</option></select></label>';
                  $("#optionArea"+step_new).append(selectBox);
              }

        if(quesOptDataa)
        {
             quesOptDataa.forEach((quest) => 
              {
                   //console.log('id: ' + quest.id);
                   //console.log('en_option: ' + quest.en_option);
                if(question.question_type=='checkbox')
                {
                   var optionText='<label class="cust-radio">'+quest.en_option+'<input type="checkbox" value="'+quest.id+'" class="question_option'+pp+'" name="options[]" onClick="getNextQuestionsByOptionId('+quest.id+')"><span class="checkmark"></span></label>';

                   $('#optionArea'+step_new).append(optionText);
                }
                if(question.question_type=='radio')
                {
                    var optionText = '<label class="cust-radio">'+quest.en_option+'<input type="radio" value="'+quest.id+'" class="question_option'+pp+'" onClick="getNextQuestionsByOptionId('+quest.id+')" name="options[]"><span class="checkmark"></span></label>';

                    $('#optionArea'+step_new).append(optionText);
                }
                 if(question.question_type=='select')
                {
                    var optionText = '<option value="'+quest.id+'">'+quest.en_option+'</option>';

                    $('#questionTypeSelect').append(optionText);
                }
                
                pp++;
            });

             if(question.question_type=='textarea')
              {
                var optionText = '<label class="cust-radio"><textarea name="question[]" placeholder="Enter here"></textarea></label>';
                $('#optionArea'+step_new).append(optionText);
              }
          }
    }else
    {
      alert("hello");
    }

}

</script>

<!--GET NEXT QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->



<!--GET FIRST QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->

<script type="text/javascript">
  function getQuestionsById(childsubserviceId)
{
    sessionStorage.setItem("childsubserviceId", childsubserviceId);

    var categoryId = <?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>;
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');

    alert('childsubserviceId ->'+childsubserviceId);

          //Start Ajax
         $.ajax({
                type: "GET",  
                url: '{!! URL::to("ajax_get_questions") !!}',  
                data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId,
                dataType: "json", 
                success: function(data) 
                {
                     if(data.success == true) 
                      {
                         alert(data.message);
                         
                         //console.log(JSON.stringify(data.questionData.en_title));
                         sessionStorage.setItem("firstQuestID", JSON.stringify(data.questionData.id));
                         sessionStorage.setItem("questionData", JSON.stringify(data.questionData));
                         //console.log(JSON.stringify(data.questionData.options));
                         sessionStorage.setItem("firstOptionData", JSON.stringify(data.questionData.options));
                         sessionStorage.setItem("childsubservicename",data.childsubservicename);

                      }else 
                      {
                        alert(data.message);
                      }
                }  
            });
      //End Ajax
  }


function mergeFisrtQuestionArray()
  {
    alert("merge data on step 4");

    var questionData = sessionStorage.getItem('questionData');
    var firstOptionData = sessionStorage.getItem('firstOptionData');
    var childsubservicename = sessionStorage.getItem('childsubservicename');

    var quesOptDataa=JSON.parse(firstOptionData);
    var question=JSON.parse(questionData);
    if(quesOptDataa)
    {
    
        $('#sideChildSubServiceName').replaceWith('<li><h6>Child Sub-servicio</h6><p>'+childsubservicename+'</p></li>');
        //console.log(question.en_title);
       //console.log(question.question_type);

        var questionTitle ='<h3 class="modal-title" id="question_step1" >'+question.es_title+'</h3>';
        $('#QuestionArea').append(questionTitle);
        var m=0;
        quesOptDataa.forEach((quest) => 
        {
               //console.log('id: ' + quest.id);
               //console.log('en_option: ' + quest.en_option);
            if(question.question_type=='checkbox')
            {
               var optionText='<label class="cust-radio">'+quest.en_option+'<input type="checkbox" value="'+quest.id+'" class="question_option'+m+'" name="options[]" onClick="getNextQuestionsByOptionId('+quest.id+')"><span class="checkmark"></span></label>';

               $('#optionArea').append(optionText);
            }
            
            m++;
       });
    }

  }


</script>

<!--GET FIRST QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->




<!---Get CHILD SERVICE accordingly SUBSERVICE ID Onchange IN STEP 2-->

<script>

function getSubserviceIdForChild(subserviceId)
{
    sessionStorage.setItem("subserviceId", subserviceId);
    alert('subserviceId ->'+subserviceId);

      //Start Ajax
         $.ajax({
                type: "GET",  
                url: '{!! URL::to("ajax_get_childservice") !!}',  
                data:'subserviceId='+subserviceId,
                dataType: "json", 
                success: function(data) 
                {
                     if(data.success == true) 
                      {
                        //console.log(data.childservices);
                        alert(data.message);
                        sessionStorage.setItem("childservicesArray", JSON.stringify(data.childservices));
                        sessionStorage.setItem("subservicename",data.subservicename);
                      }else 
                      {
                        alert(data.message);
                      }
                }  
            });
      //End Ajax
}

 function mergeChildServiceArray()
  {
    alert("merge data on step 3");

    var subservicename = sessionStorage.getItem('subservicename');
    var childservices = sessionStorage.getItem('childservicesArray');
    var chDattaa=JSON.parse(childservices);
    if(chDattaa)
    {
    
        $('#sideSubServiceName').replaceWith('<li><h6>Sub-servicio</h6><p>'+subservicename+'</p></li>');

       //console.log(dattaa);
        var j=0;
        chDattaa.forEach((itemarr) => 
        {
              // console.log('id: ' + itemarr.id);
             // console.log('en_name: ' + itemarr.en_name);
             var chLoopData='<label class="cust-radio">'+itemarr.en_name+'<input type="radio" value="'+itemarr.id+'" class="childservice_class" onClick="getQuestionsById('+itemarr.id+')"  name="getchildservice_id" id="chsr_id'+j+'" data-childservicename ="'+itemarr.en_name+'"><span class="checkmark"></span></label>';

             $('#childServicesArrayAppend').append(chLoopData);

            j++;
       });
    }

  }
</script>

<!---Get CHILD SERVICE accordingly SUBSERVICE ID Onchange IN STEP 2-->


<!---Get subservice accordingly Service ID Onchange IN STEP 1-->

<script type="text/javascript">

$(document).ready(function() {

  $('.stepOneServiceClass').change(function()
  {

    var serviceId = $( this ).val();
    sessionStorage.setItem("serviceId", serviceId);

    alert('serviceId ->'+serviceId);

      //Start Ajax
         $.ajax({
                type: "GET",  
                url: '{!! URL::to("ajax_get_subservice") !!}',  
                data:'serviceId='+serviceId,
                dataType: "json", 
                success: function(data) 
                {
                     if(data.success == true) 
                      {
                        //console.log(data.subservices);
                         alert(data.message);
                         sessionStorage.setItem("subservicesArray", JSON.stringify(data.subservices));
                         sessionStorage.setItem("servicename",data.servicename);
                      }else 
                      {
                        alert(data.message);
                      }
                }  
            });
     
      //End Ajax

  });
});



 function mergeSubServiceArray()
  {
    alert("merge data on step 2");
    var subservices = sessionStorage.getItem('subservicesArray');
    var servicename = sessionStorage.getItem('servicename');
    var dattaa=JSON.parse(subservices);
    //console.log(dattaa);
      if(dattaa)
      {
       $('#sideServiceName').replaceWith('<li><h6>Servicio</h6><p>'+servicename+'</p></li>');
        var i=0;
        dattaa.forEach((item) => 
        {
            // console.log('id: ' + item.id);
            // console.log('en_name: ' + item.en_name);
            var loopData='<label class="cust-radio">'+item.en_name+'<input type="radio" value="'+item.id+'" class="subservice_class" onClick="getSubserviceIdForChild('+item.id+')" name="getsubservice_id" id="sbsr_id'+i+'" data-subservicename ="'+item.en_name+'"><span class="checkmark"></span></label>';
             $('#appendSubserviceArray').append(loopData);

            i++;
       });

     }

  }


</script>

<!---Get subservice accordingly Service ID Onchange IN STEP 1-->