<template>
    <div class="p-4" v-if="week">
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
                                <td class="p-2 flex flex-row items-center">
                                    <div class="text-xs w-2/5 text-right">{{ match.host.name }} </div>
                                    <div class="w-[60px] text-center">
                                        <span class="font-semibold ml-3">{{ match.finished ? match.host.goals : '' }}</span>
                                        <span> - </span>
                                        <span class="font-semibold mr-3">{{ match.finished ? match.guest.goals : '' }}</span>
                                    </div>
                                    <div class="text-xs w-2/5 text-left"> {{ match.guest.name }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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