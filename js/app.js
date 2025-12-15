// =====================
// INITIALISATION SUPABASE
// =====================
const SUPABASE_URL = "https://ceywdsayqzvibvemrlex.supabase.co";
const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImNleXdkc2F5cXp2aWJ2ZW1ybGV4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3NTk1NTgsImV4cCI6MjA3OTMzNTU1OH0.-WzAmW8b1QK4vxFQJKt8S7Suh8snwjJNKtWLzk9-ako";
const supabase = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

// =====================
// VERIFIER SI CONNECTÉ ET ADMIN
// =====================
async function checkAdmin() {
  const user = supabase.auth.user();
  if (!user) return false;

  const { data: profile, error } = await supabase
    .from("profiles")
    .select("role")
    .eq("id", user.id)
    .single();

  if (error) {
    console.error(error);
    return false;
  }

  return profile.role === "admin";
}

// =====================
// LOGIN
// =====================
async function login(email, password) {
  const { data, error } = await supabase.auth.signIn({ email, password });
  if (error) {
    alert("Erreur connexion : " + error.message);
    return;
  }
  alert("Connecté !");
  initPage();
  
  const isAdmin = await checkAdmin();
  if (isAdmin) window.location.href = "admin.html";
  else window.location.href = "index.html";
}

// =====================
// LOGOUT
// =====================
async function logout() {
  const { error } = await supabase.auth.signOut();
  if (error) console.error(error);
  alert("Déconnecté !");
  updateAuthUI();
}

// =====================
// UPDATE UI AUTH
// =====================
function updateAuthUI() {
  const user = supabase.auth.user();
  document.getElementById("login-btn").style.display = user ? "none" : "inline-block";
  document.getElementById("logout-btn").style.display = user ? "inline-block" : "none";
}

// =====================
// LIRE LES EVENTS
// =====================
async function loadEvents() {
  const { data: events, error } = await supabase
    .from("events")
    .select("*")
    .order("date", { ascending: true });

  if (error) {
    console.error(error);
    return;
  }

  const eventsContainer = document.getElementById("events-list");
  if (!eventsContainer) return;

  eventsContainer.innerHTML = "";
  events.forEach(event => {
    const div = document.createElement("div");
    div.classList.add("event-item");
    div.innerHTML = `<strong>${event.title}</strong> - ${event.date}<br>${event.description || ""}`;
    eventsContainer.appendChild(div);
  });
}

// =====================
// AJOUTER UN EVENT (ADMIN)
// =====================
async function addEvent(title, date, description = "") {
  const user = supabase.auth.user();
  if (!user) return alert("Tu dois être connecté");

  const isAdmin = await checkAdmin();
  if (!isAdmin) return alert("Tu n'es pas admin");

  const { data, error } = await supabase
    .from("events")
    .insert([{ title, date, description, created_by: user.id }]);

  if (error) {
    console.error(error);
    alert("Erreur lors de l'ajout de l'événement");
    return;
  }

  alert("Événement ajouté !");
  loadEvents();
}

// =====================
// CREER UN ADHERENT (ADMIN)
// =====================
async function createAdherent(email, password) {
  // Requête vers backend sécurisé (PHP / Node / Edge Function)
  const res = await fetch("backend.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password })
  });
  const data = await res.json();
  if (data.error) alert("Erreur : " + data.error);
  else alert("Adhérent créé !");
}

// =====================
// GESTION FORMULAIRES
// =====================
document.addEventListener("DOMContentLoaded", () => {
  // Login
  document.getElementById("login-btn").addEventListener("click", () => {
    const email = document.getElementById("auth-email").value;
    const password = document.getElementById("auth-password").value;
    login(email, password);
  });

  // Logout
  document.getElementById("logout-btn").addEventListener("click", logout);

  // Ajouter un event
  const eventForm = document.getElementById("add-event-form");
  if (eventForm) eventForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const title = document.getElementById("event-title").value.trim();
    const date = document.getElementById("event-date").value;
    const desc = document.getElementById("event-desc").value.trim();
    if (!title || !date) return alert("Titre et date obligatoires");
    await addEvent(title, date, desc);
    eventForm.reset();
  });

  // Créer un adhérent
  const createForm = document.getElementById("create-user-form");
  if (createForm) createForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const email = document.getElementById("new-email").value;
    const password = document.getElementById("new-password").value;
    await createAdherent(email, password);
    createForm.reset();
  });

  // Initialisation page
  initPage();
});

// =====================
// INITIALISATION PAGE
// =====================
async function initPage() {
  updateAuthUI();
  await loadEvents();

  const isAdmin = await checkAdmin();

  // Affichage formulaires admin
  const addEventContainer = document.getElementById("add-event-container");
  const createUserContainer = document.getElementById("create-user-container");
  if (addEventContainer) addEventContainer.style.display = isAdmin ? "block" : "none";
  if (createUserContainer) createUserContainer.style.display = isAdmin ? "block" : "none";
}
