<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Successfully logged in!") }}<br>
                    <span id="redirectMessage">5 seconds later, you will be redirected to the home page...</span>
                </div>
            </div>
        </div>
    </div>

    <p>{{ session('success') }}</p>

    <script>
        // 设置倒计时的时间（以秒为单位）
        let seconds = 5;
        
        // 设置每秒执行一次的定时器
        const countdown = setInterval(() => {
            seconds--;
            document.getElementById('redirectMessage').textContent = seconds + " seconds later, you will be redirected to the home page...";
            
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = "{{ route('index') }}"; // 首页路由名称
            }
        }, 1000);

    </script>

</x-app-layout>
