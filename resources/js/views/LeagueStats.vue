<template>
    <div class="p-4" x-data="app">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-4 justify-center items-start h-full">
            <league-stats :stats-loading="loading" />
            <league-week class="p-0" v-if="currentWeek" :week="currentWeek" />
            <league-predictions :stats-loading="loading" />
        </div>
        <div class="flex flex-row gap-4 justify-between mt-3">
            <button type="button" @click="proceedAllWeeks" class="rounded bg-green-700 py-2 px-4 text-white">
                Play all weeks
            </button>
            <button type="button" @click="proceedCurrentWeek" class="rounded bg-green-700 py-2 px-4 text-white">
                Play next week
            </button>
            <button type="button" @click="resetAllWeeks" class="rounded bg-red-700 py-2 px-4 text-white">
                Reset data
            </button>
            <button type="button" @click="resetAllWeeksAndFixtures" class="rounded bg-red-700 py-2 px-4 text-white">
                Reset data with fixtures
            </button>
        </div>
    </div>
</template>

<script>
import LeaguePredictions from '@/components/Predictions/LeaguePredictions.vue';
import LeagueStats from '@/components/Stats/LeagueStats.vue';
import LeagueWeek from '@/components/Weeks/LeagueWeek.vue';
import { getCurrentWeek, playAllWeeks, playCurrentWeek, resetAllWeeks, resetAllWeeksAndFixtures } from '@/store/fetchData';

export default {
    data() {
        return {
            currentWeek: null,
            loading: false
        };
    },
    components: {
        LeaguePredictions,
        LeagueStats,
        LeagueWeek
    },
    methods: {
        async loadCurrentWeek() {
            this.loading = true;
            const week = await getCurrentWeek();

            this.currentWeek = week.data ?? null;
            this.loading = false;
        },
        async proceedAllWeeks() {
            const matchesCount = await playAllWeeks();

            if(matchesCount) {
                this.$notify({
                    group: 'success',
                    title: 'Success',
                    text: `${matchesCount} matches was finished`,
                    type: 'success',
                });

                this.loadCurrentWeek();
            }
        },
        async proceedCurrentWeek() {
            const matchesCount = await playCurrentWeek();

            if(matchesCount) {
                this.$notify({
                    group: 'success',
                    title: 'Success',
                    text: `${matchesCount} matches was finished`,
                    type: 'success',
                });

                this.loadCurrentWeek();
            }
        },
        async resetAllWeeks() {
            const matchesCount = await resetAllWeeks();

            if(matchesCount) {
                this.$notify({
                    group: 'success',
                    title: 'Success',
                    text: `${matchesCount} matches was reset`,
                    type: 'success',
                });

                this.$router.push({ name: 'fixtures' });
            }
        },
        async resetAllWeeksAndFixtures() {
            const matchesCount = await resetAllWeeksAndFixtures();

            if(matchesCount) {
                this.$notify({
                    group: 'success',
                    title: 'Success',
                    text: `${matchesCount} matches was reset. Fixtures was deleted`,
                    type: 'success',
                });

                this.$router.push({ name: 'home' });
            }
        },
    },
    mounted() {
        this.loadCurrentWeek();
    }
}
</script>