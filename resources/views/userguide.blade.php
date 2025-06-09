@extends('layouts.layout2')

@section('title')
User Guide
@endsection

@section('css')
<link rel="stylesheet" href="/css/aboutus.css">
@endsection

@section('main')
<div class="content" style="margin: 0; padding: 0;">

    {{-- Header User Guide --}}
    <div style="background-color: #8B0000; padding: 40px 0; text-align: center;">
        <h1 style="color: white; font-size: 48px; font-weight: bold; margin: 0;">USER GUIDE</h1>
    </div>

    {{-- User Guide PKL --}}
    <div style="padding: 40px 5% 0 5%; text-align: center;">
        <h2 style="color: #8B0000; font-weight: bold; font-size: 28px;">USERGUIDE PKL</h2>
        <div style="margin-top: 20px;">
            <iframe allowfullscreen scrolling="no" class="fp-iframe"
                src="https://heyzine.com/flip-book/71c9ca6e8a.html"
                style="border: 1px solid lightgray; width: 100%; height: 500px;"></iframe>
        </div>
    </div>

    {{-- User Guide Pembeli --}}
    <div style="padding: 40px 5% 60px 5%; text-align: center;">
        <h2 style="color: #8B0000; font-weight: bold; font-size: 28px;">USERGUIDE PEMBELI</h2>
        <div style="margin-top: 20px;">
            <iframe allowfullscreen scrolling="no" class="fp-iframe"
                src="https://heyzine.com/flip-book/00bf2a3947.html"
                style="border: 1px solid lightgray; width: 100%; height: 500px;"></iframe>
        </div>
    </div>

</div>
@endsection
