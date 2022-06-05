<template class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
    <div class="flex flex-col justify-center h-full">
        <!-- Table -->
        <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
            <header class="px-5 py-4 border-b border-gray-100">
                <div class="font-semibold text-gray-800">Stats</div>
            </header>

            <div class="overflow-x-auto p-3">
                <table class="table-auto w-full" v-if="teams">
                    <thead>
                        <tr>
                            <th class="text-left">Team Name</th>
                            <th>Pts</th>
                            <th>P</th>
                            <th>W</th>
                            <th>D</th>
                            <th>L</th>
                            <th>GD</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <tr v-for="team in orderedTeams" :key="team.id">
                            <td class="p-2 text-xs">
                                {{ team.name }}
                            </td>
                            <td class="text-center">{{ team.stats.points }}</td>
                            <td class="text-center">{{ team.stats.played }}</td>
                            <td class="text-center">{{ team.stats.won }}</td>
                            <td class="text-center">{{ team.stats.draw }}</td>
                            <td class="text-center">{{ team.stats.lost }}</td>
                            <td class="text-center">{{ team.stats.goal_difference }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { getStatsTeams } from '@/store/fetchData';

export default {
    name: 'LeagueStats',
    props: {
        statsLoading: Boolean
    },
    data() {
        return {
            teams: []
        };
    },
    computed: {
        orderedTeams: function () {
            this.teams = _.orderBy(this.teams, 'stats.goal_difference', 'desc');

            return _.orderBy(this.teams, 'stats.points', 'desc');
        }
    },
    methods: {
        async loadStatTeams() {
            const teams = await getStatsTeams();
            this.teams = teams.data ?? [];
        }
    },
    mounted() {
        this.loadStatTeams();
    },
    watch: {
        statsLoading(val) {
            if(! val) {
                this.loadStatTeams();
            }
        },
    }
}
</script>