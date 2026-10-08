@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full">
    @include('sections.hero')
    @include('sections.stats')
    @include('sections.about')
    @include('sections.alumni-directory')
    @include('sections.business-network')
    @include('sections.programs')
    @include('sections.news-gallery')
    @include('sections.registration-cta')
    @include('sections.contact-hub')
</div>
@endsection
