<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">

             <?php
               if(isset($contractor_details) && !empty($contractor_details)){
              ?>

              @if(isset($contractor_details->avatar_location) && !empty($contractor_details->avatar_location))
              <?php {$pic= url('img/contractor/profile/'.$contractor_details->avatar_location);}?>

              <tr>
                  <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                 <td><img src="{{ $pic }}" class="user-profile-image" style="height: 100px;"/>
              </td>
           </tr>
              @else
              <?php {$profilepic= url('img/frontend/user.png');} ?>
               <tr>
                  <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                 <td><img src="{{ $profilepic }}" class="user-profile-image" style="height: 77px;"></td>
          </tr>
        @endif

        @if(isset($contractor_details->username) && !empty($contractor_details->username))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>{{ $contractor_details->username }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>-</td>
            </tr>
        @endif

        @if(isset($contractor_details->identity_no) && !empty($contractor_details->identity_no) && ($contractor_details->ruc_no) && !empty($contractor_details->ruc_no))
                <tr>
                    <th>@lang('labels.backend.access.users.tabs.content.overview.ruc')</th>
                    <td>{{ $contractor_details->ruc_no }} - {{  $contractor_details->identity_no }}</td>
                </tr>
            @elseif(isset($contractor_details->ruc_no) && !empty($contractor_details->ruc_no))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.ruc')</th>
                <td>{{ $contractor_details->ruc_no }}</td>
            </tr>
            @elseif(isset($contractor_details->identity_no) && !empty($contractor_details->identity_no))
                <tr>
                    <th>@lang('labels.backend.access.users.tabs.content.overview.ruc')</th>
                    <td>{{  $contractor_details->identity_no }}</td>
                </tr>
    
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.ruc')</th>
                <td>-</td>
            </tr>
        @endif 

             @if(isset($contractor_details->mobile_number) && !empty($contractor_details->mobile_number))

             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.mobileno')</th>
                <td>{{ $contractor_details->mobile_number }}</td>
            </tr>
              @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.mobileno')</th>
                <td>-</td>
            </tr>
            @endif


            @if(isset($contractor_details->landline_number) && !empty($contractor_details->landline_number))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</th>
                <td>{{ $contractor_details->landline_number }}</td>
            </tr>
            @else
              <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</th>
                <td>-</td>
            </tr>
            @endif

            @if(isset($contractor_details->office_number) && !empty($contractor_details->office_number))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officenumber')</th>
                <td>{{ $contractor_details->office_number }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officenumber')</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($contractor_details->address) && !empty($contractor_details->address))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                <td style="white-space: break-spaces;">{{ $contractor_details->address }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($contractor_details->office_address) && !empty($contractor_details->office_address))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</th>
                <td style="white-space: break-spaces;">{{ $contractor_details->office_address }}</td>
            </tr>
            @else
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

           @if(isset($contractor_details->other_address) && !empty($contractor_details->other_address))

            <tr>
                 <th>@lang('labels.backend.access.users.tabs.content.overview.other address')</th>
                <td style="white-space: break-spaces;">{{ $contractor_details->other_address }}</td>
            </tr>
            @else
             <tr>
                 <th>@lang('labels.backend.access.users.tabs.content.overview.other address')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($contractor_details->dob) && !empty($contractor_details->dob))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.dob')</th>
                <td><?php echo date('m-d-Y', strtotime($contractor_details->dob)); ?></td>
            </tr>

            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.dob')</th>
                <td>-</td>
            </tr>
            @endif

           @if(isset($contractor_details->profile_description) && !empty($contractor_details->profile_description))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</th>
               <td style="white-space: break-spaces;">{{ $contractor_details->profile_description }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</th>
               <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($contractor_details->email) && !empty($contractor_details->email))
                <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>{{ $contractor_details->email }}</td>
            </tr>
          @else
           <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>-</td>
            </tr>
            @endif
          {{-- <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                <td>@include('backend.auth.user.includes.status', ['user' => $user])</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.confirmed')</th>
                <td>@include('backend.auth.user.includes.confirm', ['user' => $user])</td>
            </tr>
       --}}
            <!-- @if(isset($contractor_details->timezone) && !empty($contractor_details->timezone))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>{{ $contractor_details->timezone }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>-</td>
            </tr>
            @endif -->



            <!-- @if(isset($contractor_details->last_login_ip) && !empty($contractor_details->last_login_ip))
            <tr>
            <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_ip')</th>
                <td>{{ $contractor_details->last_login_ip ?? 'N/A' }}</td>
            </tr>
            @else
            <tr>
            <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_ip')</th>
                <td>N/A</td>
            </tr>
            @endif -->

            @if(isset($contractor_details->facebook_url) && !empty($contractor_details->facebook_url))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</th>
                <td>{{ $contractor_details->facebook_url }}</td>
            </tr>

            @else
              <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</th>
                <td>-</td>
            </tr>
             @endif

         @if(isset($contractor_details->youtube_url) && !empty($contractor_details->youtube_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</th>
                <td>{{ $contractor_details->youtube_url }}</td>
            </tr>

          @else
           <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</th>
                <td>-</td>
            </tr>

          @endif

          @if(isset($contractor_details->instagram_url) && !empty($contractor_details->instagram_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</th>
                <td>{{ $contractor_details->instagram_url }}</td>
            </tr>

             @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</th>
                <td>-</td>
            </tr>

          @endif

         @if(isset($contractor_details->snap_chat_url) && !empty($contractor_details->snap_chat_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</th>
                <td>{{ $contractor_details->snap_chat_url }}</td>
            </tr>
        @else
          <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</th>
                <td>-</td>
         </tr>

         @endif

           @if(isset($contractor_details->twitter_url) && !empty($contractor_details->twitter_url))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.twitterurl')</th>
                <td>{{ $contractor_details->twitter_url }}</td>
            </tr>
           @else
          <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.twitterurl')</th>
                <td>-</td>
         </tr>

            @endif
          <?php }?>
        </table>
    </div>
</div><!--table-responsive-->
