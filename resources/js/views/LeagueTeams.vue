<template class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
    <div class="flex flex-col justify-center h-full">
        <!-- Table -->
        <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
            <header class="px-5 py-4 border-b border-gray-100">
                <div class="font-semibold text-gray-800">Teams</div>
            </header>

            <div class="overflow-x-auto p-3">
                <table class="table-auto w-full" v-if="teams">
                    <tbody class="text-sm divide-y divide-gray-100">
                        <tr v-for="team in teams" :key="team.id">
                            <td class="p-2">
                                {{ team.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between space-x-4 px-5 py-4" v-if="teams">
                <router-link v-if="fixturesCount > 0" class="rounded bg-slate-400 py-2 px-4 text-white inline-block" :to="{ name: 'fixtures' }">Proceed to ready fixtures</router-link>
                <button v-if="fixturesCount === 0" class="rounded bg-green-700 py-2 px-4 text-white" type="button" @click="generateFixtures">
                    Generate fixtures
                </button>
            </div>
            <div class="flex justify-between space-x-4 px-5 py-4">
                <button v-if="fixturesCount === 0" class="rounded bg-blue-700 py-2 px-4 text-white" type="button" @click="generateTeams">
                    Generate teams
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { generateFixtures, generateTeams, getFixturesCount, getLeagueTeams } from '@/store/fetchData';

export default {
    data() {
        return {
            fixturesCount: null,
            loading: false,
            teams: null
        };
    },
    emits: {
        'is-loading': value => typeof value === 'boolean'
    },
    methods: {
        async generateFixtures() {
            this.loading = true;
            const fixturesCount = await generateFixtures();
            this.loading = false;

            if(fixturesCount) {
                this.$notify({
                    group: 'success',
                    title: 'Success',
                    text: `Generated ${fixturesCount} matches`,
                    type: 'success',
                });

                this.$router.push({ name: 'fixtures' });
            }
        },
        async generateTeams() {
            this.loading = true;
            const teamsCount = await generateTeams();
            this.loading = false;

            if(teamsCount) {
                this.$notify({
                    group: 'success',
                    title: 'Success',
                    text: `Generated ${teamsCount} teams`,
                    type: 'success',
                });

                this.loadTeams();
            }
        },
        isNumeric: function (n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },
        async loadFixturesCount() {
            this.loading = true;
            const fixturesCount = await getFixturesCount();
            this.loading = false;

            if(this.isNumeric(fixturesCount)) {
                this.fixturesCount = fixturesCount;
            }
        },
        async loadTeams() {
            this.loading = true;
            const teams = await getLeagueTeams();
            this.loading = false;

            if(teams.data && teams.data.length) {
                this.teams = teams.data ?? null;
            }
        }
    },
    mounted() {
        this.loadFixturesCount();
        this.loadTeams();
    },
    watch: {
        loading(val) {
            this.$emit('is-loading', val);
        }
    }
}
</script>