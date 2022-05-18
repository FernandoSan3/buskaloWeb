<div class="col">
  <div class="table-responsive">
    <table class="table table-hover">

      <tr>
        <th> @lang('labels.backend.review.table.User Name')</th>
             <?php if (isset($review_detail->username) && !empty($review_detail->username !='')) { ?>
        <td> <?php echo $review_detail->username;?></td>
      </tr>

        <?php if (isset($review_detail->mobile_number) && !empty($review_detail->mobile_number !='')) { ?>
      <tr>
          <th> @lang('labels.backend.review.table.Mobile Number')</th>
          <td> <?php echo $review_detail->mobile_number;?></td>
      </tr>
        <?php } ?>

         <?php if (isset($review_detail->provider_name) && !empty($review_detail->provider_name !='')) { ?>
       <tr>
           <th> @lang('labels.backend.review.table.Provider Name')</th>
           <td> <?php echo $review_detail->provider_name;?></td>
       </tr>
         <?php } ?>

        <?php if (isset($review_detail->request_user) && !empty($review_detail->request_user !='')) { ?>
       <tr>
           <th> @lang('labels.backend.review.table.Request User')</th>
           <td> <?php echo $review_detail->request_user;?></td>
       </tr>
         <?php } ?>


        <?php if (isset($review_detail->category_name) && !empty($review_detail->category_name !='')) { ?>
       <tr>
           <th> @lang('labels.backend.review.table.Category Name')</th>
           <td> <?php echo $review_detail->category_name;?></td>
       </tr>
         <?php } ?>

         <?php if (isset($review_detail->es_service_name) && !empty($review_detail->es_service_name !='')) { ?>
       <tr>
           <th> @lang('labels.backend.review.table.Service Name')</th>
           <td> <?php echo $review_detail->es_service_name;?></td>
       </tr>
         <?php } ?>

          <?php if (isset($review_detail->es_service_name) && !empty($review_detail->es_service_name !='')) { ?>
       <tr>
           <th> @lang('labels.backend.review.table.Sub Service Name')</th>
           <td style="white-space: break-spaces;"> <?php echo $review_detail->es_subservice_name;?></td>
       </tr>
         <?php } ?>

          <?php if (isset($review_detail->es_childsubservices_name) && !empty($review_detail->es_childsubservices_name !='')) { ?>
       <tr>
           <th> @lang('labels.backend.review.table.Child Sub Service Name')</th>
           <td> <?php echo $review_detail->es_childsubservices_name;?></td>
       </tr>
         <?php } ?>

         <?php if (isset($role_detail->name) && !empty($role_detail->name !='') ){
          if($role_detail->name =='contractor' || $role_detail->name =='company' ){
           ?>

       <tr>
            <th> @lang('labels.backend.review.table.Role')</th>
            <td> <?php echo $role_detail->name;?></td>
        </tr>
      <?php } } ?>

       <tr>
          <th> @lang('labels.backend.review.table.Rating')</th>
          <td>
           <div class="star-list">
            <?php if(isset($review_detail->rating) && $review_detail->rating != '')
             {

               for ($i=1; $i <6 ; $i++) {
                 if($i > $review_detail->rating){
                  echo '<i class="fa fa-star"></i>';

                 }else{
                  echo '<i class="fa fa-star" style="color:orange"></i>';

               }
            }

            } else{
              echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( No Review found )';
            }
            ?>
          </div>
        </td>
      </tr>
      @if(isset($review_detail->price) && $review_detail->price != '')
    <tr>
        <th> @lang('labels.backend.review.table.Price')</th>
        <td>
          <div class="star-list">
            <?php if(isset($review_detail->price) && $review_detail->price != '')
             {

               for ($i=1; $i <6 ; $i++) {
                 if($i > $review_detail->price){
                  echo '<i class="fa fa-star"></i>';


                 }else{
                  echo '<i class="fa fa-star" style="color:orange"></i>';

               }
            }

            } else{
              echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( No Review found )';
              }
            ?>
          </div>
        </td>
    </tr>
    @endif

    @if(isset($review_detail->puntuality) && $review_detail->puntuality != '')
    <tr>
        <th> @lang('labels.backend.review.table.Puntuality')</th>
        <td>
          <div class="star-list">
            <?php if(isset($review_detail->puntuality) && $review_detail->puntuality != '')
            {

               for ($i=1; $i <6 ; $i++) {
                 if($i > $review_detail->puntuality){
                  echo '<i class="fa fa-star"></i>';

                 }else{
                  echo '<i class="fa fa-star" style="color:orange"></i>';
               }
            }

            } else{
              echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( No Review found )';
            }
            ?>
          </div>
        </td>
    </tr>
    @endif

    @if(isset($review_detail->service) && $review_detail->service != '')
    <tr>
        <th> @lang('labels.backend.review.table.Service')</th>
        <td>
          <div class="star-list">
            <?php if(isset($review_detail->service) && $review_detail->service != '')
            {

               for ($i=1; $i <6 ; $i++) {
                 if($i > $review_detail->service){
                  echo '<i class="fa fa-star"></i>';

                 }else{
                  echo '<i class="fa fa-star" style="color:orange"></i>';

               }
            }

            } else{
              echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( No Review found )';
            }
            ?>
          </div>
        </td>
    </tr>
    @endif

    @if(isset($review_detail->quality) && $review_detail->quality != '')
    <tr>
        <th> @lang('labels.backend.review.table.Quality')</th>
        <td>
          <div class="star-list">
            <?php if(isset($review_detail->quality) && $review_detail->quality != '')
            {

               for ($i=1; $i <6 ; $i++) {
                 if($i > $review_detail->quality){
                  echo '<i class="fa fa-star"></i>';


                 }else{
                  echo '<i class="fa fa-star" style="color:orange"></i>';

               }
            }

            } else{
              echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( No Review found )';
            }
            ?>
          </div>
        </td>
    </tr>
    @endif

    @if(isset($review_detail->amiability) && $review_detail->amiability != '')
    <tr>
        <th> @lang('labels.backend.review.table.Amiability')</th>
        <td>
          <div class="star-list">
            <?php if(isset($review_detail->amiability) && $review_detail->amiability != '')
            {

               for ($i=1; $i <6 ; $i++) {
                 if($i > $review_detail->amiability){
                  echo '<i class="fa fa-star"></i>';


                 }else{
                  echo '<i class="fa fa-star" style="color:orange"></i>';

               }
            }

            } else{
              echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( No Review found )';
            }
            ?>
          </div>
        </td>
    </tr>
    @endif
    @if(isset($review_detail->review) && !empty( $review_detail->review))
    <tr>
        <th> @lang('Review')</th>
        <td>{!!  $review_detail->review !!}</td>
    </tr>
    @endif
      <?php }?>
    </table>
  </div>
</div><!--table-responsive-->
