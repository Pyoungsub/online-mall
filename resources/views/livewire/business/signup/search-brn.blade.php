<div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <div 
            x-data="{
                brn:@entangle('brn'),
                remaining_seconds:@entangle('remaining_seconds'),
                due_time:0,
                displaying_seconds:0,
                timer:false,
                calculateRemaingTime()
                {
                    this.displaying_seconds = this.due_time - DateTime.now().toUnixInteger();
                },
                interval()
                {
                    if(this.displaying_seconds > 0)
                    {
                        this.timer = setInterval(() => { 
                            this.calculateRemaingTime();
                        }, 1000 );
                    };
                },
                init()
                {
                    if(this.remaining_seconds > 0)
                    {
                        this.displaying_seconds = this.remaining_seconds;
                        this.due_time = DateTime.now().plus({seconds: this.remaining_seconds}).toFormat('X');
                        this.interval();
                    }
                },
                brn_error_message:@entangle('brn_error_message'),
            }"
            x-init="
                $watch('remaining_seconds', value => {
                    displaying_seconds = value;
                    due_time = DateTime.now().plus({seconds: value}).toFormat('X');
                    interval();
                });
                $watch('displaying_seconds', value => {
                    if(value <= 0){
                        clearInterval(timer);
                    }
                });
                $watch('brn', value => {
                    if(value)
                    {
                        brn_error_message = '';
                    }
                });
            "
            class="mt-4"
        >
            <h1 class="font-bold text-2xl">{{ __('사업자 등록 번호 조회') }}</h1>
            <x-label for="name" value="{{ __('사업자 등록 번호 조회') }}" />
            <form wire:submit="check">
                <x-input 
                    x-mask="999-99-99999" 
                    placeholder="000-00-00000" 
                    id="brn" 
                    x-model="brn"
                    wire:model="brn"
                    class="mt-1 block w-full" 
                    type="text" 
                    name="brn" 
                    :value="old('brn')" 
                    required 
                    autofocus 
                    autocomplete="brn"
                    x-bind:disabled="displaying_seconds > 0"
                />
                <x-input-error for="brn" class="mt-2" />
                <p class="mt-2 text-sm text-red-700 text-end" x-show="displaying_seconds"><span x-text="displaying_seconds"></span>{{__('초 후에 다시 시도해 주세요.')}}</p>
                <p class="mt-2 text-sm text-red-700 text-end" x-text="brn_error_message"></p>
                <x-submit-button type="submit" x-bind:disabled="displaying_seconds > 0">{{ __('조회') }}</x-submit-button>
            </form>
        </div>
    </div>
</div>
