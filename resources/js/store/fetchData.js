import { notify } from "@kyvg/vue3-notification";

async function sendRequest(url, method = 'GET', params = null) {
    console.log(url);
    let fetchBody = null;
    if(params) {
      fetchBody = JSON.stringify(params);
    }

    return await fetch(url, {
      body: fetchBody,
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": window.csrf_token,
      },
      method: method,
    })
    .then((r) => r.json())
    .then(function(result) {
      console.log(result);

      if(result.error) {
        handleError(result);
        return null;
      }

      return result;
    }).catch(function(error) {
      handleError(error);
    });
}

export async function getCurrentWeek() {
  return await sendRequest('/api/weeks/current');
}

export async function generateFixtures() {
  return await sendRequest('/api/matches/generate', 'POST');
}

export async function generateTeams() {
  return await sendRequest('/api/teams/generate', 'POST');
}

export async function getFixturesCount() {
  return await sendRequest('/api/matches/count');
}

export async function getLeagueTeams() {
    return await sendRequest('/api/teams');
}

export async function getLeagueWeeks() {
  return await sendRequest('/api/weeks');
}

export async function getPredictionsTeams() {
  return await sendRequest('/api/teams/predictions');
}

export async function getStatsTeams() {
  return await sendRequest('/api/teams/stats');
}

export async function playCurrentWeek() {
  return await sendRequest('/api/weeks/play', 'POST');
}

export async function playAllWeeks() {
  const params = { all: true };

  return await sendRequest('/api/weeks/play', 'POST', params);
}

export async function resetAllWeeks() {
  return await sendRequest('/api/weeks/reset', 'POST');
}

export async function resetAllWeeksAndFixtures() {
  return await sendRequest('/api/weeks/reset-all', 'POST');
}

function handleError(error) {
  let text = 'Something went wrong';

  if(error.error) {
    text = error.error;
  }

  notify({
    group: 'error',
    title: 'Error',
    text: text,
    type: 'error',
  });
}