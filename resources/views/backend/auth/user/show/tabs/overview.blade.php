<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">

             <?php
               if(isset($user) && !empty($user)){
              ?>

              @if(isset($user->avatar_location) && !empty($user->avatar_location))
              <?php {$pic= url('img/user/profile/'.$user->avatar_location);}?>

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


            @if(isset($user->username) && !empty($user->username))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>{{ $user->username }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($user->mobile_number) && !empty($user->mobile_number))

             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.mobileno')</th>
                <td>{{ $user->mobile_number }}</td>
            </tr>
              @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.mobileno')</th>
                <td>-</td>
            </tr>
            @endif


            @if(isset($user->landline_number) && !empty($user->landline_number))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</th>
                <td>{{ $user->landline_number }}</td>
            </tr>
            @else
              <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</th>
                <td>-</td>
            </tr>
            @endif

            @if(isset($user->office_number) && !empty($user->office_number))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officenumber')</th>
                <td>{{ $user->office_number }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officenumber')</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($user->address) && !empty($user->address))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                <td style="white-space: break-spaces;">{{ $user->address }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($user->office_address) && !empty($user->office_address))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</th>
                <td style="white-space: break-spaces;">{{ $user->office_address }}</td>
            </tr>
            @else
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

           @if(isset($user->other_address) && !empty($user->other_address))

            <tr>
                 <th>@lang('labels.backend.access.users.tabs.content.overview.other address')</th>
                <td style="white-space: break-spaces;">{{ $user->other_address }}</td>
            </tr>
            @else
             <tr>
                 <th>@lang('labels.backend.access.users.tabs.content.overview.other address')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($user->dob) && !empty($user->dob))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.dob')</th>
                <td><?php echo date('m-d-Y', strtotime($user->dob))?></td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.dob')</th>
                <td>-</td>
            </tr>
            @endif

           @if(isset($user->profile_description) && !empty($user->profile_description))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</th>
               <td style="white-space: break-spaces;">{{ $user->profile_description }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</th>
               <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($user->email) && !empty($user->email))
                <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>{{ $user->email }}</td>
            </tr>
          @else
           <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>-</td>
            </tr>
            @endif
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                <td>@include('backend.auth.user.includes.status', ['user' => $user])</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.confirmed')</th>
                <td>@include('backend.auth.user.includes.confirm', ['user' => $user])</td>
            </tr>

            @if(isset($user->timezone) && !empty($user->timezone))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>{{ $user->timezone }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>-</td>
            </tr>
            @endif


            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_at')</th>
                <td>
                   @if($user->last_login_at)
                        {{ timezone()->convertToLocal($user->last_login_at) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_ip')</th>
                <td>{{ $user->last_login_ip ?? 'N/A' }}</td>
            </tr>
            <?php }?>

            @if(isset($user_details->facebook_url) && !empty($user_details->facebook_url))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</th>
                <td>{{ $user_details->facebook_url }}</td>
            </tr>

            @else
              <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</th>
                <td>-</td>
            </tr>
             @endif

         @if(isset($user_details->youtube_url) && !empty($user_details->youtube_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</th>
                <td>{{ $user_details->youtube_url }}</td>
            </tr>

          @else
           <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</th>
                <td>-</td>
            </tr>

          @endif

          @if(isset($user_details->instagram_url) && !empty($user_details->instagram_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</th>
                <td>{{ $user_details->instagram_url }}</td>
            </tr>

             @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</th>
                <td>-</td>
            </tr>

          @endif

         @if(isset($user_details->snap_chat_url) && !empty($user_details->snap_chat_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</th>
                <td>{{ $user_details->snap_chat_url }}</td>
            </tr>
        @else
          <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</th>
                <td>-</td>
         </tr>

         @endif

           @if(isset($user_details->twitter_url) && !empty($user_details->twitter_url))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.twitterurl')</th>
                <td>{{ $user_details->twitter_url }}</td>
            </tr>
           @else
          <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.twitterurl')</th>
                <td>-</td>
         </tr>

            @endif

        </table>
    </div>
</div><!--table-responsive-->
