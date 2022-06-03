<template class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
    <div class="flex flex-col justify-center h-full">
        <!-- Table -->
        <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
            <header class="px-5 py-4 border-b border-gray-100">
                <div class="font-semibold text-gray-800">Week {{ week.order }}</div>
            </header>

            <div class="overflow-x-auto p-3">
                <table class="table-auto w-full" v-if="week.matches.data">
                    <tbody class="text-sm divide-y divide-gray-100">
                        <tr v-for="match in week.matches.data" :key="match.id">
                            <td class="p-2">
                                {{ match.host.name }} {{ match.host.goals }} : {{ match.guest.goals }} {{ match.guest.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  name: 'LeagueWeek',
  props: {
    week: Object
  }
}
</script>