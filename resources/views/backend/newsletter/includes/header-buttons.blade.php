{{ html()->form('POST', route('admin.newsletter.SendmailtToSubscribers'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
    <a href="" class="btn btn-success ml-1" data-toggle="tooltip" type="submit" title="Send Mail To All">Send mail to all</a>
</div>
