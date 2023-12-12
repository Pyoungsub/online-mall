<div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <div class="mt-4">
            <h1 class="font-bold text-2xl">{{ __('매출 정산 관리') }}</h1>
            <p class="block font-medium text-sm text-gray-700 dark:text-gray-300">매출, 정산, 계약 등 상점과 관련된 모든 정보를 확인하세요</p>
            <button class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0"/></svg>
            </button>
        </div>
    </div>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <div class="mt-4">
            <h1 class="font-bold text-2xl">{{ __('입점 준비 중') }}</h1>
            <p class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{__('작성 중이거나 심사를 기다리는 신청서는 여기에 있어요')}}</p>
            <button class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0"/></svg>
            </button>
        </div>
    </div>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <div class="mt-4">
            <h1 class="font-bold text-2xl">{{ __('판매자 계정 만들기') }}</h1>
            <p class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{__('사업자라면 바로 만들어 보세요!')}}</p>
            <a class="text-center hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm block mt-1 w-full p-2" href="{{route('business.signup.search')}}">
                {{ __('판매자 계정 생성') }}
            </a>
        </div>
    </div>
</div>