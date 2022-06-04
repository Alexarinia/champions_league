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

            <div class="flex justify-end font-bold space-x-4 text-2xl border-t border-gray-100 px-5 py-4">
                <div>
                    <router-link :to="{ name: 'fixtures' }">Proceed to ready fixtures</router-link>
                    <button type="button" @click="generateFixtures">
                        Generate fixtures
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { generateFixtures, getLeagueTeams } from '@/store/fetchData';

export default {
    data() {
        return {
            teams: []
        };
    },
    methods: {
        async generateFixtures() {
            const fixturesCount = await generateFixtures();
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
        async loadTeams() {
            const teams = await getLeagueTeams();
            this.teams = teams.data ?? [];
        }
    },
    mounted() {
        this.loadTeams();
    }
}
</script>