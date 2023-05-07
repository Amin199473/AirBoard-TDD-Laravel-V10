@extends('layouts.app')

@section('content')

    <body>
        <div class="container mt-6">
            <div class="flex flex-row justify-center">
                <div>
                    <div class="flex justify-between items-end my-10">
                        <div>
                            <h1 class="text-white text-2xl">My Projects</h1>
                        </div>
                        <div>
                            <a href="{{ route('projects.create') }}"
                                class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-lg px-5 py-2.5">
                                Add
                                Project
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        @forelse ($projects as $project)
                            <div
                                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <svg class="w-10 h-10 mb-2 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z"
                                        clip-rule="evenodd"></path>
                                    <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"></path>
                                </svg>
                                <a href="{{ $project->path() }}">
                                    <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                        {{ $project->title }}
                                    </h5>
                                </a>
                                <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                    {{ $project->description }}
                                </p>
                                @can('mange',$project)
                                <form action="{{ $project->path() }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="text-right">
                                        <button type="submit"
                                            class="text-center focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                                    </div>
                                </form>
                                @endcan
                            </div>
                        @empty
                            <h1>There is not project</h1>
                        @endforelse
                    </div>
                </div>
                {{-- <div class="basis-1/4 text-white">02</div> --}}
            </div>
        </div>
    </body>
@endsection
