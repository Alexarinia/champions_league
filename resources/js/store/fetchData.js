async function sendRequest(url, method = 'GET', params = {}) {
    console.log(url);
    return await fetch(url, {
      method: method ,
      data: params
    }).then((r) => r.json());
}

export async function getLeagueTeams() {
    return await sendRequest('/api/teams');
}

export async function getLeagueWeeks() {
  return await sendRequest('/api/weeks');
}
