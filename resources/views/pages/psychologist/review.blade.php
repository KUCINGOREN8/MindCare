@extends('layouts.dashboard')

@section('title')
{{ $psychologist->full_name }} Reviews
@endsection

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-lg">{{$psychologist->full_name}}</h1>
                    <h5 class="text-captiondark text-sm">Review Page</h5>
                </div>
                <button onclick="window.history.back()" type="submit" class="px-4 py-2 bg-primary text-white rounded-md">Back</button>            </div>
            </div>
        </div>
    </div>
@endsection