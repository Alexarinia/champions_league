import LeagueTeams from './components/LeagueTeams.vue';
import LeagueFixtures from './components/Fixtures/LeagueFixtures.vue';

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
