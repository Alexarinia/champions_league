<template>
    <div class="p-4" x-data="app">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-4 justify-center items-start h-full">
            <league-stats :stats-loading="loading" />
            <league-week class="p-0" v-if="currentWeek" :week="currentWeek" />
            <league-predictions :stats-loading="loading" />
        </div>
        <div class="flex flex-row gap-4 justify-between mt-3">
            <router-link class="rounded bg-slate-400 py-2 px-4 text-white inline-block" :to="{ name: 'fixtures' }">
                Show fixtures
            </router-link>
            <button type="button" 
                    v-if="currentWeek" 
                    @click="proceedAllWeeks"
                    :disabled="currentWeek.finished"
                    class="rounded bg-green-700 py-2 px-4 text-white disabled:opacity-75">
                Play all weeks
            </button>
            <button type="button"
                    v-if="currentWeek"
                    @click="proceedCurrentWeek"
                    :disabled="currentWeek.finished"
                    class="rounded bg-green-700 py-2 px-4 text-white disabled:opacity-75">
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
    emits: {
        'is-loading': value => typeof value === 'boolean'
    },
    methods: {
        async loadCurrentWeek() {
            this.loading = true;
            const week = await getCurrentWeek();

            if(week && week.data) {
                this.currentWeek = week.data ?? null;
            }

            this.loading = false;
            if(! this.currentWeek) {
                this.$router.push({ name: 'home' });
            }
        },
        async proceedAllWeeks() {
            this.loading = true;
            const matchesCount = await playAllWeeks();
            this.loading = false;

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
            this.loading = true;
            const matchesCount = await playCurrentWeek();
            this.loading = false;

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
            this.loading = true;
            const matchesCount = await resetAllWeeks();
            this.loading = false;

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
            this.loading = true;
            const matchesCount = await resetAllWeeksAndFixtures();
            this.loading = false;

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
    },
    watch: {
        loading(val) {
            this.$emit('is-loading', val);
        }
    }
}
</script>