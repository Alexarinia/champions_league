<template>
    <div class="p-4" x-data="app">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-4 justify-center h-full">
            <league-stats />
            <league-week v-if="currentWeek" :week="currentWeek" />
            <league-predictions />
            Play all weeks
            Play next week
            Reset data
        </div>
    </div>
</template>

<script>
import LeaguePredictions from '@/components/Predictions/LeaguePredictions.vue';
import LeagueStats from '@/components/Stats/LeagueStats.vue';
import LeagueWeek from '@/components/Weeks/LeagueWeek.vue';
import { getCurrentWeek } from '@/store/fetchData';

export default {
    data() {
        return {
            currentWeek: null,
        };
    },
    components: {
        LeaguePredictions,
        LeagueStats,
        LeagueWeek
    },
    methods: {
        async loadCurrentWeek() {
            const week = await getCurrentWeek();

            this.currentWeek = week.data ?? null;
        }
    },
    mounted() {
        this.loadCurrentWeek();
    }
}
</script>