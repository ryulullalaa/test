@if (session('error'))
    <script>
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
    })
    </script>
@endif
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home.index') }}">
                        <img class="rounded mx-auto d-block" src="{{ url('img/logo/mini.png') }}" alt="">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home.index')" :active="request()->routeIs('home.index')">
                        {{ __('홈') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('kpi.now')" :active="request()->routeIs('kpi.*')">
                        {{ __('집계') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('worship.index')" :active="request()->routeIs('worship.index')">
                        {{ __('출석관리') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('congregation.index')" :active="request()->routeIs('congregation.index')">
                        {{ __('재적관리') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex">
                <!-- 소속 및 직위 -->
                <div class="flex-shrink-0 flex items-center text-sm text-gray-600">
                    @if (Auth::user()->member->affiliation->group)
                        {{ Auth::user()->member->affiliation->parish }}교구 {{ Auth::user()->member->affiliation->team }}팀 {{ Auth::user()->member->affiliation->group }}그룹
                    @elseif (Auth::user()->member->affiliation->team)
                        {{ Auth::user()->member->affiliation->parish }}교구 {{ Auth::user()->member->affiliation->team }}팀
                    @elseif (Auth::user()->member->affiliation->parish)
                        {{ Auth::user()->member->affiliation->parish }}교구
                    @endif

                    @if (Auth::user()->member->grade_id)
                        @switch(Auth::user()->member->grade_id)
                            @case(1) 교역자 @break
                            @case(2) 행정부장 @break
                            @case(3) 실행위원 @break
                            @case(4) 총괄관리자 @break
                            @case(5) 회장단 @break
                            @case(6) 임원단총괄 @break
                            @case(7) 임원단서기 @break
                            @case(8) 교구임원단 @break
                            @case(9) 임원단 @break
                            @case(10) 팀장 @break
                            @case(11) 그룹장 @break
                            @default
                        @endswitch
                    @endif
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <form method="GET" action="{{ route('home.change-password') }}">
                                <x-dropdown-link :href="route('home.change-password')">
                                    비밀번호 변경
                                </x-dropdown-link>
                            </form>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home.index')" :active="request()->routeIs('home.index')">
                {{ __('홈') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kpi.now')" :active="request()->routeIs('kpi.*')">
                {{ __('집계') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('worship.index')" :active="request()->routeIs('worship.index')">
                {{ __('출석관리') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('congregation.index')" :active="request()->routeIs('congregation.index')">
                {{ __('재적관리') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="GET" action="{{ route('home.change-password') }}">
                    <x-dropdown-link :href="route('home.change-password')">
                        비밀번호 변경
                    </x-dropdown-link>
                </form>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
