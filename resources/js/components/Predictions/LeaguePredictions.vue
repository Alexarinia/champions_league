<template class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
    <div class="flex flex-col justify-center h-full">
        <!-- Table -->
        <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
            <header class="px-5 py-4 border-b border-gray-100">
                <div class="font-semibold text-gray-800">Predictions</div>
            </header>
            <div class="overflow-x-auto p-3">
                <table class="table-auto w-full" v-if="teams">
                    <thead>
                        <tr>
                            <th class="text-left">Championship Predictions</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <tr v-for="team in orderedTeams" :key="team.id">
                            <td class="p-2 text-xs">
                                {{ team.name }}
                            </td>
                            <td>{{ team.prediction.toFixed(0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { getPredictionsTeams } from '@/store/fetchData';

export default {
    name: 'LeaguePredictions',
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
            this.teams = _.orderBy(this.teams, 'stats.points', 'desc');

            return _.orderBy(this.teams, 'stats.prediction', 'desc');
        }
    },
    methods: {
        async loadStatTeams() {
            const teams = await getPredictionsTeams();
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