&nbsp;
<x-nav-link :href="route('worship.group')" :active="request()->routeIs('worship.group')">
    {{ __('그룹') }}
</x-nav-link>
<x-nav-link :href="route('worship.committee')" :active="request()->routeIs('worship.committee')">
    {{ __('임원단') }}
</x-nav-link>
<x-nav-link :href="route('worship.executive')" :active="request()->routeIs('worship.executive')">
    {{ __('실행위원') }}
</x-nav-link>