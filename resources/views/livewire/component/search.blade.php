<div class="relative">
    <x-input-dropdown align="none" dropdownClasses="">
        <x-slot name="trigger">
            <button>
                <svg class="stroke-black dark:stroke-white" :class="open ? 'stroke-2' : 'stroke-1'" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
        </x-slot>
        <x-slot name="content">
            <div 
                class="p-2 max-w-sm"
                x-data="{search:''}"
                x-init="
                    $watch('open', value => {
                        if(!value){search = ''}
                    });
                "
            >
                <div class="">
                    <input x-model="search" type="text">
                </div>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>
            </div>
        </x-slot>
    </x-input-dropdown>
</div>