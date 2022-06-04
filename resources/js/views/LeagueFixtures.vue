<template>
    <div class="p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 justify-center h-full" v-if="weeks">
            <league-week v-for="week in weeks" :week="week" :key="week.id" />
        </div>
        <div class="flex flex-row justify-center">
            <router-link class="rounded bg-slate-400 py-2 px-4 text-white" :to="{ name: 'week-stats' }">Start Simulation</router-link>
        </div>
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