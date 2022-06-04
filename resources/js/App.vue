<template>
    <div class="container m-auto">
        <div class="text-center" style="margin: 20px 0px 20px 0px;">
            <span class="text-secondary">Insider Champions League</span>
        </div>
        <div class="antialiased bg-gray-100 text-gray-600">
            <router-view></router-view>
        </div>
        <notifications group="error" />
        <notifications group="success" />
    </div>
</template>

<script>

export default {}
</script>