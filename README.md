# Champions League Simulation

## Install
Clone project. Go to a project folder. Copy file `.env.example`, rename copy to `.env` and fill database settings if you wish. Run in terminal
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
Then
```
First start may take a while. Then run
```
./vendor/bin/sail composer install
./vendor/bin/sail npm i
./vendor/bin/sail npm run prod
./vendor/bin/sail artisan migrate
```
Open your browser and paste [http://localhost/](http://localhost/). Enjoy game simulation!

To quit run in terminal
```
./vendor/bin/sail down
```

## Task  
Complete a simulation. In this simulation, there will be a group of football teams and the simulation will show match results and the league table. Task is to estimate the final league table.  
There will be four teams in the league (teams have different strengths and the results of the matches are determined depending on the strengths of these selected teams).  
Other rules in the league (scoring, points, goal difference, etc.) will be the same as the rules of the Premier League.  

### Completion  
I've used Laravel 9 with Sail as Docker environment. MySQL was chosen as database, Vue3.js - as a front-end.  
Almost all backend/frontend connections go via API endpoints. Major routes are located in `routes/api.php`.  
Due to small size of project I didn't use Vuex and stayed for a few components with emitted events and common file with fetch functions.  
I've build some complex formulas for simulation, but they are too simple to use in real life:  
- Teams generation take place in TeamFactory, team power randomly sets from 10 to 100.  
- Fixture generation meets teams with each other and doesn't repeat pairs. If it comes to a dead end (left only teams that has already played with each other) all week fixtures are regenerating.  
- Match score is based on team strengh and is counting with probabilities of result.  
- Predictions are based on team earned points and history of playing in current league.  
Predictions and stats are refreshing after week playing. There is a possibility to create up to 20 unique teams. You can edit teams in `config/insider.php` file.   

## Utilities used  
[Laravel 9](https://laravel.com/docs/9.x) - Laravel 9 backend framework  
[Vue.js](https://vuejs.org/) - Vue 3 framework  
[Tailwind](https://tailwindcss.com/) - CSS-framework  
[Vue.js notifications](https://bestofvue.com/repo/kyvg-vue3-notification-vuejs-notification) - pop-up notifications   
[Tailwind component](https://tailwindcomponents.com/component/manage-product-cart) - cards Tailwind ready component  

## ToDo List  
- [ ] Increase speed of generating. I've used Eloquent, but it created some recursions which slower the performance. Maybe raw database queries would make sitiation much better.  
- [ ] Write automated tests. Due to limited time I've sacrificed them for a wider functionality. But tests are vital for stability of the system.  
- [ ] More validation cases. Now only a little part of data goes through validation.  
- [ ] Better probability formulas.  
- [ ] More beutiful consistent design with good UX.  
- [ ] Extend error handling.  
- [ ] Rare browser error `ERR_EMPTY_RESPONSE` after click "Generate fixtures".  
- [ ] Show something meaningful in Predictions section after all weeks were played.  
- [ ] etc...

