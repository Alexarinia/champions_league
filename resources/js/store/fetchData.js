import { notify } from "@kyvg/vue3-notification";

async function sendRequest(url, method = 'GET', params = {}) {
    console.log(url);

    return await fetch(url, {
      method: method ,
      data: params
    }).then((r) => r.json())
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

export async function getLeagueTeams() {
    return await sendRequest('/api/teams');
}

export async function getLeagueWeeks() {
  return await sendRequest('/api/weeks');
}

export async function generateFixtures() {
  return await sendRequest('/api/matches/generate', 'POST');
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