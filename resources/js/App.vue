<template>
    <div class="container">
        <div class="text-center" style="margin: 20px 0px 20px 0px;">
            <span class="text-secondary">Insider Champions League</span>
        </div>
        <league-teams></league-teams>
    </div>
</template>

<script>
import LeagueTeams from './components/LeagueTeams';

export default {
    components: {
        LeagueTeams
    }
}
</script>