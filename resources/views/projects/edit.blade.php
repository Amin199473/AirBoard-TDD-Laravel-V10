@extends('layouts.app')



@section('content')

    <body>
        <h1 class="text-4xl mx-8 my-7 text-white">Edit Project</h1>
        <div class="container mx-auto my-4">
            <div
                class="block mx-8 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <form method="post" action="{{ route('project.update', ['project'=>$project->id]) }}">
                    @csrf
                    @method('patch')
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" value="{{ $project->title }}" name="title" id="floating_email"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" />
                        <label for="floating_email"
                            class="peer-focus:font-medium absolute text-2xl text-gray-500 dark:text-gray-100 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                            Description</label>
                        <textarea id="message" name="description" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Leave a Description...">{{ $project->description }}</textarea>
                    </div>
                    <div class="flex flex-row">
                        <button type="submit"
                            class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Update
                            Project</button>
                        <a href="{{ route('projects') }} "
                            class="text-white ml-2 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </body>
@endsection
