<template class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
    <div class="flex flex-col justify-center h-full" v-if="weeks">
        <league-week v-for="week in weeks" :week="week" :key="week.id" />
        <router-link :to="{ name: 'week-stats' }">Start Simulation</router-link>
    </div>
</template>

<script>
import LeagueWeek from '@/components/Weeks/LeagueWeek.vue';
import { getLeagueWeeks } from '@/store/fetchData';

export default {
    data() {
        return {
            weeks: [],
        };
    },
    components: {
        LeagueWeek
    },
    methods: {
        async loadWeeks() {
            const weeks = await getLeagueWeeks();
            this.weeks = weeks.data ?? [];
        }
    },
    mounted() {
        this.loadWeeks();
    }
}
</script>