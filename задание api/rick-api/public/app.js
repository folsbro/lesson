const API_URL = "/api/characters";

async function loadCharacters() {
  const res = await fetch(API_URL);
  const characters = await res.json();

  const container = document.getElementById("characters");
  container.innerHTML = "";

  characters.forEach(char => {
    const div = document.createElement("div");
    div.className = "card";
    div.innerHTML = `
      <img src="${char.image}" alt="${char.name}">
      <h3>${char.name}</h3>
      <p>${char.status} - ${char.species}</p>
      <button class="delete" onclick="deleteChar(${char.id})">Delete</button>
    `;
    container.appendChild(div);
  });
}

async function deleteChar(id) {
  await fetch(`${API_URL}/${id}`, { method: "DELETE" });
  loadCharacters();
}

document.getElementById("characterForm").addEventListener("submit", async e => {
  e.preventDefault();
  const form = e.target;
  const data = {
    name: form.name.value,
    status: form.status.value,
    species: form.species.value,
    location_id: parseInt(form.location_id.value),
    episode_ids: [],
    image: form.image.value
  };

  await fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  form.reset();
  loadCharacters();
});

loadCharacters();

