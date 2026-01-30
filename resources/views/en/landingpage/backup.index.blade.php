@extends('layouts-callnchat.master')

@section('title')
<title>Calln.chat</title>

@endsection

@section('content')

<section class="section-webcall">
    <div class="title">
        @if(session()->get('locale') == 'id')
        <h1>@lang('callnchat.header_1.title_1') <label style="color: #FBC236;">@lang('callnchat.header_1.title_2')</label> @lang('callnchat.header_1.title_3')</h1>
        @elseif(session()->get('locale') == 'en')
        <h1><label style="color: #FBC236;">@lang('callnchat.header_1.title_1')</label> @lang('callnchat.header_1.title_2')</h1>
        @else
        <h1><label style="color: #FBC236;">@lang('callnchat.header_1.title_1')</label> @lang('callnchat.header_1.title_2')</h1>
        @endif
    </div>
    <div class="banner">
        @if(session()->get('locale') == 'id')
        <img data-original="{{ asset('assets_callnchat/images/banner-callnchat.png') }}" alt="">
        @elseif(session()->get('locale') == 'en')
        <img data-original="{{ asset('assets_callnchat/images/banner-callnchat-eng.png') }}" alt="">
        @else
        <img data-original="{{ asset('assets_callnchat/images/banner-callnchat-eng.png') }}" alt="">
        @endif
    </div>
</section>

<section class="step-webcall">
    <div class="title">
        <h1>@lang('callnchat.header_2.title')</h1>
    </div>
    <div class="banner">
        @if(session()->get('locale') == 'id')
        <img data-original="{{ asset('assets_callnchat/images/banner-step-webcall.png') }}" alt="">
        @elseif(session()->get('locale') == 'en')
        <img data-original="{{ asset('assets_callnchat/images/banner-step-webcall-eng.png') }}" alt="">
        @else
        <img data-original="{{ asset('assets_callnchat/images/banner-step-webcall-eng.png') }}" alt="">
        @endif
    </div>
</section>

<section class="left-feature-webcall">
    <div class="split-box">
        <div class="left-box">
            <div class="banner">
                @if(session()->get('locale') == 'id')
                <img style="width: 85%" data-original="{{ asset('assets_callnchat/images/left-banner-webcall-1.png') }}" alt="">
                @elseif(session()->get('locale') == 'en')
                <img style="width: 85%" data-original="{{ asset('assets_callnchat/images/left-banner-webcall-1-en.png') }}" alt="">
                @else
                <img style="width: 85%" data-original="{{ asset('assets_callnchat/images/left-banner-webcall-1-en.png') }}" alt="">
                @endif
            </div>
        </div>
        <div class="right-box">
            <div class="content">
                <div class="title">
                    <h1>@lang('callnchat.left_1.header_1') <span style="color: #FBC236">@lang('callnchat.left_1.header_2')</span></h1>
                </div>
                <div class="desc">
                    <h1>@lang('callnchat.left_1.desc')</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="right-feature-webcall">
    <div class="split-box">
        <div class="left-box">
            <div class="content">
                <div class="title">
                    <h1>@lang('callnchat.right_1.header_1') <span style="color: #FBC236">@lang('callnchat.right_1.header_2')</span></h1>
                </div>
                <div class="desc">
                    <h1>@lang('callnchat.right_1.desc')</h1>
                </div>
            </div>
        </div>
        <div class="right-box" style="margin-bottom: 0rem">
            <div class="banner">
                <img data-original="{{ asset('assets_callnchat/images/right-banner-webcall-1.png') }}" alt="">
            </div>
        </div>
    </div>
</section>

<section class="left-feature-webcall">
    <div class="split-box">
        <div class="left-box">
            <div class="banner">
                <img data-original="{{ asset('assets_callnchat/images/left-banner-webcall-2.png') }}" alt="">
            </div>
        </div>
        <div class="right-box">
            <div class="content" style="margin-top: 5rem">
                <div class="title">
                    <h1>@lang('callnchat.left_2.header_1') <span style="color: #FBC236">@lang('callnchat.left_2.header_2')</span></h1>
                </div>
                <div class="desc">
                    <h1>@lang('callnchat.left_2.desc')</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="right-feature-webcall">
    <div class="split-box">
        <div class="left-box">
            <div class="content">
                <div class="title">
                    <h1>@lang('callnchat.right_2.header_1') <span style="color: #FBC236">@lang('callnchat.right_2.header_2')</span></h1>
                </div>
                <div class="desc">
                    <h1>@lang('callnchat.right_2.desc')</h1>
                </div>
            </div>
        </div>
        <div class="right-box" style="margin-bottom: 8rem">
            <div class="banner">
                @if(session()->get('locale') == 'id')
                <img data-original="{{ asset('assets_callnchat/images/right-banner-webcall-2.png') }}" alt="">
                @elseif(session()->get('locale') == 'en')
                <img data-original="{{ asset('assets_callnchat/images/right-banner-webcall-2-en.png') }}" alt="">
                @else
                <img data-original="{{ asset('assets_callnchat/images/right-banner-webcall-2-en.png') }}" alt="">
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
@parent
<script src="{{ URL::asset('assets_callnchat/carousel.js') }}"></script>
@endsection