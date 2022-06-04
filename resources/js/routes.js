import LeagueTeams from '@/views/LeagueTeams.vue';
import LeagueFixtures from '@/views/LeagueFixtures.vue';

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
];
