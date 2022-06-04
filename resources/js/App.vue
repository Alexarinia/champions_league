<template>
    <div class="container m-auto">
        <div class="text-center" style="margin: 20px 0px 20px 0px;">
            <span class="text-secondary">Insider Champions League</span>
        </div>
        <router-view></router-view>
        <notifications group="error" />
        <notifications group="success" />
    </div>
</template>

<script>

export default {}
</script>