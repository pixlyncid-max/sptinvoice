@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-7xl space-y-6">
    <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-slate-200/60">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-slate-200/60">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-slate-200/60">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
