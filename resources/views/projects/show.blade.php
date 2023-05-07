@extends('layouts.app')

@section('content')

    <body>
        <main class="mx-12 mt-12">
            <div class="flex justify-between gap-4 items-end mb-8">
                <div>
                    <h1 class="text-white text-2xl">
                        <a href="{{ route('projects.index') }}" class="hover:text-red-700">My Projects</a> /
                        {{ $project->title }}
                    </h1>
                </div>
                <div class="flex items-end">

                    <div class="flex flex-row mr-2">
                        @foreach ($project->members as $member)
                            <img src="https://gravatar.com/avatar/{{ $member->email }}?s=60"
                                alt="{{ $member->name }}'s avatar" class="rounded-full w-8 mr-2">
                        @endforeach
                        <img src="https://gravatar.com/avatar/{{ $project->owner->email }}?s=60"
                            alt="{{ $project->owner->name }}'s avatar" class="rounded-full w-8 mr-2">
                    </div>
                    <a href="{{ route('projects.edit', $project->id) }}"
                        class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-lg px-5 py-2.5">
                        Edit Project
                    </a>
                </div>
            </div>
            <h1 class="text-gray-400 text-lg mb-3">Tasks</h1>
            <div class="flex flex-row items-start gap-4">
                <div class="basis-3/4">
                    <!-- start Card section -->
                    <div class="mb-12">
                        @foreach ($project->tasks as $task)
                            <div
                                class="mb-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <form class="block" method="POST" action="{{ $task->path() }}">
                                    @csrf
                                    @method('patch')
                                    <div class="flex justify-between items-center m-4 gap-3">
                                        <input type="text" name="body" value="{{ $task->body }}"
                                            class="{{ $task->completed ? 'text-green-400' : '' }} text-white bg-gray-50 border border-gray-300  text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 "
                                            placeholder="begin adding tasks Here ...">

                                        <input onchange="this.form.submit()" name="completed" type="checkbox"
                                            value="{{ $task->completed }}" {{ $task->completed ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                </form>
                            </div>
                        @endforeach
                        <div
                            class="mt-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <form class="block" method="POST" action="{{ $project->path() . '/tasks' }}">
                                @csrf
                                @method('POST')
                                <div>
                                    <input type="text" name="body"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="begin adding tasks Here ...">
                                    @error('body')
                                        <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--End Card section -->

                    <!--Start Card section General Notes-->
                    <div class="">
                        <h1 class="text-lg mb-2 text-gray-400">General notes</h1>
                        <div
                            class="p-3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <form method="POST" action="{{ $project->path() }}">
                                @csrf
                                @method('patch')
                                <textarea id="message" rows="4" name="notes"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Write your Notes here...">{{ $project->notes }}</textarea>
                                @error('notes')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                                <button type="submit"
                                    class="text-white mt-3 bg-blue-700 hover:bg-blue-800 focus:ring-4  font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600">Save</button>
                            </form>
                        </div>
                    </div>
                    <!--End Card section -->
                </div>
                <div class="basis-1/4">
                    <div
                        class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">

                        <a href="{{ $project->path() }}">
                            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                {{ $project->title }}
                            </h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                            {{ $project->description }}
                        </p>

                        <form action="{{ $project->path() }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="text-right -mr-4 -mb-6">
                                <button type="submit"
                                    class="text-center focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                            </div>
                        </form>
                    </div>

                    <div
                        class="max-w-sm mt-3 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            Activities
                        </h5>
                        @foreach ($activites as $act)
                            <ul>
                                <li class="text-white hover:text-red-400 text-xs ">
                                    {{ $act->user->name }}-{{ $act->description }} - <span
                                        class="text-blue-400">{{ $act->created_at->shortRelativeDiffForHumans() }}</span>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                    @if (auth()->user()->is($project->owner))
                        <div
                            class="max-w-sm mt-3 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                Invite Member
                            </h5>
                            <input type="email" name="email" value=""
                                class="text-white mb-3 bg-gray-50 border border-gray-300  text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 "
                                placeholder="Enter Email Address ...">
                            @error('email')
                                <div class="text-red-500 mb-2">{{ $message }}</div>
                            @enderror
                            <form action="{{ $project->path() . '/invitations' }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="-mb-6">
                                    <button type="submit"
                                        class="text-center focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Invite</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center mt-10">
                <i class="fa-solid fa-arrow-left fa-xl text-white"></i>
                <a class="text-white text-xl hover:text-blue-500" href="{{ route('projects.index') }}">Go Back</a>
            </div>
        </main>
    </body>

    </html>
@endsection
