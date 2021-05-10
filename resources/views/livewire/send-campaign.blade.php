<div class="p-12">


    <!--
  This example requires Tailwind CSS v2.0+

  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ]
  }
  ```
-->
    <form wire:submit.prevent="send" class="space-y-8 divide-y divide-gray-200">
        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <div>
                @error('supportersUpload')
                <div class="inline-block bg-red-300 text-red-900 px-4 mb-8">{{ $message }}</div> @enderror
                @if (session()->has('message'))
                    <div class="inline-block bg-green-300 text-green-800 px-4 mb-8">
                        {{ session('message') }}
                    </div>
                @endif


                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Send Campaign
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Upload recipients and send postcards via one of the given campaigns.
                    </p>
                </div>

                <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="cover_photo" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Recipients
                        </label>

                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div
                                class="max-w-lg flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                         viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload"
                                               class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input type="file" wire:model="supportersUpload" id="file-upload"
                                                   name="file-upload" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        @if($supportersUpload)
                                            File ready:
                                            "{{  $supportersUpload->getClientOriginalName() ?? $supportersUpload->getFilename() }}
                                            "
                                        @else
                                            (CSV)
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5">
                        <label for="campaign" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Campaign
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <select id="campaign" wire:model="campaignClass"
                                    class="max-w-lg block focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md">

                                @foreach(config('postcards.campaigns') as $campaign)
                                    <option value="{{ $campaign['class'] }}">{{ $campaign['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end">
                <button wire:click="send"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save
                </button>
            </div>
        </div>
    </form>


</div>
