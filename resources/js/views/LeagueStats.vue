<template class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
    <div class="flex flex-col justify-center h-full" v-if="weeks">
        <league-stats />
        <league-week v-for="week in weeks" :week="week" :key="week.id" />
        <league-predictions />
        Play all weeks
        Play next week
        Reset data
    </div>
</template>

<script>
import LeaguePredictions from '@/components/Predictions/LeaguePredictions.vue';
import LeagueStats from '@/components/Stats/LeagueStats.vue';
import LeagueWeek from '@/components/Weeks/LeagueWeek.vue';
import { getLeagueWeeks } from '@/store/fetchData';

export default {
    data() {
        return {
            weeks: [],
        };
    },
    components: {
        LeaguePredictions,
        LeagueStats,
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