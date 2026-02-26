&nbsp;
<x-nav-link :href="route('kpi.now')" :active="request()->routeIs('kpi.now')">
    {{ __('실시간') }}
</x-nav-link>
<x-nav-link :href="route('kpi.absent')" :active="request()->routeIs('kpi.absent')">
    {{ __('결석') }}
</x-nav-link>
<x-nav-link :href="route('kpi.spirit')" :active="request()->routeIs('kpi.spirit')">
    {{ __('영성') }}
</x-nav-link>
<x-nav-link :href="route('kpi.report')" :active="request()->routeIs('kpi.report')">
    {{ __('주일보고') }}
</x-nav-link>
<x-nav-link :href="route('kpi.status')" :active="request()->routeIs('kpi.status')">
    {{ __('훈련통계') }}
</x-nav-link>
<x-nav-link :href="route('kpi.identity')" :active="request()->routeIs('kpi.identity')">
    {{ __('신분통계') }}
</x-nav-link>
<x-nav-link :href="route('kpi.inactive')" :active="request()->routeIs('kpi.inactive')">
    {{ __('비활동') }}
</x-nav-link>
<x-nav-link :href="route('kpi.notentered')" :active="request()->routeIs('kpi.notentered')">
    {{ __('미입력') }}
</x-nav-link>