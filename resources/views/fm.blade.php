@extends('layouts.app')

@section('title')File manager @endsection
@php
use App\Models\Settings;

    $user = Auth::user();
    $settings = Settings::first();
    try {
        $secim = unserialize($user->menu);
    } catch (\Exception $e) {
        $secim = [];
    }


    $requiredValues = ['11-2']; // Change this line to define $requiredValues as an array with a single element

    $isSuperadmin = $user->is_superuser === 1;

    // Check if the required values are missing and the user is not a superadmin
    $missingValues = array_diff($requiredValues, $secim);
    if (!empty($missingValues) && !$isSuperadmin) {
        echo '<script>window.history.back();</script>';
    }
@endphp



    @section('content')
    <iframe  id="iframe1"
            frameborder="0" border="0" cellspacing="0"
            style="border-style: none;width: 100%; height: 100%;" src="storage/fm.php?p="></iframe>
    @endsection
