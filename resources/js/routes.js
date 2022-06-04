import LeagueTeams from '@/views/LeagueTeams.vue';
import LeagueFixtures from '@/views/LeagueFixtures.vue';
import LeagueStats from '@/views/LeagueStats.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: LeagueTeams
    },
    {
        name: 'fixtures',
        path: '/fixtures',
        component: LeagueFixtures
    },
    {
        name: 'week-stats',
        path: '/week-stats',
        component: LeagueStats
    },
];
