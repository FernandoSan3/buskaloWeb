<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">  
          
             <?php
               if(isset($company_details) && !empty($company_details)){
              ?>

              @if(isset($company_details->avatar_location) && !empty($company_details->avatar_location))
              <?php {$pic= url('img/company/profile/'.$company_details->avatar_location);}?>

              <tr>
                  <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                 <td><img src="{{ $pic }}" class="user-profile-image" style="height: 100px;"/>
                 </td>
              </tr>
              @else
              <?php {$profilepic= url('img/frontend/user.png');} ?>

               <tr>
                  <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                 <td><img src=" {{ $profilepic }}" style="height: 77px;"></td>
          </tr>
        @endif

            @if(isset($company_details->username) && !empty($company_details->username))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>{{ $company_details->username }}</td>
            </tr>
            @else
              <tr>
              <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th><td>-</td>
            </tr>
            @endif

            @if(isset($company_details->year_of_constitution) && !empty($company_details->year_of_constitution))
             <tr>
                <th>Year Of Constitution</th>
                <td>{{ $company_details->year_of_constitution }}</td>
            </tr>
            @else
            <tr>
                <th>Year Of Constitution</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($company_details->legal_representative) && !empty($company_details->legal_representative))
             <tr>
                <th>Legal Representative</th>
                <td>{{ $company_details->legal_representative }}</td>
            </tr>
            @else
            <tr>
                <th>Legal Representative</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($company_details->website_address) && !empty($company_details->website_address))
             <tr>
                <th>Web Address</th>
                <td>{{ $company_details->website_address }}</td>
            </tr>
            @else
            <tr>
                <th>Web Address</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($company_details->mobile_number) && !empty($company_details->mobile_number))

             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.mobileno')</th>
                <td>{{ $company_details->mobile_number }}</td>
            </tr>
              @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.mobileno')</th>
                <td>-</td>
            </tr>
            @endif


            @if(isset($company_details->landline_number) && !empty($company_details->landline_number))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</th>
                <td>{{ $company_details->landline_number }}</td>
            </tr>
            @else
              <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</th>
                <td>-</td>
            </tr>
            @endif

            @if(isset($company_details->office_number) && !empty($company_details->office_number))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officenumber')</th>
                <td>{{ $company_details->office_number }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officenumber')</th>
                <td>-</td>
            </tr>
            @endif

             @if(isset($company_details->address) && !empty($company_details->address))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                <td style="white-space: break-spaces;">{{ $company_details->address }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($company_details->office_address) && !empty($company_details->office_address))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</th>
                <td style="white-space: break-spaces;">{{ $company_details->office_address }}</td>
            </tr>
            @else
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

           @if(isset($company_details->other_address) && !empty($company_details->other_address))

            <tr>
                 <th>@lang('labels.backend.access.users.tabs.content.overview.other address')</th>
                <td style="white-space: break-spaces;">{{ $company_details->other_address }}</td>
            </tr>
            @else
             <tr>
                 <th>@lang('labels.backend.access.users.tabs.content.overview.other address')</th>
                <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($company_details->dob) && !empty($company_details->dob))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.dob')</th>
                <td><?php echo date('m-d-Y', strtotime($company_details->dob)) ?></td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.dob')</th>
                <td>-</td>
            </tr>
            @endif

           @if(isset($company_details->profile_description) && !empty($company_details->profile_description))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</th>
               <td style="white-space: break-spaces;">{{ $company_details->profile_description }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</th>
               <td style="white-space: break-spaces;">-</td>
            </tr>
            @endif

             @if(isset($company_details->email) && !empty($company_details->email))
                <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>{{ $company_details->email }}</td>
            </tr>
          @else
           <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>-</td>
            </tr>
            @endif
          {{--  <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                <td>@include('backend.auth.user.includes.status', ['user' => $user])</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.confirmed')</th>
                <td>@include('backend.auth.user.includes.confirm', ['user' => $user])</td>
            </tr>
       --}}
             <!-- @if(isset($company_details->timezone) && !empty($company_details->timezone))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>{{ $company_details->timezone }}</td>
            </tr>
            @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>-</td>
            </tr>
            @endif -->
            <!-- {{--<tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_at')</th>
                <td>
                   @if($company_details->last_login_at)
                        {{ timezone()->convertToLocal($company_details->last_login_at) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
              --}} -->
            <!-- <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_ip')</th>
                <td>{{ $company_details->last_login_ip ?? 'N/A' }}</td>
            </tr> -->
            <?php }?>

            @if(isset($company_details->facebook_url) && !empty($company_details->facebook_url))

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</th>
                <td>{{ $company_details->facebook_url }}</td>
            </tr>

            @else
              <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</th>
                <td>-</td>
            </tr>
             @endif

         @if(isset($company_details->youtube_url) && !empty($company_details->youtube_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</th>
                <td>{{ $company_details->youtube_url }}</td>
            </tr>

          @else
           <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</th>
                <td>-</td>
            </tr>

          @endif

          @if(isset($company_details->instagram_url) && !empty($company_details->instagram_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</th>
                <td>{{ $company_details->instagram_url }}</td>
            </tr>

             @else
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</th>
                <td>-</td>
            </tr>

          @endif

         @if(isset($company_details->snap_chat_url) && !empty($company_details->snap_chat_url))
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</th>
                <td>{{ $company_details->snap_chat_url }}</td>
            </tr>
        @else
          <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</th>
                <td>-</td>
         </tr>

         @endif

           @if(isset($company_details->twitter_url) && !empty($company_details->twitter_url))
             <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.twitterurl')</th>
                <td>{{ $company_details->twitter_url }}</td>
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
